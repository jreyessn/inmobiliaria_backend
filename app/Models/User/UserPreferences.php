<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreferences extends Model
{
    use HasFactory;

    protected $table = "users_preferences";

    public $timestamps = false;

    protected $primaryKey = "key";

    public $incrementing = false;

    protected $fillable = [
        "key",
        "value",
        "user_id",
    ];
}
