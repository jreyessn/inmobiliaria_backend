<?php

namespace App\Http\Requests\Equipments;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentsRequest extends FormRequest
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
        $id = $this->route("equipment");

        return [
            "name"                    => "required|string|max:200|unique:equipments,name,{$id},id,deleted_at,NULL",
            "categories_equipment_id" => "required|exists:categories_equipments,id",
            "brands_equipment_id"     => "nullable|exists:brands_equipments,id",
            "no_serie"                => "required|string|max:200",
            "area_id"                 => "nullable|exists:areas,id",
            "between_days_service"    => "required|numeric|min:1",
            "cost"                    => "required|numeric",
            "maintenance_required"    => "nullable|numeric|in:1,0",
            "no_serie_visible"        => "nullable|numeric|in:1,0",
            "parts"                   => "array",
            "images.*"                => "file|mimes:jpg,jpeg,png"
        ];
    }

    public function attributes()
    {
        $validationMessages = [];

        foreach ($this->file('images') ?? [] as $key => $val) {
            $validationMessages["images." . $key] = "archivo °N ".($key + 1);
        }

        return $validationMessages;
    }
}
