<?php

namespace App\Repositories\Farm;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Farm\FarmRepository;
use App\Models\Farm\Farm;
use App\Models\Person\Person;
use App\Validators\Farm\FarmValidator;

/**
 * Class FarmRepositoryEloquent.
 *
 * @package namespace App\Repositories\Farm;
 */
class FarmRepositoryEloquent extends BaseRepository implements FarmRepository
{
    protected $fieldSearchable = [
        "centro" => 'like',
        "supervisor" => 'like',
        "gerente" => 'like',
        "nombre_centro" => 'like',
        "nombre_supervisor" => 'like',
        "nombre_gerente" => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Farm::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar los datos de la granja
     * 
     * @param array $data
     * @return App\Models\Farm\Farm
     */
    public function save(array $data): Farm
    {
        $farm = $this->create($data);

        return $farm;
    }

    /**
     * Actualizar los datos de la granja
     * 
     * @param array $data
     * @return App\Models\Farm\Farm
     */
    public function saveUpdate(array $data, int $id): Farm
    {
        $farm = $this->find($id);
        $farm->fill($data);
        $farm->save();
        
        return $farm;
    }
    
}
