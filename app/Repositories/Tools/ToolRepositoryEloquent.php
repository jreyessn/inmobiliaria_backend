<?php

namespace App\Repositories\Tools;

use App\Models\Area\Area;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Tools\ToolRepository;
use App\Models\Tools\Tool;
use App\Models\Tools\ToolsModel;
use App\Models\Tools\ToolsUser;
use App\Models\User;
use App\Validators\Tools\ToolValidator;

/**
 * Class ToolRepositoryEloquent.
 *
 * @package namespace App\Repositories\Tools;
 */
class ToolRepositoryEloquent extends BaseRepository implements ToolRepository
{

    protected $fieldSearchable = [
        "name"      => "like",
        "user.name" => "like"
    ];
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tool::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Guardar herramientas
     */
    public function save(array $data)
    {
        $store = $this->create($data);

        if(array_key_exists("tools_models", $data)){
            $this->saveToolsModels($data["tools_models"], $store->id);
            // $this->saveToolsUsers($data["tools_users"], $store->id);
        }

        return $store;
    }

    /**
     * Guarde varias relaciones con herramientas en tabla polimorfica
     * 
     * @param array $tools_users Herramientas de usuarios
     * @param int $tool_id ID de herramienta 
     */
    public function saveToolsModels(array $tools_models = [], $tool_id){
        
        ToolsModel::where("tool_id", $tool_id)->forceDelete();

        foreach ($tools_models as $tools_model) {
            
            $tools_model["tool_id"]    = $tool_id;
            $tools_model["model_type"] = $this->findEntityModel($tools_model["entity"]);

            ToolsModel::create($tools_model);
        }
    }

    /**
     * Busca la clase modelo con la entidad para relacionar
     */
    private function findEntityModel($entity){
        switch ($entity) {
            case 'area':
                return Area::class;
            break;
            
            default:
                return User::class;
            break;
        }
    }

    /**
     * Guardar las herramientas de usuarios
     * 
     * @param array $tools_users Herramientas de usuarios
     * @param int $tool_id ID de herramienta 
     */
    public function saveToolsUsers(array $tools_users = [], $tool_id){
        
        ToolsUser::where("tool_id", $tool_id)->forceDelete();

        foreach ($tools_users as $tool_user) {
            $tool_user["tool_id"] = $tool_id;
            ToolsUser::create($tool_user);
        }
    }

    /**
     * Actualizar herramientas
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        if(array_key_exists("tools_models", $data)){
            $this->saveToolsModels($data["tools_models"], $store->id);
            // $this->saveToolsUsers($data["tools_users"], $store->id);
        }

        return $store;
    }
    
}
