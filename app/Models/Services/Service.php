<?php

namespace App\Models\Services;

use App\Models\Equipment\Equipment;
use App\Models\Equipment\EquipmentPart;
use App\Models\Farm\Farm;
use App\Models\Images\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Service.
 *
 * @package namespace App\Models\Services;
 */
class Service extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "categories_service_id",
        "type_service_id",
        "equipments_part_id",
        "user_assigned_id",
        "farm_id",
        "event_date",
        "note",
        "received_by",
        "observation",
        "completed_at",
        "status",
    ];

    protected $with = [
        "signature",
        "evidences",
        "categories_service",
        "type_service",
        "farm",
        "user_assigned",
    ];

    protected $appends = [
        "status_text",
    ];

    protected $casts = [
        "event_date"   => "datetime",
        "completed_at" => "datetime",
    ];

    public function categories_service()
    {
        return $this->belongsTo(CategoriesService::class);
    }
    
    public function type_service()
    {
        return $this->belongsTo(TypesService::class);
    }

    public function equipments_part()
    {
        return $this->belongsTo(EquipmentPart::class, "equipments_part_id");
    }

    public function equipment()
    {
        return $this->hasOneThrough(Equipment::class, EquipmentPart::class, "id", "id", "equipments_part_id", "equipment_id");
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function user_assigned()
    {
        return $this->belongsTo(User::class, "user_assigned_id");
    }

    public function signature()
    {
        return $this->morphOne(Image::class, "model")->where("type", "Signature");
    }

    public function evidences()
    {
        return $this->morphMany(Image::class, "model")->where("type", "Evidences");
    }

    public function getStatusAttribute($status)
    {
        $this->event_date = $this->event_date->setHour(23);
        $this->event_date = $this->event_date->setMinute(59);
        $this->event_date = $this->event_date->setSecond(59);

        if($status == 0 && $this->event_date && now()->gt("{$this->event_date}")){
            return 2;
        }
        return $status;
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case '1':
                return "Completado";
            break;
            
            case '2':
                return "Vencido";
            break;
            
            default:
                return "Pendiente";
            break;
        }
    }

}
