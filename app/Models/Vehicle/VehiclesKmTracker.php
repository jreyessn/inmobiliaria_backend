<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclesKmTracker extends Model
{
    use HasFactory;

    protected $table = "vehicles_km_tracker";

    protected $fillable = [
        "vehicle_id",
        "km_previous",
        "km_current",
        "model_type",
        "model_id",
    ];

    protected $appends = [
        "km_traveled"
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function getKmTraveledAttribute()
    {
        return $this->km_current - $this->km_last_service;
    }
}
