<?php

namespace App\Models\Equipment;

use App\Models\Area\Area;
use App\Models\Images\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Equipment.
 *
 * @package namespace App\Models\Equipment;
 */
class Equipment extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "equipments";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "categories_equipment_id",
        "brands_equipment_id",
        "no_serie",
        "area_id",
        "obtained_at",
        "between_days_service",
        "cost",
        "maintenance_required",
        "no_serie_visible",
    ];

    protected $appends = [
        "last_service",
        "last_service_at",
        "next_service_at",
    ];

    public function parts()
    {
        return $this->hasMany(EquipmentPart::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoriesEquipment::class);
    }

    public function brand()
    {
        return $this->belongsTo(BrandsEquipment::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, "model");
    }

    public function getLastServiceAttribute()
    {
        return null;
    }

    public function getLastServiceAtAttribute()
    {
        return null;
    }

    public function getNextServiceAtAttribute()
    {
        return null;
    }

}
