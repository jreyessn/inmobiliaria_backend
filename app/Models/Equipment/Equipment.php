<?php

namespace App\Models\Equipment;

use App\Models\Area\Area;
use App\Models\Images\Image;
use App\Models\Services\Service;
use Carbon\Carbon;
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
        "brand",
        "no_serie",
        "area_id",
        "obtained_at",
        "between_days_service",
        "cost",
        "maintenance_required",
        "last_service_at",
        "no_serie_visible",
        "create_services_automatic",
        "days_before_create",
    ];

    protected $appends = [
        "last_service",
        "next_service_at",
        "total_services",
    ];

    protected $with = [
        "categories_equipment",
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
        return $this->hasMany(Service::class);
    }

    public function getLastServiceAttribute()
    {
        return $this->services()->where("status", 1)->latest('completed_at')->first();
    }
    
    public function getLastServiceAtAttribute($value)
    {
        $service = $this->services()->where("status", 1)->latest('completed_at')->first();

        if($service && $service->completed_at)
            return $service->completed_at;
        return $value;
    }
    
    public function getNextServiceAtAttribute()
    {
        $lastAt = $this->last_service->completed_at ?? $this->last_service_at ?? $this->created_at ?? null;
        
        if(is_null($lastAt)){
            return null;
        }
        
        return Carbon::parse($lastAt)->add($this->between_days_service, "day");
    }

    public function getTotalServicesAttribute()
    {
        return $this->services()->where("status", 1)->count();
    }

}