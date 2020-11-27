<?php

namespace App\Http\Controllers\ApplicantProviders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\TypeProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Users\UserRepositoryEloquent;
use App\Http\Requests\Applicant\ApplicantStoreRequest;
use App\Notifications\ApplicantProvider\RejectCreateUser;
use App\Notifications\ApplicantProvider\ApprovedCreateUser;
use App\Notifications\ApplicantProvider\ApprovedCreateUserToApplicant;
use App\Notifications\ApplicantProvider\NotifyToPurchases;
use App\Repositories\ApplicantProvider\ApplicantProviderRepositoryEloquent;

class ApplicantProvidersController extends Controller
{

    private $repository;
    private $userRepository;

    function __construct(
        ApplicantProviderRepositoryEloquent $repository,
        UserRepositoryEloquent $userRepository
    )
    {

        $this->middleware('auth:api', ['except' => ['store']]);

        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->customPaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApplicantStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->all();
        
            $existType = TypeProvider::where('description', $data['type_provider'])->get()->count();

            /* Se buscan aquellos usuarios de compras que tienen el mismo tipo de proveedor para notificarles */

            if($existType == 0){
                $users = $this->userRepository->getUsersPermissionPurchases();
            }
            else{
                $users =  $this->userRepository->getUsersPurchasesByTypeProvider($data['type_provider']);
            }

            $store = $this->repository->save($data);   
            
            Notification::send($users, new NotifyToPurchases($data['fullname_applicant']));

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
        return $this->repository->with(['user', 'user_approver'])->find($id);
    }

    /**
     * Download file the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadAuthorization($id)
    {
        $applicant = $this->repository->find($id);

        return Storage::disk('local')->download('applicant_providers_authorizations/'.$applicant->authorization_file, $applicant->authorization_file);
    }

    /**
     * Change status the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'note' => 'required'
        ],[
            'note.required' => 'La nota es requerida'
        ]);

        try {
            DB::beginTransaction();
            
            $userApprover = $request->user();
            $applicant = $this->repository->find($id);

            /* 
            * Cuando el usuario no coincide con el tipo de proveedor y el tipo de proveedor existe en la tabla, signifca que el usuario no puede aprobar 
            * este proveedor
            *
            * El otro caso es cuando el tipo de proveedor es "Otros", dicho caso cualquier usuario puede aprobarlo y la consulta daría null, por lo que
            * la condición no se cumple
            */

            $typeProviderUser = $userApprover->type_provider->description ?? null;

            if(
               !is_null($typeProviderUser) && // si es nulo, puede aprobar todo
               $typeProviderUser != $applicant->type_provider &&  // si no coincide con el proveedor, no puede aprobar
               TypeProvider::where('description', $applicant->type_provider)->first()) // si el tipo de proveedor es "otros" o está en bd
            {
              return response()->json([
                    'errors' => [
                        'validation' => [
                            "El usuario actual solo puede aprobar/rechazar tipos de proveedores '{$typeProviderUser}' y 'Otros'"
                        ]
                    ]
                ], 422);
            }

            $applicant->update([
                'status' => $request->status,
                'note' => $request->note,
                'approver_by_user_id' => $userApprover->id,
                'approved_at' => Carbon::now()
            ]);

            if($request->status == 1){

                /* Se crea el usuario automaticamente */
                
                $username = preg_replace('/@.*?$/', '', $applicant->email_provider);
                $password = strtoupper(Str::random(6));
                
                $user = $this->userRepository->create([
                    'name' => $applicant->business_name,
                    'email' => $applicant->email_provider,
                    'username' => "{$username}-{$applicant->id}",
                    'password' => $password,
                ]);
                    
                $user->roles()->sync([2]);
                $user->notify(new ApprovedCreateUser([
                    'email' => $user->email,
                    'password' => $password
                ])); 

                Notification::route('mail', $applicant->email_applicant)->notify(new ApprovedCreateUserToApplicant($applicant->tradename));
                
                $applicant->update(['user_id' => $user->id]);
            }
            else{
                Notification::route('mail', $applicant->email_applicant)->notify(new RejectCreateUser($request->note));
            }

            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
                "data" => null
            ], 201);
        }
        catch (\Exception $th) {
            DB::rollback();

            return response()->json(['message' => $th->getMessage()], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
