<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = "configuration";

    protected $primaryKey = 'key';

    public $incrementing = false;
    
    protected $fillable = [
        'key',
        'value'
    ];

    public $timestamps = false;
}
