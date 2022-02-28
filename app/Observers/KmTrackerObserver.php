<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\Vehicle\VehiclesKmTracker;

class KmTrackerObserver
{
    
    public function created($store)
    {
        $vehicle = $store->vehicle;
        $recordsPrevious = VehiclesKmTracker::where("vehicle_id", $vehicle->id)->count();

        VehiclesKmTracker::create(
            [
                "vehicle_id"  => $vehicle->id,
                "km_previous" => $recordsPrevious == 0? ($vehicle->km_start ?? 0) : $vehicle->km_traveled,
                "km_current"  => $store->km_current,
                "model_type"  => get_class($store),
                "model_id"    => $store->id,
            ]
        );
    }

    /**
     * Actualiza el kilometraje actual y el siguiente del historial para mantener una consistencia
     * en los kilometrajes recorridos registrados
     */
    public function updated($store)
    {
        $vehicle = $store->vehicle;
        $kmTrackerCurrent = VehiclesKmTracker::where([
            "vehicle_id" => $vehicle->id,
            "model_type" => get_class($store),
            "model_id"   => $store->id,
        ])->first();

        if($kmTrackerCurrent){
            $kmTrackerCurrent->km_current = $store->km_current;
            $kmTrackerCurrent->save();

            $kmTrackerNext = VehiclesKmTracker::where("id", ">", $kmTrackerCurrent->id)->first();

            // Se actualiza el siguiente registro de kilometraje registrado
            if($kmTrackerNext){
                $kmTrackerNext->km_previous = $store->km_current;
                $kmTrackerNext->save();
            }
        }
        else{
            $this->created($store);
        }

    }

    /**
     * Al eliminar el registro
     */
    public function deleted($store)
    {
        $vehicle = $store->vehicle;
        $kmTrackerCurrent = VehiclesKmTracker::where([
            "vehicle_id" => $vehicle->id,
            "model_type" => get_class($store),
            "model_id"   => $store->id,
        ])->first();

        if($kmTrackerCurrent){
            $kmTrackerCurrent->delete();
        }

    }

}
