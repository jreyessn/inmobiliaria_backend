<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class PermissionsVehicle.
 *
 * @package namespace App\Models\Vehicle;
 */
class PermissionsVehicle extends Model implements Transformable
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
        "date",
        "expiration_at",
        "last_notification_expired_at",
    ];

    protected $casts = [
        "expiration_at" => "date"
    ];

    protected $appends = [
        "status_expiration"
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    public function getStatusExpirationAttribute()
    {
        if($this->expiration_at && $this->expiration_at->gt(now())){
            return "Vigente";
        }

        return "Vencido";
    }

}
