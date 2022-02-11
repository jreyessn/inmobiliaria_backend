<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\Vehicle\VehiclesKmTracker;

class KmTrackerObserver
{
    
    public function created($store)
    {
        $vehicle = $store->vehicle;
        
        VehiclesKmTracker::create(
            [
                "vehicle_id"  => $vehicle->id,
                "km_previous" => $vehicle->km_traveled,
                "km_current"  => $store->km_current,
                "model_type"  => get_class($store),
                "model_id"    => $store->id,
            ]
        );
    }

}
