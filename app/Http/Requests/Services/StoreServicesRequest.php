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
            "equipments_part_id"    => "required_without:save_from|exists:equipments_parts,id",
            "user_assigned_id"      => "required_without:save_from|exists:users,id",
            "farm_id"               => "required_without:save_from|exists:farms,id",
            "event_date"            => "required_without:save_from|date",
            "note"                  => "nullable|string|max:300",

            "received_by"           => "required_if:save_from,pwa|string|max:200",
            "observation"           => "required_if:save_from,pwa|string|max:300",
            "signature"             => "required_if:save_from,pwa|string",
            "evidences.*"           => "file|mimes:jpg,jpeg,png"
        ];
    }

    public function attributes()
    {
        $validationMessages = [];

        foreach ($this->get('spare_parts') ?? [] as $key => $val) {
            $validationMessages["evidences.{$key}"] = "Archivo °N ".($key + 1);
        }

        return $validationMessages;
    }

    public function messages()
    {
        return [
            "required_without" => "El campo :attribute es obligatorio",
            "required_if"      => "El campo :attribute es obligatorio"
        ];
    }
}
