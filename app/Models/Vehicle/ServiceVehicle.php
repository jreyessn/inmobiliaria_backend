<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ServiceVehicle.
 *
 * @package namespace App\Models\Vehicle;
 */
class ServiceVehicle extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "services_vehicles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "vehicle_id",
        "km_current",
        "type_service_vehicle_id",
        "event_date",
        "amount",
        "status",
        "note",
        "completed_at",
        "observation",
    ];

    protected $appends = [
        "status_text",
        "km_last_service",
        "km_traveled" // km_current - km_last_service
    ];

    protected $casts = [
        "event_date"   => "datetime",
        "completed_at" => "datetime",
        "km_current"   => "float",
        "amount"        => "float"
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    public function type_service_vehicle(){
        return $this->belongsTo(TypeServiceVehicle::class);
    }
    
    public function getKmLastServiceAttribute()
    {
        return 0;
    }
    
    public function getKmTraveledAttribute()
    {
        return 0;
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
