<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Repositories\Vehicle\ServiceVehicleRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceVehicleController extends Controller
{
    private $ServiceVehicleRepositoryEloquent;

    function __construct(
        ServiceVehicleRepositoryEloquent $ServiceVehicleRepositoryEloquent
    )
    {
        $this->ServiceVehicleRepositoryEloquent = $ServiceVehicleRepositoryEloquent;
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
            "vehicle_id"                => "required|exists:vehicles,id",
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
        
        $request->validate([
            "vehicle_id"                => "required|exists:vehicles,id",
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
            $this->ServiceVehicleRepositoryEloquent->saveUpdate($request->all(), $id);
            
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
}
