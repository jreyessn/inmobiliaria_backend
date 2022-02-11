<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Fuel.
 *
 * @package namespace App\Models\Vehicle;
 */
class Fuel extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "vehicle_id",
        "lts_current",
        "lts_loaded",
        "amount",
        "km_current",
        "efficiency",
    ];

    protected $casts = [
        "km_current"  => "float",
        "lts_current" => "float",
        "lts_loaded"  => "float",
        "amount"      => "float",
    ];

    protected $appends = [
        "km_last_fuel",
        "km_traveled" // km_current - km_last_service
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    public function km_tracker()
    {
        return $this->morphOne(VehiclesKmTracker::class, "model");
    }

    public function getKmLastFuelAttribute()
    {

        $prev = Fuel::where('id', '<', $this->id)->orderBy('id', 'desc')->first();

        if($prev){
            return $prev->km_current;
        }

        return $this->km_tracker()->first()->km_previous;
    }
    
    public function getKmTraveledAttribute()
    {
        return $this->km_current - $this->km_last_fuel;
    }

    public function getEfficiencyAttribute($value)
    {
        return $this->km_traveled / $this->lts_loaded;
    }
}
