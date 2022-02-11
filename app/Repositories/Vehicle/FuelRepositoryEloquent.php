<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\FuelRepository;
use App\Models\Vehicle\Fuel;
use App\Validators\Vehicle\FuelValidator;

/**
 * Class FuelRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class FuelRepositoryEloquent extends BaseRepository implements FuelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Fuel::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Guardar combustible
     */
    public function save(array $data)
    {
        $store = $this->create($data);
        
        return $store;
    }

    /**
     * Actualizar combustible
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
}
