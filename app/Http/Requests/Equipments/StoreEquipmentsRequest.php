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
            "no_serie"                => "nullable|string|max:200",
            "categories_equipment_id" => "required|exists:categories_equipments,id",
            "brand"                   => "nullable|string|max:200",
            "area_id"                 => "nullable|exists:areas,id",
            "between_days_service"    => "nullable|numeric|min:0",
            "cost"                    => "required|numeric",
            "days_before_create"      => "required|numeric|min:1",
            "maintenance_required"    => "nullable|numeric|in:1,0",
            "no_serie_visible"        => "nullable|numeric|in:1,0",
            "obtained_at"             => "nullable|date|before_or_equal:today",
            "last_service_at"         => "nullable|date|before_or_equal:today",
            "parts.*.name"            => "required|string",
            "parts.*.between_days_service" => "nullable|numeric|min:0",
            "parts.*.last_service_at"      => "nullable|date|before_or_equal:today",
            "images.*"                     => "file|mimes:jpg,jpeg,png"
        ];
    }

    public function attributes()
    {
        $validationMessages = [];

        foreach ($this->file('images') ?? [] as $key => $val) {
            $validationMessages["images." . $key] = "archivo °N ".($key + 1);
        }

        foreach ($this->get('parts') ?? [] as $key => $val) {
            $validationMessages["parts." . $key] = "pieza de equipo °N ".($key + 1);
            $validationMessages["parts." . $key . ".last_service_at"] = "Fecha de ultimo servicio para la pieza de equipo N° ".($key + 1);
            $validationMessages["parts." . $key . ".between_days_service"] = "Días entre servicio para la pieza de equipo N° ".($key + 1);
            $validationMessages["parts." . $key . ".name"] = "Nombre para la pieza de equipo N° ".($key + 1);
        }

        return $validationMessages;
    }

    public function messages()
    {
        $validationMessages = [];
        $validationMessages["obtained_at.before_or_equal"] = "El campo :attribute debe ser una fecha anterior o igual a hoy.";
        $validationMessages["last_service_at.before_or_equal"] = "El campo :attribute debe ser una fecha anterior o igual a hoy.";

        foreach ($this->get('parts') ?? [] as $key => $val) {
            $validationMessages["parts." . $key . ".last_service_at.before_or_equal"] = "El campo :attribute debe ser una fecha anterior o igual a hoy.";
        }

        return $validationMessages;
    }
}
