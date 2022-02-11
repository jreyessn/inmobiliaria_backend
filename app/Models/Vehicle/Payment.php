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

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

}
