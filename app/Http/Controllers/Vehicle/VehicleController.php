<?php

namespace App\Http\Controllers\Vehicle;

use App\Criteria\VehicleCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Vehicle\VehicleRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{

    private $VehicleRepositoryEloquent;

    function __construct(
        VehicleRepositoryEloquent $VehicleRepositoryEloquent
    )
    {
        $this->VehicleRepositoryEloquent = $VehicleRepositoryEloquent;
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

        return $this->VehicleRepositoryEloquent->paginate($perPage);
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
            "name"                    => "required|string|max:200|unique:vehicles,name,NULL,id,deleted_at,NULL",
            "brand"                   => "required|string|max:200",
            "model"                   => "required|string|max:200",
            "license_plate"           => "required|string|max:200",
            "expiration_license_at"   => "nullable|date",
            "no_serie"                => "nullable|string|max:200",
            "user_id"                 => "required|exists:users,id",
            "insurance_policy"        => "nullable|string|max:200",
            "comments"                => "nullable|string",
            "expiration_policy_at"    => "nullable|date",
            "km_start"                => "required|numeric|min:0",
            "km_limit"                => "required|numeric|min:0",
            "maintenance_limit_at"    => "nullable|date",
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->VehicleRepositoryEloquent->save($request->all());
            
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
        $data = $this->VehicleRepositoryEloquent->find($id)->load([
            "services",
            "fuels",
            "license_plates",
            "payments",
            "permissions",
        ]);

        $data->acumulated_amount = $this->VehicleRepositoryEloquent->transformToAccumulatedAmount($data);

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
            "name"                    => "required|string|max:200|unique:vehicles,name,{$id},id,deleted_at,NULL",
            "brand"                   => "required|string|max:200",
            "model"                   => "required|string|max:200",
            "license_plate"           => "required|string|max:200",
            "expiration_license_at"   => "nullable|date",
            "no_serie"                => "nullable|string|max:200",
            "user_id"                 => "required|exists:users,id",
            "insurance_policy"        => "nullable|string|max:200",
            "comments"                => "nullable|string",
            "expiration_policy_at"    => "nullable|date",
            "km_start"                => "required|numeric|min:0",
            "km_limit"                => "required|numeric|min:0",
            "maintenance_limit_at"    => "nullable|date",
        ]);
        
        DB::beginTransaction();

        try{
            $this->VehicleRepositoryEloquent->saveUpdate($request->all(), $id);
            
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

            $this->VehicleRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
