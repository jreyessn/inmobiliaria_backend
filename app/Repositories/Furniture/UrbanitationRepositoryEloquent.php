<?php

namespace App\Repositories\Furniture;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Furniture\UrbanitationRepository;
use App\Models\Furniture\Urbanitation;
use App\Validators\Furniture\UrbanitationValidator;

/**
 * Class UrbanitationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Furniture;
 */
class UrbanitationRepositoryEloquent extends BaseRepository implements UrbanitationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Urbanitation::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
 
         
    /**
     * Guardar urbanizaciones
     */
    public function save(array $data)
    {

        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar urbanizaciones
     */
    public function saveUpdate(array $data, int $id)
    {
        
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
