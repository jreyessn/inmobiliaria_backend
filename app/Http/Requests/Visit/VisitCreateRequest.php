<?php

namespace App\Http\Requests\Visit;

use App\Rules\ValidQuestion;
use Illuminate\Foundation\Http\FormRequest;

class VisitCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cost_center'  => 'required|string|max:100',
            'comment'      => 'nullable|string',
            'farm_id'      => 'required|exists:farms,id',
            'questions'    => ['required', 'array', new ValidQuestion],
        ];
    }

    public function attributes()
    {
        return [
            'cost_center' => 'Centro de Costo',
            'farm_id' => 'Granja',
            'questions' => 'Preguntas'
        ];
    }
}
