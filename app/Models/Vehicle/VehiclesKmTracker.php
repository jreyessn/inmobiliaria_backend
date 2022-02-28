<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehiclesKmTracker extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $casts = [
        "km_previous" => "float",
        "km_current"  => "float",
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function getKmTraveledAttribute()
    {
        return $this->km_current - $this->km_previous;
    }
}
