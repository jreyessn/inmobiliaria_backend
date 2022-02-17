<?php

namespace App\Http\Requests\Services;

use App\Rules\ServiceCompleted;
use Illuminate\Foundation\Http\FormRequest;

class StoreServicesRequest extends FormRequest
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {

        $isRouteComply = collect($this->segments("comply"))->search("comply");

        if($isRouteComply !== false){
            $this->merge([
                "id"        => $this->route("id"),
                "save_from" => "pwa",
                "status"    => 1
            ]);
        }

    }


    /**
     * Validar servicio de dos maneras
     * 
     * Si es un actualizar estándar entonces los campos son requeridos. Si es un actualizar por medio
     * de la ruta de "comply" (desde la pwa), solo se validarán los campos necesarios para cambiar el estado
     * del servicio
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id"                    => new ServiceCompleted,
            "categories_service_id" => "required_without:save_from|exists:categories_services,id",
            "type_service_id"       => "required_without:save_from|exists:type_services,id",  
            "equipment_id"          => "required_without:save_from|exists:equipments,id",
            "equipments_part_id"    => "nullable|exists:equipments_parts,id",
            "user_assigned_id"      => "required_without:save_from|exists:users,id",
            "farm_id"               => "required_without:save_from|exists:farms,id",
            "priorities_service_id" => "required_without:save_from|exists:priorities_services,id",
            "event_date"            => "required_without:save_from|date|after_or_equal:today",
            "note"                  => "nullable|string|max:300",

            "received_by"           => "required_if:save_from,pwa|string|max:200",
            "observation"           => "required_if:save_from,pwa|string|max:300",
            "signature"             => "required_if:save_from,pwa|string",
            "evidences_after.*"     => "file|mimes:jpg,jpeg,png",
            "evidences_before.*"    => "file|mimes:jpg,jpeg,png",
        ];
    }

    public function attributes()
    {
        $validationMessages = [];

        foreach ($this->file('evidences_after') ?? [] as $key => $val) {
            $validationMessages["evidences_after.{$key}"] = "Archivo de Evidencia (Después) °N ".($key + 1);
        }

        foreach ($this->file('evidences_before') ?? [] as $key => $val) {
            $validationMessages["evidences_before.{$key}"] = "Archivo de Evidencia (Antes) °N ".($key + 1);
        }

        return $validationMessages;
    }

    public function messages()
    {
        $validationMessages = [
            "required_without"          => "El campo :attribute es obligatorio",
            "required_if"               => "El campo :attribute es obligatorio",
            "event_date.after_or_equal" => "El campo :attribute debe ser una fecha posterior o igual a hoy."
        ];

        return $validationMessages;
    }
}
