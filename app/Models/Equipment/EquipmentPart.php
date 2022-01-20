<?php

namespace App\Models\Equipment;

use App\Models\Services\Service;
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
        "equipment_id",
        "between_days_service",
        "last_service_at",
    ];

    protected $appends = [
        "total_services",
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class, "equipments_part_id");
    }

    public function getTotalServicesAttribute()
    {
        return $this->services()->where("status", 1)->count();
    }

}
