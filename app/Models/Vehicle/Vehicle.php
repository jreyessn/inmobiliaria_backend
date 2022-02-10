<?php

namespace App\Models\Vehicle;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Vehicle.
 *
 * @package namespace App\Models\Vehicle;
 */
class Vehicle extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "brand",
        "model",
        "license_plate",
        "no_serie",
        "user_id",
        "insurance_policy",
        "km_start",
        "km_limit",
        "comments",
        "maintenance_limit_at",
        "expiration_license_at",
        "expiration_policy_at",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
