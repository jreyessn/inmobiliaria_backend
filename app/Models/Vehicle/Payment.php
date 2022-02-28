<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Payment.
 *
 * @package namespace App\Models\Vehicle;
 */
class Payment extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "vehicle_id",
        "concept",
        "km_current",
        "date",
        "amount",
        "note",
    ];

    protected $casts = [
        "km_current"  => "float",
        "amount"      => "float",
    ];

    protected $appends = [
        "km_last_payment",
        "km_traveled", // km_current - km_last_service
        "is_last_payment"
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
    
    public function km_tracker()
    {
        return $this->morphOne(VehiclesKmTracker::class, "model");
    }

    public function getKmLastPaymentAttribute()
    {

        $prev = Payment::where('id', '<', $this->id)
                        ->where("vehicle_id", $this->vehicle_id) 
                        ->orderBy('id', 'desc')->first();

        if($prev){
            return $prev->km_current;
        }

        return $this->km_tracker()->first()->km_previous;
    }

    public function getIsLastPaymentAttribute()
    {
        $prev = Payment::where('id', '>', $this->id)
                        ->where("vehicle_id", $this->vehicle_id)
                        ->orderBy('id', 'desc')->first();
        if($prev){
            return false;
        }
        return true;
    }

    public function getKmTraveledAttribute()
    {
        return $this->km_current - $this->km_last_payment;
    }

}
