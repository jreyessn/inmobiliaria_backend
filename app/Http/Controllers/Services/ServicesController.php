<?php

namespace App\Http\Controllers\Services;

use App\Criteria\CategoriesServicesCriteria;
use App\Criteria\EquipmentCriteria;
use App\Criteria\EquipmentPartAvailableCriteria;
use App\Criteria\HasTechnicalCriteria;
use App\Criteria\SinceUntilCreatedAtCriteria;
use App\Criteria\SinceUntilEventAtCriteria;
use App\Criteria\StatusServiceCriteria;
use App\Criteria\UserAssignedCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Services\StoreServicesRequest;
use App\Notifications\Services\NewService;
use App\Repositories\Images\ImageRepositoryEloquent;
use App\Repositories\Services\ServiceRepositoryEloquent;
use App\Repositories\Users\UserRepositoryEloquent;
use App\Rules\ServiceAutomatic;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{

    private $ServiceRepositoryEloquent;

    private $ImageRepositoryEloquent;

    private $UserRepositoryEloquent;

    function __construct(
        ServiceRepositoryEloquent $ServiceRepositoryEloquent,
        ImageRepositoryEloquent $ImageRepositoryEloquent,
        UserRepositoryEloquent $UserRepositoryEloquent
    )
    {
        $this->ServiceRepositoryEloquent = $ServiceRepositoryEloquent;
        $this->ImageRepositoryEloquent   = $ImageRepositoryEloquent;
        $this->UserRepositoryEloquent     = $UserRepositoryEloquent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'perPage'                =>  'nullable|integer',
            'page'                   =>  'nullable|integer',
            'search'                 =>  'nullable|string',
            'orderBy'                =>  'nullable|string',
            'sortBy'                 =>  'nullable|in:desc,asc',
            'since'                  =>  'nullable|date',
            'until'                  =>  'nullable|date',
            'user_assigned_id'       =>  'nullable|string',
            'equipment_id'           =>  'nullable|string',
            'categories_service_id'  =>  'nullable|numeric',
        ]);
        
        $this->ServiceRepositoryEloquent->pushCriteria(SinceUntilEventAtCriteria::class);
        $this->ServiceRepositoryEloquent->pushCriteria(UserAssignedCriteria::class);
        $this->ServiceRepositoryEloquent->pushCriteria(EquipmentCriteria::class);
        $this->ServiceRepositoryEloquent->pushCriteria(CategoriesServicesCriteria::class);
        $this->ServiceRepositoryEloquent->pushCriteria(StatusServiceCriteria::class);
        $this->ServiceRepositoryEloquent->pushCriteria(HasTechnicalCriteria::class);

        return $this->ServiceRepositoryEloquent->customPaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            "categories_service_id" => "required|exists:categories_services,id",
            "type_service_id"       => "required|exists:type_services,id",  
            "equipment_id"          => "required|exists:equipments,id",
            "equipments_part_id"    => "nullable|exists:equipments_parts,id",
            "user_assigned_id"      => "required|exists:users,id",
            "farm_id"               => "required|exists:farms,id",
            "priorities_service_id" => "required|exists:priorities_services,id",
            "event_date"            => "required|date|after_or_equal:today",
            "note"                  => "nullable|string",
        ], [
            "event_date.after_or_equal" => "El campo :attribute debe ser una fecha posterior o igual a hoy."
        ]);

        DB::beginTransaction();

        try{
            $data = $this->ServiceRepositoryEloquent->save($request->all());
            
            $this->notifyAdmins($data);

            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $data
            ], 201);

        }catch(\Exception $e){
            DB::rollback();
            
            return response()->json(['message' => $e->getMessage()], 500);
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
        $data = $this->ServiceRepositoryEloquent->find($id)->load([
            "equipments_part",
            "equipment.parts",
            "equipment.images",
        ]);

        return ["data" => $data];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreServicesRequest $request, $id)
    {
        DB::beginTransaction();

        try{

            $data = $this->ServiceRepositoryEloquent->saveUpdate($request->all(), $id);
            
            $this->ImageRepositoryEloquent->saveMany($request->file("evidences_after") ?? [], $data, [
                "path" => "evidences_services",
                "type" => "Evidences_After",
            ]);

            $this->ImageRepositoryEloquent->saveMany($request->file("evidences_before") ?? [], $data, [
                "path" => "evidences_services",
                "type" => "Evidences_Before",
            ]);

            if($request->signature){
                $this->ImageRepositoryEloquent->destroy($data, [ "type" => "Signature" ]);
                $this->ImageRepositoryEloquent->saveBase64($request->signature, $data, [
                    "path" => "signatures_services",
                    "type" => "Signature"
                ]);
            }

            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
            ], 200);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Patch update records
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patchUpdate(Request $request, $id)
    {
        $request->validate([
            "event_date" => "required_unless:event_date,null|date"
        ]);

        DB::beginTransaction();

        try{
            $body = sanitize_null($request->all());

            $this->ServiceRepositoryEloquent->saveUpdate($body, $id);

            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
            ], 200);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
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
        $request = new Request(["id" => $id]);
        $request->validate([
            "id" => new ServiceAutomatic
        ]);

        try{
            $this->ServiceRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }

    /**
     * El tecnico completa el servicio desde la pwa
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function comply(StoreServicesRequest $request, $id)
    {
        $service = $this->ServiceRepositoryEloquent->find($id);
        $requestComply = new Request([
            "user_assigned_id" => $service->user_assigned_id,
            "type_service_id"  => $service->type_service_id,
        ]);
        $requestComply->validate([
            "user_assigned_id" => "required|exists:users,id",
            "type_service_id"  => "required|exists:type_services,id",
        ], [
            "user_assigned_id.required" => "El servicio debe tener un técnico asignado",
            "type_service_id.required"  => "El servicio debe tener un tipo de servicio asignado",
        ]);

        return $this->update($request, $id);
    }

    /**
     * PDF de los detalles del servicio
     * 
     * @param int $id ID de servicio
     */
    public function pdfDetail($id){

        $data["service"] = $this->ServiceRepositoryEloquent->find($id);
        // dd($data);
        $pdf = PDF::loadView('reports/pdf/detail_service', $data);
        
        return $pdf->download('detail.pdf');
    }

    /**
     * Notificar a los administradores
     * 
     * @param collection $service Instancia de servicio
     */
    private function notifyAdmins($service){
        $admins = $this->UserRepositoryEloquent->getAdminUsers();
        $values = [
            "service_category"    => $service->categories_service->name,
            "equipment_name"      => $service->equipment->name,
            "equipment_part_name" => $service->equipments_part->name ?? "No aplica",
            "event_date"          => $service->event_date->format("d/m/Y"),
            "id"                  => $service->id,
        ];

        foreach ($admins as $admin) {
            if($admin->id != request()->user()->id){
                $admin->notify(new NewService($values));
            }
        }
    }

}