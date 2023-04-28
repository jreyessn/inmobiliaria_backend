<?php

namespace App\Http\Requests\Furniture;

use Illuminate\Foundation\Http\FormRequest;

class FurnitureStoreRequest extends FormRequest
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
            "customer_name"           => "required_unless:customer_dni,null",
            "customer_dni"            => "nullable",

            "name"                    => "required|string|max:200",
            "description"             => "nullable|string|max:200",
            
            "bathrooms"               => "required|numeric|min:0",
            "bedrooms"                => "required|numeric|min:0",
            "covered_garages"         => "required|numeric|min:0",
            "uncovered_garages"       => "required|numeric|min:0",

            // "measure_unit_id"         => "required|exists:measure_units,id",
            // "area"                    => "nullable|string|max:200",

            "unit_price"              => "required|numeric|min:0",
            "initial_price"           => "required|numeric|min:0|lt:unit_price",

            "type_furniture_id"       => "required|exists:type_furnitures,id",
            "city_id"                 => "nullable|exists:cities,id",
            "postal_code"             => "nullable|numeric",
            "region"                  => "nullable|string|max:200",
            "address"                 => "nullable|string|max:200",
            "street_number"           => "nullable|string|max:200",
            "aditional_info_address"  => "nullable|string|max:200",
            "flat"                    => "required|numeric|min:0",
            "reference_address"       => "nullable|string|max:200",
            "getter_user_id"          => "nullable|exists:users,id",
            "agent_user_id"           => "nullable|exists:users,id",
            "images.*"                => "file|mimes:jpg,jpeg,png",

            "credit_amount_anticipated"     => "nullable|numeric|min:0",
            "credit_interest_percentage"    => "nullable|numeric|min:0",
            "credit_cuotes"                 => "nullable|array",
            "credit_cuotes.*.number_letter" => "required|string|max:20",
            "credit_cuotes.*.giro_at"       => "required|date:Y-m-d",
            "credit_cuotes.*.expiration_at" => "required|date:Y-m-d",

            "fee_getter"                    => "nullable|numeric|lt:unit_price",
            "is_credit"                     => "required|in:1,0",
        ];
    }

    public function attributes()
    {
        $validationMessages = [
            "name"          => "Titulo" ,
            "customer_dni"  => "Cédula de Cliente",
            "customer_name" => "Nombre de Cliente",
            "fee_getter"    => "Comisión de Captador",
            "is_credit"     => "Forma de Pago"
        ];

        foreach ($this->file('images') ?? [] as $key => $val) {
            $validationMessages["images." . $key] = "imagen °N ".($key + 1);
        }

        foreach ($this->get('credit_cuotes') ?? [] as $key => $val) {
            $validationMessages["credit_cuotes." . $key . ".number_letter"] = "Cuota N° ".($key + 1);
            $validationMessages["credit_cuotes." . $key . ".giro_at"]       = "Fecha Giro de Cuota N° ".($key + 1);
            $validationMessages["credit_cuotes." . $key . ".expiration_at"] = "Fecha de Vencimiento de Cuota N° ".($key + 1);
        }

        return $validationMessages;
    }

    public function messages()
    {
        return [
            "customer_name.required_unless" => "El campo :attribute es requerido si se coloca la cédula."
        ];
    }

}
