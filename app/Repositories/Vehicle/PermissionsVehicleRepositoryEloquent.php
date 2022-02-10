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
    
}
