<?php

namespace App\Http\Controllers\Equipments;

use App\Http\Controllers\Controller;
use App\Repositories\Equipment\EquipmentRepositoryEloquent;
use App\Repositories\Images\ImageRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentsController extends Controller
{

    private $EquipmentRepositoryEloquent;
    
    private $ImageRepositoryEloquent;

    function __construct(
        EquipmentRepositoryEloquent $EquipmentRepositoryEloquent,
        ImageRepositoryEloquent $ImageRepositoryEloquent
    )
    {
        $this->EquipmentRepositoryEloquent = $EquipmentRepositoryEloquent;
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

        return $this->EquipmentRepositoryEloquent->paginate($perPage);
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

        foreach ($request->file('images') ?? [] as $key => $val) {
            $validationMessages["images.*.mimes"] = "El archivo °N ".($key + 1)." debe ser un archivo de tipo: jpg, jpeg, png.";
            $validationMessages["images.*.file"]  = "El archivo °N ".($key + 1)." debe ser un archivo.";
        }

        $request->validate([
            "name"                    => "required|string|max:200|unique:equipments,name,NULL,id,deleted_at,NULL",
            "categories_equipment_id" => "required|exists:categories_equipments,id",
            "brands_equipment_id"     => "nullable|exists:brands_equipments,id",
            "no_serie"                => "required|string|max:200",
            "area_id"                 => "nullable|exists:areas,id",
            "between_days_service"    => "required|numeric|min:1",
            "cost"                    => "required|numeric",
            "maintenance_required"    => "nullable|numeric|in:1,0",
            "no_serie_visible"        => "nullable|numeric|in:1,0",
            "parts"                   => "array",
            "images.*"                => "file|mimes:jpg,jpeg,png"
        ], $validationMessages);

        DB::beginTransaction();

        try{
            
            $data = $this->EquipmentRepositoryEloquent->save($request->all());

            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $data, [
                "path" => "equipments"
            ]);

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
        $data = $this->EquipmentRepositoryEloquent->find($id)->load([
            "brand",
            "category",
            "area",
            "parts",
            "images"
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

        $validationMessages = [];

        foreach ($request->file('images') ?? [] as $key => $val) {
            $validationMessages["images.*.mimes"] = "El archivo °N ".($key + 1)." debe ser un archivo de tipo: jpg, jpeg, png.";
            $validationMessages["images.*.file"]  = "El archivo °N ".($key + 1)." debe ser un archivo.";
        }

        $request->validate([
            "name"                    => "required|string|max:200|unique:equipments,name,{$id},id,deleted_at,NULL",
            "categories_equipment_id" => "required|exists:categories_equipments,id",
            "brands_equipment_id"     => "nullable|exists:brands_equipments,id",
            "no_serie"                => "required|string|max:200",
            "area_id"                 => "nullable|exists:areas,id",
            "between_days_service"    => "required|numeric|min:1",
            "cost"                    => "required|numeric",
            "maintenance_required"    => "nullable|numeric|in:1,0",
            "no_serie_visible"        => "nullable|numeric|in:1,0",
            "parts"                   => "array",
            "images.*"                => "file|mimes:jpg,jpeg,png"
        ], $validationMessages);
        
        DB::beginTransaction();

        try{
            $data = $this->EquipmentRepositoryEloquent->saveUpdate($request->all(), $id);
            
            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $data, [
                "path" => "equipments"
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

            $this->EquipmentRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
