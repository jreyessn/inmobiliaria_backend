<?php

namespace App\Http\Requests\Contacts;

use App\Models\Contact\Contact;
use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
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
        $id = $this->route('contact') ?? $this->user()->contact->id ?? null;
        
        $userId = Contact::find($id)->user_id ?? null;

        return [
            'name' => 'required|string|max:100',
            'email' => "required|email|nullable|email|unique:users,email,{$userId},id,deleted_at,NULL|unique:contacts,email,{$id},id,deleted_at,NULL",
            'phone' => 'nullable',
            'customer_id' => 'nullable|exists:customers,id',
            'address' => 'nullable',
            'language' => 'nullable',
            'note' => 'nullable',
            'avatar' => 'nullable',
            'password' => 'nullable|string|min:6|required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'nullable|string|min:6',
        ];
        
    }
}
