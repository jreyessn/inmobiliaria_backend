<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypesServicesRequest extends FormRequest
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
        $id = $this->route("types_service");

        return [
            "name"          => "required|string|max:200|unique:type_services,name,{$id},id,deleted_at,NULL",
            "description"   => "string|nullable",
            "spare_parts.*" => "exists:spare_parts,id",
        ];
    }

    public function attributes()
    {
        $validationMessages = [];

        foreach ($this->get('spare_parts') ?? [] as $key => $val) {
            $validationMessages["spare_parts.{$key}"] = "Refacción N° ".($key + 1);
        }

        return $validationMessages;
    }
}
