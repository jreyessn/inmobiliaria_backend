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
        'user_id'
    ];

    /**
     * Morbilidades
     */
    public function mortalities()
    {
        return $this->hasMany(VisitsMortality::class);
    }
    
    /**
     * Compromisos agregados
     */
    public function commitments()
    {
        return $this->hasMany(VisitsCommitment::class);
    }


    /**
     * Preguntas contestadas durante la visita
     */
    public function questions()
    {
        return $this->hasMany(VisitsQuestion::class);
    }
    
    /**
     * Granja visitada
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
