<?php

namespace App\Models\Visit;

use App\Models\Farm\Farm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cost_center',
        'result',
        'comment',
        'farm_id',
        'user_id',
    ];

    protected $hidden = [
        'farm_id',
        'user_id'
    ];

    /**
     * Preguntas contestadas durante la visita
     */
    public function questions_answers()
    {
        return $this->hasMany(VisitsQuestion::class);
    }
    
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
