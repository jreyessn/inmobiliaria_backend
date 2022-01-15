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

    protected $with = ["signature"];

    protected $appends = [
        "status_text",
    ];

    protected $casts = [
        "event_date"   => "datetime",
        "completed_at" => "datetime",
    ];

    public function category()
    {
        return $this->belongsTo(CategoriesService::class);
    }
    
    public function type_service()
    {
        return $this->belongsTo(TypesService::class);
    }

    public function equipment_part()
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

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case '1':
                return "Completado";
            break;
            
            case '2':
                return "Cancelado";
            break;
            
            default:
                return "Pendiente";
            break;
        }
    }

}
