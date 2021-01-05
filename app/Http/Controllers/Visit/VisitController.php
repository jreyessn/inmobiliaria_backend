<?php

namespace App\Http\Controllers\Visit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Visit\VisitCreateRequest;
use App\Repositories\Visit\VisitRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{

    private $repository;

    public function __construct(
        VisitRepositoryEloquent $repository
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
    public function store(VisitCreateRequest $request)
    {

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
            'data' => $this->repository->with(['questions_answers', 'farm'])->find($id)
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VisitCreateRequest $request, $id)
    {
        DB::beginTransaction();

        try{
            $this->repository->saveUpdate($request->all(), $id);
            
            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
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
