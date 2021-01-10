<?php

namespace App\Models\Visit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitsMortality extends Model
{
    use HasFactory;

    protected $fillable = [
        'building',
        'mort_acum', 
        'mort_current_week', 
        'pigs_age', 
        'pigs_fever', 
        'activity',
        'cought', 
        'diarrhea', 
        'pigs_treated_day', 
        'visit_id'
    ];

    public $timestamps = false;

}
