<?php

namespace App\Http\Controllers\Equipments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipments\StoreEquipmentsRequest;
use App\Repositories\Equipment\EquipmentRepositoryEloquent;
use App\Repositories\Images\ImageRepositoryEloquent;
use App\Repositories\Services\ServiceRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentsController extends Controller
{

    private $EquipmentRepositoryEloquent;
    
    private $ImageRepositoryEloquent;

    private $ServiceRepositoryEloquent;

    function __construct(
        EquipmentRepositoryEloquent $EquipmentRepositoryEloquent,
        ImageRepositoryEloquent $ImageRepositoryEloquent,
        ServiceRepositoryEloquent $ServiceRepositoryEloquent
    )
    {
        $this->EquipmentRepositoryEloquent = $EquipmentRepositoryEloquent;
        $this->ImageRepositoryEloquent     = $ImageRepositoryEloquent;
        $this->ServiceRepositoryEloquent   = $ServiceRepositoryEloquent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
        ]);
        
        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        return $this->EquipmentRepositoryEloquent->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEquipmentsRequest $request)
    {

        DB::beginTransaction();

        try{

            $store = $this->EquipmentRepositoryEloquent->save($request->all());

            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $store, [
                "path" => "equipments"
            ]);

            $this->scheduleService($store);

            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $store
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
        $data = $this->EquipmentRepositoryEloquent->find($id)->load([
            "parts",
            "images"
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
    public function update(StoreEquipmentsRequest $request, $id)
    {
        DB::beginTransaction();

        try{
            
            $values = $request->all();

            $store = $this->EquipmentRepositoryEloquent->saveUpdate($values, $id);
            
            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $store, [
                "path" => "equipments"
            ]);

            $this->scheduleService($store);

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
        try{

            $this->EquipmentRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }

    /**
     * Se encarga de agendar todos los servicios automáticos de los equipos
     */
    public function scheduleServices(){
        $equipments = $this->EquipmentRepositoryEloquent->all();

        foreach ($equipments as $equipment) {
            $this->scheduleService($equipment);
        }
    }

    /**
     * Agenda servicios según los parametros del equipo
     * 
     * @param collection $equipment Instancia de equipo
     */
    private function scheduleService($equipment){
        $hasParts = $equipment->parts()->count() > 0? true : false;
        
        if($equipment->create_services_automatic && $equipment->last_service_at && $equipment->between_days_service > 0 && $equipment->days_before_create){
            $this->newService($equipment->services(), [
                "between_days_service" => $equipment->between_days_service,
                "last_service_at"      => $equipment->last_service_at,
                "equipment_id"         => $equipment->id,
                "equipments_part_id"   => null,
                "days_before_create"   => $equipment->days_before_create
            ]);
        }

        if($hasParts && $equipment->days_before_create){
            foreach ($equipment->parts as $part) {
                if($part->create_services_automatic && $part->last_service_at && $part->between_days_service > 0){
                    $this->newService($part->services(), [
                        "between_days_service" => $part->between_days_service,
                        "last_service_at"      => $part->last_service_at,
                        "equipment_id"         => $equipment->id,
                        "equipments_part_id"   => $part->id,
                        "days_before_create"   => $equipment->days_before_create
                    ]);
                }
            }
        }
    }

    /**
     * Lógica que genera los servicios según las condiciones de los anteriores ya realizados o generados
     * 
     * @param collection $services Lista de Servicios del equipo o la pieza
     * @param array $values Valores que interactuan con las condiciones
     */
    private function newService($services, $values = []){

        $lastServiceAutomatic = $services->where("is_automatic", 1)->latest('created_at')->first();
        $afterToday = false; // Asegurar que la fecha agendada sea después de hoy para que no sea vencida
        $eventDateAutomatic = $lastServiceAutomatic->event_date ?? null;

        while ($afterToday == false) {
            $next_service_at = null;

            // Si no se ha modificado el registro automatico anterior (equal),
            // entonces se elimina para crear el nuevo según la ultima fecha de servicio
            if($lastServiceAutomatic && is_null($lastServiceAutomatic->completed_at) && $lastServiceAutomatic->created_at->eq($lastServiceAutomatic->updated_at)){
                $lastServiceAutomatic->forceDelete();

                $beforeLastServiceAutomatic = $services->where("is_automatic", 1)->latest('created_at')->first();

                // si hay un servicio anterior al que se ha eliminado, entonces se toma como referencia para agendar el siguiente servicio
                if($beforeLastServiceAutomatic){
                    $values["last_service_at"] = $beforeLastServiceAutomatic->event_date;
                }

            }
    
            // En caso de que se haya modificado. Se crea un nuevo servicio en base a ese servicio automatico
            if($lastServiceAutomatic && $lastServiceAutomatic->trashed() == false && $lastServiceAutomatic->created_at->ne($lastServiceAutomatic->updated_at)){
                $next_service_at = Carbon::parse($eventDateAutomatic)->add($values["between_days_service"], "day");
            }
    
            // Si no tiene fecha siguiente todavía, se agenda entonces
            if(is_null($next_service_at)){
                $next_service_at = Carbon::parse($values["last_service_at"])->add($values["between_days_service"], "day");
            }

            $values["last_service_at"] = $next_service_at;
            $eventDateAutomatic        = $next_service_at;
            
            if($next_service_at->gt(now())){
                $afterToday = true;
            }
        }
        
        // Crear el servicio la cantidad de días antes definidas en el equipo...

        $compareNewDate = Carbon::parse($next_service_at)->addDay(-$values["days_before_create"]); 

        if(now()->gte($compareNewDate)){
            $this->ServiceRepositoryEloquent->save([
                "categories_service_id"   => 1,
                "equipment_id"            => $values["equipment_id"],
                "equipments_part_id"      => $values["equipments_part_id"],
                "event_date"              => $next_service_at,
                "priorities_service_id"   => 1,
                "is_automatic"            => 1
            ]);
        }
        
    }

}
