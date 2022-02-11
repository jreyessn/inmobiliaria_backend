<?php

namespace App\Models\Vehicle;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class LicensePlate.
 *
 * @package namespace App\Models\Vehicle;
 */
class LicensePlate extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "vehicle_id",
        "user_id",
        "expiration_at",
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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getStatusExpirationAttribute()
    {
        if($this->expiration_at && $this->expiration_at->gt(now())){
            return "Vigente";
        }

        return "Vencido";
    }

}
