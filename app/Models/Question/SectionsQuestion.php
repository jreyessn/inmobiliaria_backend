<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionsQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'order',
        'inactivated_at'
    ];
}
