<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'orden'
    ];

    public $timestamps = false;

    public function childs(){
        return $this->hasMany(TreasuryGroup::class);
    }
}
