<?php

namespace App\Http\Controllers\Vehicle;

use App\Criteria\VehicleCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Vehicle\FuelRepositoryEloquent;
use App\Repositories\Vehicle\VehicleKmTrackerRepositoryEloquent;
use App\Rules\IsLastFuel;
use App\Rules\KmLessThat;
use App\Rules\KmLimiTravel;
use App\Rules\VehicleLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelsController extends Controller
{
    private $FuelRepositoryEloquent;

    private $VehicleKmTrackerRepositoryEloquent;

    function __construct(
        FuelRepositoryEloquent $FuelRepositoryEloquent,
        VehicleKmTrackerRepositoryEloquent $VehicleKmTrackerRepositoryEloquent
    )
    {
        $this->FuelRepositoryEloquent = $FuelRepositoryEloquent;
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

        $this->FuelRepositoryEloquent->pushCriteria(VehicleCriteria::class);

        return $this->FuelRepositoryEloquent->paginate($perPage);
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
            "vehicle_id"    => [
                "required", 
                "exists:vehicles,id", 
                new KmLessThat($request->km_current),
                new VehicleLimitService($request->km_current ?? null),
            ],
            "lts_loaded"    => "required|numeric|min:0",
            "amount"        => "required|numeric|min:0",
            "km_current"    => "required|numeric|min:0",
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->FuelRepositoryEloquent->save($request->all());
            
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
        $data = $this->FuelRepositoryEloquent->find($id)->load([
            "vehicle",
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

        // Kilometraje recorrido hasta este registro especifico
        $fuel         = $this->FuelRepositoryEloquent->find($id);
        $traveledCurrent = $this->VehicleKmTrackerRepositoryEloquent->kmLastRoadTraveled($fuel, $fuel->vehicle ?? null);
        $limitTraveled   = $this->VehicleKmTrackerRepositoryEloquent->kmNextTraveled($fuel, $fuel->vehicle ?? null);

        $rulesVehicle   = ["required", "exists:vehicles,id", ];

        if($fuel && $fuel->is_last_loaded){
            array_push($rulesVehicle, new KmLessThat($request->km_current, $traveledCurrent));
            array_push($rulesVehicle, new VehicleLimitService($request->km_current ?? null));
            array_push($rulesVehicle, new KmLimiTravel($request->km_current ?? null, $limitTraveled));
        }

        $request->validate([
            "vehicle_id"    => $rulesVehicle,
            "lts_loaded"    => "required|numeric|min:0",
            "amount"        => "required|numeric|min:0",
            "km_current"    => "required|numeric|min:0",
        ]);

        DB::beginTransaction();

        try{
            $data = $fuel->is_last_loaded? $request->all() : $request->except(["km_current"]);

            $this->FuelRepositoryEloquent->saveUpdate($data, $id);
            
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

            $this->FuelRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
