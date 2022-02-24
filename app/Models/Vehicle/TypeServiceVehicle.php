<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TypeServiceVehicle.
 *
 * @package namespace App\Models\Vehicle;
 */
class TypeServiceVehicle extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "description",
    ];

    public function services()
    {
        return $this->hasMany(ServiceVehicle::class, "type_service_vehicle_id");
    }

}
