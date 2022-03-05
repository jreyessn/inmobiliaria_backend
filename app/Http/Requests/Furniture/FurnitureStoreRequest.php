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
        $id = $this->route("furniture");

        return [
            "name"                    => "required|string|max:200|unique:furniture,name,{$id},id,deleted_at,NULL",
            "description"             => "nullable|string|max:200",
            "bathrooms"               => "required|numeric|min:0",
            "bedrooms"                => "required|numeric|min:0",
            "covered_garages"         => "required|numeric|min:0",
            "uncovered_garages"       => "required|numeric|min:0",
            "measure_unit_id"         => "required|exists:measure_units,id",
            "area"                    => "nullable|string|max:200",
            "unit_price"              => "required|numeric|min:0",
            "sale_price"              => "required|numeric|min:0",
            "type_furniture_id"       => "required|exists:type_furnitures,id",
            "city_id"                 => "required|exists:cities,id",
            "postal_code"             => "nullable|numeric",
            "region"                  => "nullable|string|max:200",
            "address"                 => "nullable|string|max:200",
            "street_number"           => "nullable|string|max:200",
            "aditional_info_address"  => "nullable|string|max:200",
            "flat"                    => "required|numeric|min:0",
            "reference_address"       => "nullable|string|max:200",
            "getter_user_id"          => "nullable|exists:users,id",
            "agent_user_id"           => "nullable|exists:users,id",
            "images.*"                => "file|mimes:jpg,jpeg,png"
        ];
    }

    public function attributes()
    {
        $validationMessages = [
            "name" => "Titulo"
        ];

        foreach ($this->file('images') ?? [] as $key => $val) {
            $validationMessages["images." . $key] = "imagen °N ".($key + 1);
        }

        return $validationMessages;
    }

}
