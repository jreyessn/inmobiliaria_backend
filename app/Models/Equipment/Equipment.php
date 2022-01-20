<?php

namespace App\Models\Equipment;

use App\Models\Area\Area;
use App\Models\Images\Image;
use App\Models\Services\Service;
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
        "last_service_at",
        "no_serie_visible",
    ];

    protected $appends = [
        "last_service",
        "next_service_at",
        "total_services",
    ];

    protected $with = [
        "categories_equipment",
        "brands_equipment",
        "area",
    ];

    public function parts()
    {
        return $this->hasMany(EquipmentPart::class);
    }

    public function categories_equipment()
    {
        return $this->belongsTo(CategoriesEquipment::class);
    }

    public function brands_equipment()
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

    public function services()
    {
        return $this->hasManyThrough(Service::class, EquipmentPart::class, "equipment_id", "equipments_part_id", "id", "id");
    }

    public function getLastServiceAttribute()
    {
        return $this->services()->where("status", 1)->latest('completed_at')->first();
    }
    
    public function getNextServiceAtAttribute()
    {
        $lastAt = $this->last_service->completed_at ?? $this->created_at ?? null;
        
        if(is_null($lastAt)){
            return null;
        }
        
        return $lastAt->add($this->between_days_service, "day");
    }

    public function getTotalServicesAttribute()
    {
        return $this->services()->where("status", 1)->count();
    }

}
