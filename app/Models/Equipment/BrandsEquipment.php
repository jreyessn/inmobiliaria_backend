<?php

namespace App\Models\Equipment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class BrandsEquipment.
 *
 * @package namespace App\Models\Equipment;
 */
class BrandsEquipment extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "brands_equipments";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name"
    ];

}
