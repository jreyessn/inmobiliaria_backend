<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\StoreServicesRequest;
use App\Repositories\Images\ImageRepositoryEloquent;
use App\Repositories\Services\ServiceRepositoryEloquent;
use App\Rules\ServiceCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{

    private $ServiceRepositoryEloquent;

    private $ImageRepositoryEloquent;

    function __construct(
        ServiceRepositoryEloquent $ServiceRepositoryEloquent,
        ImageRepositoryEloquent $ImageRepositoryEloquent
    )
    {
        $this->ServiceRepositoryEloquent = $ServiceRepositoryEloquent;
        $this->ImageRepositoryEloquent   = $ImageRepositoryEloquent;
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

        return $this->ServiceRepositoryEloquent->paginate($perPage);
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
            "equipments_part_id"    => "required|exists:equipments_parts,id",
            "user_assigned_id"      => "required|exists:users,id",
            "farm_id"               => "required|exists:farms,id",
            "event_date"            => "required|date",
            "note"                  => "nullable|string",
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->ServiceRepositoryEloquent->save($request->all());
            
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
            "type_service",
            "category",
            "equipment_part",
            "equipment",
            "farm",
            "user_assigned",
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
            
            $this->ImageRepositoryEloquent->saveMany($request->file("evidences") ?? [], $data, [
                "path" => "evidences_services",
                "type" => "Evidences",
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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
        return $this->update($request, $id);
    }

}