<?php

namespace App\Http\Controllers\Furniture;

use App\Criteria\Furniture\FurnitureCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Furniture\FurnitureStoreRequest;
use App\Repositories\Furniture\FurnitureRepositoryEloquent;
use App\Repositories\Images\ImageRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FurnitureController extends Controller
{
    private $FurnitureRepositoryEloquent;

    private $ImageRepositoryEloquent;

    function __construct(
        FurnitureRepositoryEloquent $FurnitureRepositoryEloquent,
        ImageRepositoryEloquent     $ImageRepositoryEloquent
    )
    {
        $this->FurnitureRepositoryEloquent = $FurnitureRepositoryEloquent;
        $this->ImageRepositoryEloquent     = $ImageRepositoryEloquent;
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

        $this->FurnitureRepositoryEloquent->pushCriteria(FurnitureCriteria::class);

        return $this->FurnitureRepositoryEloquent->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FurnitureStoreRequest $request)
    {

        DB::beginTransaction();

        try{            
            $store = $this->FurnitureRepositoryEloquent->save($request->all());

            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $store, [
                "path" => "furnitures"
            ]);

            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $store
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
        $data = $this->FurnitureRepositoryEloquent->find($id)->load(["images"]);

        return ["data" => $data];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FurnitureStoreRequest $request, $id)
    {
        DB::beginTransaction();

        try{
            $store = $this->FurnitureRepositoryEloquent->saveUpdate($request->all(), $id);
            
            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $store, [
                "path" => "furnitures"
            ]);

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

            $this->FurnitureRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
