<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Services\TypesServiceRepositoryEloquent;
use Illuminate\Support\Facades\DB;

class TypesServicesController extends Controller
{
   
    private $TypesServiceRepositoryEloquent;

    function __construct(
        TypesServiceRepositoryEloquent $TypesServiceRepositoryEloquent
    )
    {
        $this->TypesServiceRepositoryEloquent = $TypesServiceRepositoryEloquent;
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

        return $this->TypesServiceRepositoryEloquent->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationMessages = [];
        
        foreach ($request->get('spare_parts') ?? [] as $key => $val) {
            $validationMessages["spare_parts.*.exists"] = "La refacción N° ".($key + 1)." no existe";
        }
        
        $request->validate([
            "name"          => "required|string|max:200|unique:type_services,name,NULL,id,deleted_at,NULL",
            "description"   => "string|nullable",
            "spare_parts.*" => "exists:spare_parts,id",
        ], $validationMessages);

        DB::beginTransaction();

        try{
            
            $data = $this->TypesServiceRepositoryEloquent->save($request->all());
            
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
        $data = $this->TypesServiceRepositoryEloquent->find($id)->load("spare_parts");

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
        $validationMessages = [];
        
        foreach ($request->get('spare_parts') ?? [] as $key => $val) {
            $validationMessages["spare_parts.*.exists"] = "La refacción N° ".($key + 1)." no existe";
        }
        
        $request->validate([
            "name"          => "required|string|max:200|unique:type_services,name,{$id},id,deleted_at,NULL",
            "description"   => "string|nullable",
            "spare_parts.*" => "exists:spare_parts,id",
        ], $validationMessages);
        
        DB::beginTransaction();

        try{
            $this->TypesServiceRepositoryEloquent->saveUpdate($request->all(), $id);
            
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

            $this->TypesServiceRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
