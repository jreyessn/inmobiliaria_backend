<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Repositories\Images\ImageRepositoryEloquent;
use App\Repositories\Vehicle\ServiceVehicleRepositoryEloquent;
use App\Repositories\Vehicle\VehicleKmTrackerRepositoryEloquent;
use App\Rules\KmLessThat;
use App\Rules\KmLimiTravel;
use App\Rules\ServiceVehicleCompleted;
use App\Rules\VehicleLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceVehicleController extends Controller
{
    private $ServiceVehicleRepositoryEloquent;

    private $ImageRepositoryEloquent;

    private $VehicleKmTrackerRepositoryEloquent;

    function __construct(
        ServiceVehicleRepositoryEloquent $ServiceVehicleRepositoryEloquent,
        ImageRepositoryEloquent $ImageRepositoryEloquent,
        VehicleKmTrackerRepositoryEloquent $VehicleKmTrackerRepositoryEloquent
    )
    {
        $this->ServiceVehicleRepositoryEloquent = $ServiceVehicleRepositoryEloquent;
        $this->ImageRepositoryEloquent = $ImageRepositoryEloquent;
        $this->VehicleKmTrackerRepositoryEloquent = $VehicleKmTrackerRepositoryEloquent;
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

        return $this->ServiceVehicleRepositoryEloquent->paginate($perPage);
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
            "vehicle_id"                => [
                "required", 
                "exists:vehicles,id", 
                new KmLessThat($request->km_current),
                new VehicleLimitService($request->km_current)
            ],
            "km_current"                => "required|numeric|min:0",
            "type_service_vehicle_id"   => "required|exists:type_service_vehicles,id",
            "event_date"                => "required|date|after_or_equal:today",
            "amount"                    => "nullable|numeric|min:0",
            "note"                      => "nullable|string",
        ], [
            "event_date.after_or_equal" => "El campo :attribute debe ser una fecha posterior o igual a hoy."
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->ServiceVehicleRepositoryEloquent->save($request->all());
            
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
        $data = $this->ServiceVehicleRepositoryEloquent->find($id)->load([
            "vehicle",
            "type_service_vehicle",
            "evidences"
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
    public function update(Request $request, $id)
    {
        $serviceCurrent  = $this->ServiceVehicleRepositoryEloquent->find($id);
        $traveledCurrent = $this->VehicleKmTrackerRepositoryEloquent->kmLastRoadTraveled($serviceCurrent, $serviceCurrent->vehicle ?? null);
        $limitTraveled   = $this->VehicleKmTrackerRepositoryEloquent->kmNextTraveled($serviceCurrent, $serviceCurrent->vehicle ?? null);

        $request->validate([
            "id"                        => [ new ServiceVehicleCompleted ],
            "vehicle_id"                => [
                "required_without:save_from", 
                "exists:vehicles,id", 
                new KmLessThat($request->km_current ?? null, $traveledCurrent),
                new VehicleLimitService($request->km_current ?? null),
                new KmLimiTravel($request->km_current ?? null, $limitTraveled)
            ],
            "km_current"                => "required_without:save_from|numeric|min:0",
            "type_service_vehicle_id"   => "required_without:save_from|exists:type_service_vehicles,id",
            "event_date"                => "required_without:save_from|date|after_or_equal:today",
            "amount"                    => "nullable|numeric|min:0",
            "note"                      => "nullable|string",

            "status"                    => "nullable",
            "completed_at"              => "nullable",
            "observation"               => "nullable",
            "evidences.*"               => "file|mimes:jpg,jpeg,png",

        ], [
            "event_date.after_or_equal" => "El campo :attribute debe ser una fecha posterior o igual a hoy."
        ]);

        DB::beginTransaction();

        try{
            $data = $this->ServiceVehicleRepositoryEloquent->saveUpdate($request->all(), $id);
            
            $this->ImageRepositoryEloquent->saveMany($request->file("evidences") ?? [], $data, [
                "path" => "evidences_vehicles_services",
                "type" => "Evidences",
            ]);

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

            $this->ServiceVehicleRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }

    /**
     * Se completa el servicio
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function comply(Request $request, $id)
    {
        $service = $this->ServiceVehicleRepositoryEloquent->find($id);

        $request->merge([
            "id"           => $service->id,
            'save_from'    => true,
            "vehicle_id"   => $service->vehicle_id,
            "completed_at" => now(),
            "status"       => 1
        ]);

        return $this->update($request, $id);
    }
}
