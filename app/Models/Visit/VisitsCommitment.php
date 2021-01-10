<?php

namespace App\Models\Visit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitsCommitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'title', 
        'description', 
        'visit_id', 
        'question_id', 
    ];

    public $timestamps = false;
}
