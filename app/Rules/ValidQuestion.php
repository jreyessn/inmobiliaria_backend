<?php

namespace App\Rules;

use App\Models\Question\Question;
use Illuminate\Contracts\Validation\Rule;

class ValidQuestion implements Rule
{

    /**
     * En caso de que la pregunta no exista, se arroja en esta propiedad el mensaje.
     * 
     * @var $questionNoExist
     */
    private $questionNoExist = '';

    /**
     * Descripción de la pregunta
     * 
     * @var $question
     */
    private $question = '';

    /**
     * Máximo de puntaje que se puede colocar. Se envía como mensaje de la validación
     * 
     * @var $max_score
     */
    private $max_score = 1;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $key => $item) {
            
            $question = Question::find($item['question_id']);

            if(is_null($question)){
                
                $nro = $key + 1;
                $this->questionNoExist = "La pregunta N° {$nro} no se ha logrado encontrar";

                return false;
                break;
            }


            if($item['score'] > $question->max_score){

                $this->question = $question->description;
                $this->max_score = $question->max_score;

                return false;
                break;
            }

        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->questionNoExist != '')
          return $this->questionNoExist;

        return "El puntaje realizado a la pregunta '{$this->question}' no se encuentra dentro del rango. Debe ser menor a '{$this->max_score}'.";
    }
}
