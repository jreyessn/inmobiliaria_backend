<?php

namespace App\Http\Controllers\Provider;

use App\Criteria\PhaseProviderCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProviderRequestEdit;

use App\Notifications\Providers\RequestEditInformation;
use App\Notifications\Providers\ApprovedEditInformation;
use App\Notifications\Providers\RejectEditInformation;
use App\Http\Controllers\Controller;

use App\Repositories\Users\UserRepositoryEloquent;
use App\Http\Requests\Provider\ProviderStoreRequest;
use App\Models\Provider\ProviderDocument;
use App\Models\TypeProvider;
use App\Notifications\Providers\Contracted;
use App\Notifications\Providers\ProviderRegistered;
use App\Repositories\Provider\ProviderRepositoryEloquent;
use App\Repositories\Provider\ProviderSapAuthoRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{

    private $providerRepository;

    public function __construct(
        ProviderRepositoryEloquent $providerRepository,
        UserRepositoryEloquent $userRepository,
        ProviderSapAuthoRepositoryEloquent $providerSapAutho
    ){
        $this->providerRepository = $providerRepository;
        $this->userRepository = $userRepository;
        $this->providerSapAutho = $providerSapAutho;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->providerRepository->pushCriteria(PhaseProviderCriteria::class);
        $list =  $this->providerRepository->list();

        return $list;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->all();

            $store = $this->providerRepository->save($data);   

            
            /* Notificar a compras */
            
            $typeProvider = $request->user()->applicant_requested->type_provider ?? 0;
            $existType = TypeProvider::where('description', $typeProvider)->get()->count();
            
            /* Se buscan aquellos usuarios de compras que tienen el mismo tipo de proveedor para notificarles */
            
            if($existType == 0){
                $users = $this->userRepository->getUsersPermissionPurchases();
            }
            else{
                $users =  $this->userRepository->getUsersPurchasesByTypeProvider($typeProvider);
            }
            
            Notification::send($users, new ProviderRegistered($store->applicant_name));
            
            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $store
            ], 201);
        } 
        catch (\Exception $th) {
            DB::rollback();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->providerRepository->with([
            'account_bank',
            'retention_types',
            'retention_indicators',
            'documents',
            'business_type',
            'references'
        ])->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->all();

            $store = $this->providerRepository->saveUpdate($data);   
            
            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
                "data" => $store
            ], 201);
        } 
        catch (\Exception $th) {
            DB::rollback();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /* 
    * Contratar proveedor (si esto cambia, considerar hacerlo en una tabla polimorfica para almacenar los logs de contrataciones)
    */

    public function contract(Request $request){
        $provider = $this->providerRepository->find($request->provider_id);

        try {
            $fillData = $request->all();
            $fillData['contracted_by_user_id'] = $request->user()->id;
            $fillData['contracted_at'] = Carbon::now();

            $provider->fill($fillData);
            $provider->save();

            if($request->contracted == 1){
                $provider->user->notify(new Contracted);
            }

            return response()->json([
                "message" => "Contratación éxitosa",
            ], 200);
        } 
        catch (\Exception $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        } 

    }

    /* 
    * Inactivación (panel administrativo)
    * Se utiliza el mismo endpoint para reactivar/inactivar. Cuando es reactivar la fecha de inactivated_at
    * se deja en nulo, se guarda el historial y se le notifica al proveedor para que pueda editar su información
    */
    public function inactive(Request $request){
        $provider = $this->providerRepository->find($request->provider_id);

        try {
            $fillData['reason_inactivated'] = $request->reason;
            $fillData['inactivated_at'] = $request->inactivate ? Carbon::now() : null;
            $fillData['can_edit'] = $request->inactivate ? 0 : 1;

            $provider->fill($fillData);
            $provider->save();

            return response()->json([
                "message" => "Operación éxitosa",
            ], 200);
        } 
        catch (\Exception $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        } 

    }

    /**
     * El proveedor envia post para solicitar editar su info. Se notifica por correo a todos los usuarios
     * de compras
     */

    public function requestEditInformation(Request $request){

        $requestSends = ProviderRequestEdit::where([
            'provider_id' => $request->id,
            'approved' => 0
        ])->get()->count(); 
    
        // limite de solicitudes 

        if($requestSends == 2)
            return response()->json(['message' => 'Ya tiene dos solicitudes pendientes', 'status' => false], 200);

        $providerRequest = ProviderRequestEdit::create([
            'provider_id' => $request->id,
            'reason' => $request->reason
        ]);
        
        $toUsers = $this->userRepository->getUsersPermissionPurchases();

        foreach($toUsers as $toUser){  
            $toUser->notify(new RequestEditInformation($providerRequest));      
        }

        return response()->json(['message' => 'Solicitud enviada correctamente', 'status' => true], 200);
    }

    /* 
     * Solo el rol necesario puede aceptar la edicion del proveedor
    */

    public function approvedEditInformation(Request $request){
        $user = $request->user();
        
        $providerRequest = ProviderRequestEdit::find($request->id);

        // validations

        if($providerRequest == null)
            return response()->json(['message' => 'ID de solicitud no encontrada'], 404);
            
        if($providerRequest->approved == 1)
            return response()->json(['message' => 'Solicitud ya se encuentra aprobada'], 400);
        
        if($providerRequest->approved == 2)
            return response()->json(['message' => 'Solicitud se encuentra rechazada'], 400);

        if(!$user->hasPermissionTo('approve providers edit'))
            return response()->json([
                'message' => 'Usuario no cuenta con el permiso necesario para aprobar solicitudes'
            ], 400);

        // actualizar y notificar al usuario

        $providerRequest->provider->update(['can_edit' => $request->approved]);

        ProviderRequestEdit::where([
                                'provider_id' => $providerRequest->provider_id,
                                'approved' => 0
                            ])
                            ->update([
                                'user_id' => $user->id, 
                                'approved' => $request->approved? 1 : 2,
                                'note' => $request->note
                            ]);

        if($request->approved){
            $providerRequest->provider->user->notify(new ApprovedEditInformation);
            return response()->json(['message' => 'Se ha aprobado la solicitud'], 200);
        }
       
        $providerRequest->provider->user->notify(new RejectEditInformation($request->note));
        return response()->json(['message' => 'Se ha rechazado la solicitud'], 200);
    }

    /** 
     * Mostrar los detalles del documento del proveedor
    */

    public function showDocument(Request $request, $id, $download = null){

        $provider = ProviderDocument::with(['provider', 'user_approver'])->find($id);
        
        if(!$download)
            return $provider;
        return Storage::disk('local')->download( $provider->document->folder.'/'.$provider->name, $provider->name);
    }

    /* 
    * Se muestra el proveedor relacionado a la id de la solicitud 
    */

    public function requestEditShow($id){
        return ProviderRequestEdit::with('provider')->findOrFail($id);
    }
}
