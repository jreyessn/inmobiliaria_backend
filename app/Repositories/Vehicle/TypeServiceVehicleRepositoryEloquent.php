<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\TypeServiceVehicleRepository;
use App\Models\Vehicle\TypeServiceVehicle;
use App\Validators\Vehicle\TypeServiceVehicleValidator;

/**
 * Class TypeServiceVehicleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class TypeServiceVehicleRepositoryEloquent extends BaseRepository implements TypeServiceVehicleRepository
{

    protected $fieldSearchable = [
        "name" => "like",
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TypeServiceVehicle::class;
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
