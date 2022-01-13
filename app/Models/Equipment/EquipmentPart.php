<?php

namespace App\Models\Equipment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class EquipmentPart.
 *
 * @package namespace App\Models\Equipment;
 */
class EquipmentPart extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "equipments_parts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "equipment_id"
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

}
