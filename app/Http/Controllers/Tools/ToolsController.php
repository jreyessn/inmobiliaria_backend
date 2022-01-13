<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Tools\ToolRepositoryEloquent;
use Illuminate\Support\Facades\DB;

class ToolsController extends Controller
{

    private $ToolRepositoryEloquent;

    function __construct(
        ToolRepositoryEloquent $ToolRepositoryEloquent
    )
    {
        $this->ToolRepositoryEloquent = $ToolRepositoryEloquent;
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

        return $this->ToolRepositoryEloquent->paginate($perPage);
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
            "name"     => "required|string|max:200|unique:tools,name,NULL,id,deleted_at,NULL",
            "quantity" => "required|numeric|min:0",
            "user_id"  => "nullable|exists:users,id"
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->ToolRepositoryEloquent->save($request->all());
            
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
        $data = $this->ToolRepositoryEloquent->find($id)->load("user");

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
            "name"     => "required|string|max:200|unique:tools,name,{$id},id,deleted_at,NULL",
            "quantity" => "required|numeric|min:0",
            "user_id"  => "nullable|exists:users,id"
        ]);
        
        DB::beginTransaction();

        try{
            $this->ToolRepositoryEloquent->saveUpdate($request->all(), $id);
            
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

            $this->ToolRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
