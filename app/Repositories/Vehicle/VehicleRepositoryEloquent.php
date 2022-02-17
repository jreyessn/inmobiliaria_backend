<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\VehicleRepository;
use App\Models\Vehicle\Vehicle;
use App\Validators\Vehicle\VehicleValidator;

/**
 * Class VehicleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class VehicleRepositoryEloquent extends BaseRepository implements VehicleRepository
{
    protected $fieldSearchable = [
        "name"             => "like",
        "user.name"        => "like",
        "name"             => "like",
        "brand"            => "like",
        "model"            => "like",
        "license_plate"    => "like",
        "no_serie"         => "like",
        "insurance_policy" => "like",
    ];
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Vehicle::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Guardar vehiculos
     */
    public function save(array $data)
    {
        $store = $this->create($data);
        
        return $store;
    }

    /**
     * Actualizar vehiculos
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
}
