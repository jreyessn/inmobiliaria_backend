<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTypeProvider extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type_provider_id'
    ];
}
