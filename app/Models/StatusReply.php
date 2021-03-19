<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusReply extends Model
{
    use HasFactory;

    protected $table = "replies_status";

    protected $fillable = [
        "description",
        "background_color",
        "border_color",
        "color",
        "show_in_list",
    ];
}
