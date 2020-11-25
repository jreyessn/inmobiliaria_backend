<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantStoreRequest extends FormRequest
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
            'reason' => 'required|max:200',
            'type_provider' => 'required|string|max:100',
            "tradename" => 'required|max:255',
            "business_name" => 'required|max:200',
            "name_contact" => 'required|max:200',
            "phone_provider" => 'required|max:200',
            "email_provider" => 'required|email|max:100',
            "fullname_applicant" => 'required|max:155',
            "email_applicant" => 'required|email|unique:applicant_providers,email_applicant,NULL,id,deleted_at,NULL|max:155',
            "microbusiness" => 'required|max:200',
            "authorization_file" => 'required|file|mimes:pdf',
        ];
    }

    public function attributes(){
        return [
            'reason' => 'Motivo',
            'type_provider' => 'Tipo de Proveedor',
            "tradename" => 'Nombre Comercial',
            "business_name" => 'Razón Social',
            "name_contact" => 'Nombre de Contacto',
            "phone_provider" => 'Teléfono de Proveedor',
            "email_provider" => 'Correo de Proveedor',
            "fullname_applicant" => 'Nombre de Solicitante',
            "email_applicant" => 'Correo de Solicitante',
            "microbusiness" => 'Micronegocio al que Pertenece',
            "authorization_file" => 'Autorización',
        ];
    }
}
