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

    protected $with = [];

    protected $hidden = [
        'inactivated_at',
        'deleted_at',
    ];

    /**
     * Preguntas correspondientes a cada sección
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order', 'asc');
    }
}
