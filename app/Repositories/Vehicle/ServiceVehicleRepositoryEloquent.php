<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\ServiceVehicleRepository;
use App\Models\Vehicle\ServiceVehicle;
use App\Validators\Vehicle\ServiceVehicleValidator;

/**
 * Class ServiceVehicleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class ServiceVehicleRepositoryEloquent extends BaseRepository implements ServiceVehicleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ServiceVehicle::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

        
    /**
     * Guardar permisos de vehiculos
     */
    public function save(array $data)
    {
        $store = $this->create($data);
        
        return $store;
    }

    /**
     * Actualizar permisos de vehiculos
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
