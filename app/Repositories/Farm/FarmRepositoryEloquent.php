<?php

namespace App\Repositories\Farm;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Farm\FarmRepository;
use App\Models\Farm\Farm;
use App\Validators\Farm\FarmValidator;

/**
 * Class FarmRepositoryEloquent.
 *
 * @package namespace App\Repositories\Farm;
 */
class FarmRepositoryEloquent extends BaseRepository implements FarmRepository
{

    protected $fieldSearchable = [
        "name" => "like"
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
     * Guardar granjas
     */
    public function save(array $data)
    {
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar granjas
     */
    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
