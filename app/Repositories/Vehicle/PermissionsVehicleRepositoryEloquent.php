<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\PermissionsVehicleRepository;
use App\Models\Vehicle\PermissionsVehicle;
use App\Validators\Vehicle\PermissionsVehicleValidator;

/**
 * Class PermissionsVehicleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class PermissionsVehicleRepositoryEloquent extends BaseRepository implements PermissionsVehicleRepository
{

    protected $fieldSearchable = [
        "vehicle.name" => "like",
        "concept"      => "like",
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PermissionsVehicle::class;
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
