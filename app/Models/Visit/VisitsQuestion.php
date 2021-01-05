<?php

namespace App\Models\Visit;

use App\Models\Question\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitsQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'question_id',
        'score'
    ];

    protected $hidden = [
        'visit_id',
        'question_id'
    ];

    protected $with = [
        'question'
    ];

    public $timestamps = false;

    /**
     * Información de la pregunta relacionada
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
