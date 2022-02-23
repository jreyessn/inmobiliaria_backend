<?php

namespace App\Http\Controllers\Vehicle;

use App\Criteria\VehicleCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Vehicle\PermissionsVehicleRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionsVehicleController extends Controller
{
    private $PermissionsVehicleRepositoryEloquent;

    function __construct(
        PermissionsVehicleRepositoryEloquent $PermissionsVehicleRepositoryEloquent
    )
    {
        $this->PermissionsVehicleRepositoryEloquent = $PermissionsVehicleRepositoryEloquent;
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

        $this->PermissionsVehicleRepositoryEloquent->pushCriteria(VehicleCriteria::class);

        return $this->PermissionsVehicleRepositoryEloquent->paginate($perPage);
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
            "vehicle_id"    => "required|exists:vehicles,id",
            "concept"       => "required|string|max:200",
            "expiration_at" => "required|date",
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->PermissionsVehicleRepositoryEloquent->save($request->all());
            
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
        $data = $this->PermissionsVehicleRepositoryEloquent->find($id)->load([
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
        
        $request->validate([
            "vehicle_id"    => "required|exists:vehicles,id",
            "concept"       => "required|string|max:200",
            "expiration_at" => "required|date",
        ]);

        DB::beginTransaction();

        try{
            $this->PermissionsVehicleRepositoryEloquent->saveUpdate($request->all(), $id);
            
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

            $this->PermissionsVehicleRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
