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
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc'
        ]);

        $perPage = $request->get('perPage', config('repository.pagination.limit'));

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
            'name'         => 'required|string|max:100',
            'direction'    => 'nullable|string|max:200',
            'farm_manager' => 'required|string|max:100',
            'sharecropper' => 'required|string|max:100',
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
            'name'         => 'required|string|max:100',
            'farm_manager' => 'required|string|max:100',
            'sharecropper' => 'required|string|max:100',
            'direction'    => 'nullable|string|max:200',
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
