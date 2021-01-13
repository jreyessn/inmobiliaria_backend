<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'max_score',
        'score_fractional',
        'order',
        'inactivated_at',
        'sections_question_id',
    ];

    protected $hidden = [
        'inactivated_at',
        'sections_question_id',
        'deleted_at',
    ];

    protected $appends = [
        'score_options'
    ];

    /**
     * Cada pregunta tiene un puntaje. Esta propiedad obtiene las respuestas posibles 
     * en base a las fracciones de puntos
     */
    public function getScoreOptionsAttribute()
    {
        return range(0, $this->max_score, $this->score_fractional);
    }

    public function section()
    {
        return $this->belongsTo(SectionsQuestion::class, 'sections_question_id');
    }
}
