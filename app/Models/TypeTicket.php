<?php

namespace App\Models;

use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
