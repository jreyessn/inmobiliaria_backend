<?php

namespace App\Models\Equipment;

use App\Models\Services\Service;
use Carbon\Carbon;
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
        "create_services_automatic"
    ];

    protected $appends = [
        "last_service",
        "next_service_at",
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

}
