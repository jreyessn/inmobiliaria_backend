<?php

namespace App\Models\Tools;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolsUser extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        "quantity",
        "tool_id",
        "user_id"
    ];

    protected $with = [
        "user"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

}
