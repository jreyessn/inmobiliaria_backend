<?php

namespace App\Repositories\Tools;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Tools\ToolRepository;
use App\Models\Tools\Tool;
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

        return $store;
    }

    /**
     * Actualizar herramientas
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
