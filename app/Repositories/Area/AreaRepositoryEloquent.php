<?php

namespace App\Repositories\Area;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Area\AreaRepository;
use App\Models\Area\Area;
use App\Validators\Area\AreaValidator;

/**
 * Class AreaRepositoryEloquent.
 *
 * @package namespace App\Repositories\Area;
 */
class AreaRepositoryEloquent extends BaseRepository implements AreaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Area::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Guardar areas
     */
    public function save(array $data)
    {
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar areas
     */
    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }

}
