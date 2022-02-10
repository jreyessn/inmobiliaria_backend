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
    
}
