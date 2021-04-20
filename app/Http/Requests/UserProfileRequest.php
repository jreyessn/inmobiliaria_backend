<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
        $id = $this->user()->id;

        return [
            'name'                  =>  'required|string',
            'slack_player'          =>  'nullable|string',
            'email'                 =>  "nullable|email|unique:users,email,{$id},id,deleted_at,NULL",
            'password'              =>  [
                'nullable',
                'string',
                'min:6',
            ],
        ];
    }
}
