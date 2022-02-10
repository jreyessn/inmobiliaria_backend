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

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

}
