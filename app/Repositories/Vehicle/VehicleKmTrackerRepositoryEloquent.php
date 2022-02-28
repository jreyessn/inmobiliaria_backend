<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\VehicleKmTrackerRepository;
use App\Models\Vehicle\VehiclesKmTracker;
use App\Validators\Vehicle\VehicleKmTrackerValidator;

/**
 * Class VehicleKmTrackerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class VehicleKmTrackerRepositoryEloquent extends BaseRepository implements VehicleKmTrackerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return VehiclesKmTracker::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Calcula el kilometraje recorrido del ultimo tramo según el modelo
     * 
     * @param $model Modelo a consultar
     * @param $vehicle Modelo vehiculo
     */
    public function kmLastRoadTraveled($model, $vehicle){

        if(is_null($model) || is_null($vehicle))
            return 0;

        $currentTracker = $this->where("model_type", get_class($model))
                         ->where("vehicle_id", $vehicle->id)
                         ->where("model_id", $model->id)
                         ->first();
        
        $trackersPrev = $this->where("vehicle_id", $vehicle->id)
                         ->where("id", "<", $currentTracker->id)
                         ->orderBy("id", "desc")
                         ->first();


        return $vehicle->km_traveled - ($trackersPrev->km_current ?? 0);
    }
    
    /**
     * Calcula el kilometraje recorrido del siguiente registro segun el modelo
     * 
     * @param $model Modelo a consultar
     * @param $vehicle Modelo vehiculo
     */
    public function kmNextTraveled($model, $vehicle){

        if(is_null($model) || is_null($vehicle))
            return 0;

        $currentTracker = $this->where("model_type", get_class($model))
                         ->where("vehicle_id", $vehicle->id)
                         ->where("model_id", $model->id)
                         ->first();

        $trackersNext = $this->where("vehicle_id", $vehicle->id)
                         ->where("id", ">", $currentTracker->id)
                         ->orderBy("id", "asc")
                         ->first();


        return $trackersNext? $trackersNext->km_current : 0;
    }

}
