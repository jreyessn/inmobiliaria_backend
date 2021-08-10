<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{

    protected $table = "audit";

    use HasFactory;

    protected $fillable = [
        "action",
        "query",
        "user_id",
        "model_type",
        "model_id",
    ];

    protected $hidden = [
        "query"
    ];

}
