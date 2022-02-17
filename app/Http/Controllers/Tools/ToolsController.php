<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Tools\ToolRepositoryEloquent;
use App\Repositories\Tools\ToolsModelRepositoryEloquent;
use Illuminate\Support\Facades\DB;

class ToolsController extends Controller
{

    private $ToolRepositoryEloquent;

    private $ToolsModelRepositoryEloquent;

    function __construct(
        ToolRepositoryEloquent $ToolRepositoryEloquent,
        ToolsModelRepositoryEloquent $ToolsModelRepositoryEloquent
    )
    {
        $this->ToolRepositoryEloquent      = $ToolRepositoryEloquent;
        $this->ToolsModelRepositoryEloquent = $ToolsModelRepositoryEloquent;
    }

    /**
     * Muestra una paginación a partir de las herramientas de usuarios
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

        return $this->ToolsModelRepositoryEloquent->with(["relation", "tool"])->paginate($perPage);
    }

    /**
     * Guarda una nueva herramienta con sus usuarios
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationMessages = [];

        foreach ($request->get('tools_users') ?? [] as $key => $val) {
            $num = $key + 1;
            $validationMessages["tools_models." . $key . ".quantity.required"] = "El campo cantidad en la fila N° {$num} es obligatorio";
            $validationMessages["tools_models." . $key . ".quantity.numeric"] = "El campo cantidad en la fila N° {$num} debe ser númerico";
            $validationMessages["tools_models." . $key . ".quantity.min"]     = "El campo cantidad en la fila N° {$num} debe ser mínimo 0";
        }

        $request->validate([
            "name"                   => "required|string|max:200|unique:tools,name,NULL,id,deleted_at,NULL",
            "tools_models.*.quantity" => "required|numeric|min:0",
        ], $validationMessages);
        
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
     * Muestra una herramienta y sus usuarios
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->ToolRepositoryEloquent->find($id)->load("tools_models.relation");

        return ["data" => $data];
    }

    /**
     * Se actualiza la herramienta y los usuarios
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validationMessages = [];

        foreach ($request->get('tools_models') ?? [] as $key => $val) {
            $num = $key + 1;
            $validationMessages["tools_models." . $key . ".quantity.required"] = "El campo cantidad en la fila N° {$num} es obligatorio";
            $validationMessages["tools_models." . $key . ".quantity.numeric"] = "El campo cantidad en la fila N° {$num} debe ser númerico";
            $validationMessages["tools_models." . $key . ".quantity.min"]     = "El campo cantidad en la fila N° {$num} debe ser mínimo 0";
            // $validationMessages["tools_users." . $key . ".user_id.exists"]   = "El usuario seleccionado en la fila N° {$num} no existe";
        }

        $request->validate([
            "name"     => "required|string|max:200|unique:tools,name,{$id},id,deleted_at,NULL",
            "tools_models.*.quantity" => "required|numeric|min:0",
            // "tools_users.*.user_id"  => "nullable|exists:users,id"
        ], $validationMessages);
        
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
     * Elimina la herramienta del usuario. Si no hay usuarios, entonces se elimina la herramienta
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $toolUser = $this->ToolsModelRepositoryEloquent->find($id);
            $tool_id  = $toolUser->tool_id;
            $toolUser->delete();

            $tools_users = $this->ToolRepositoryEloquent->find($tool_id)->tools_users ?? collect([]);

            if($tools_users->count() == 0){
                $this->ToolRepositoryEloquent->delete($tool_id);
            }

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
