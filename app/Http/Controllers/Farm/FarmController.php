<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Repositories\Farm\FarmRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmController extends Controller
{

    private $repository;

    public function __construct(
        FarmRepositoryEloquent $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'all'           =>  'nullable|in:true,false',
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc'
        ]);

        $perPage = $request->get('perPage', config('repository.pagination.limit'));
        $all = $request->get('all', null);

        if($all) $perPage = 99999999;

        return $this->repository->paginate($perPage);
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
            "centro" => 'required|string:200',
            "supervisor" => 'nullable|string:200',
            "gerente" => 'nullable|string:200',
            "nombre_centro" => 'nullable|string:200',
            "nombre_supervisor" => 'nullable|string:200',
            "nombre_gerente" => 'nullable|string:200',
        ]);

        DB::beginTransaction();

        try{
            $this->repository->save($request->all());
            
            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
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
        return [
            'data' => $this->repository->find($id)
        ];
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
            "centro" => 'required|string:200',
            "supervisor" => 'nullable|string:200',
            "gerente" => 'nullable|string:200',
            "nombre_centro" => 'nullable|string:200',
            "nombre_supervisor" => 'nullable|string:200',
            "nombre_gerente" => 'nullable|string:200',
        ]);

        DB::beginTransaction();

        try{
            $this->repository->saveUpdate($request->all(), $id);
            
            DB::commit();

            return response()->json([
                "message" => "Actualización exitosa",
            ], 201);

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

            $this->repository->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
