<?php

namespace App\Models\Vehicle;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Vehicle.
 *
 * @package namespace App\Models\Vehicle;
 */
class Vehicle extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "brand",
        "model",
        "license_plate",
        "no_serie",
        "user_id",
        "insurance_policy",
        "km_start",
        "km_limit",
        "comments",
        "maintenance_limit_at",
        "expiration_license_at",
        "expiration_policy_at",
    ];

    protected $casts = [
        "maintenance_limit_at" => "date"
    ];

    protected $appends = [
        "km_traveled",
        "label",
        "maintenance_limit_status",
        "km_limit_status",
    ];

    protected $with = [
        "user"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(ServiceVehicle::class);
    }

    public function last_service()
    {
        return $this->hasOne(ServiceVehicle::class)->orderBy("created_at", "desc");
    }

    public function fuels()
    {
        return $this->hasMany(Fuel::class);
    }

    public function license_plates()
    {
        return $this->hasMany(LicensePlate::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function permissions()
    {
        return $this->hasMany(PermissionsVehicle::class);
    }

    public function getKmTraveledAttribute()
    {
        $total_tracker = DB::table("vehicles_km_tracker")
                        ->select(DB::raw("sum((km_current - km_previous)) as km_traveled"))
                        ->where("vehicle_id", $this->id)
                        ->where("deleted_at", null)
                        ->first();


        return $total_tracker->km_traveled ?? 0;
    }

    public function getLabelAttribute()
    {
        $label = $this->name . " | " . $this->brand . " " . $this->model;

        return $label;
    }

    public function getMaintenanceLimitStatusAttribute()
    {
        if($this->maintenance_limit_at && now()->gt($this->maintenance_limit_at)){
            return 0;
        }
        return 1;
    }

    public function getKmLimitStatusAttribute()
    {
        if($this->km_current > $this->km_limit){
            return 0;
        }
        return 1;
    }

}
