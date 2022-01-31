<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrioritiesService extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "color",
        "orden"
    ];

    public $timestamps = false;
}
