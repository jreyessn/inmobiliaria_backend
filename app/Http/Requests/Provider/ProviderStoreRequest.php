<?php

namespace App\Http\Requests\Provider;

use Illuminate\Support\Carbon;
use App\Rules\ValidDateDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProviderStoreRequest extends FormRequest
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

    protected function prepareForValidation() 
    {
        if(!is_null($this->constancia_situacion_fiscal_file)){
            $this->merge(['constancia_situacion_fiscal_date' => $this->captureDate($this->constancia_situacion_fiscal_file)]);
        }
        
        if(!is_null($this->estado_cuenta_file)){
            $this->merge(['estado_cuenta_date' => $this->captureDate($this->estado_cuenta_file)]);
        }
        
        if(!is_null($this->formato_32d_file)){
            $this->merge(['formato_32d_date' => $this->captureDate($this->formato_32d_file)]);
        }
        
        if(!is_null($this->comprobante_domicilio_file)){
            $this->merge(['comprobante_domicilio_date' => $this->captureDate($this->comprobante_domicilio_file)]);
        }
    } 
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'applicant_name' => 'required',
            'business_name' => 'required',
            'rfc' => 'required',
            'business_type_id' => 'required',
            'business_type_activity' => 'required',
            'fiscal_address' => 'required',
            'street_address' => 'required',
            'colony' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
            'main_shareholder' => 'required',
            'sales_representative' => 'required',
            'sales_phone' => 'required',
            'email_quotation' => 'required',
            'email_purchase_orders' => 'required',
            'website' => 'required',
            'retention' => 'required',
            'retention' => 'required',

            'retention_type_id' => 'array',
            'retention_indicator_id' => 'array',

            "account_holder" => "required",
            "account_number" => "required",
            "bank_name" => "required",
            "bank_address" => "required",
            "bank_country_id" => "required",
            "bank_id" => "required",

            'reference_provider_name' => 'array',
            'reference_contact' => 'array',
            'reference_phone' => 'array',
            'reference_email' => 'array',

            'acta_constitutiva_file' => 'file|nullable',
            'acta_constitutiva_date' => 'date|nullable',

            'constancia_situacion_fiscal_file' => 'file|nullable',
            'constancia_situacion_fiscal_date' => ['nullable', new ValidDateDocument],

            'copia_identificacion_file' => 'file|nullable',
            'copia_identificacion_date' => 'date|nullable',

            'formato_32d_file' => 'file|nullable',
            'formato_32d_date' => ['nullable', new ValidDateDocument],

            'estado_cuenta_file' => 'file|nullable',
            'estado_cuenta_date' => ['nullable', new ValidDateDocument],

            'comprobante_domicilio_file' => 'file|nullable',
            'comprobante_domicilio_date' => ['nullable', new ValidDateDocument],

            'imss_file' => 'file|nullable',
            'imss_date' => 'date|nullable',

            'rfc_file' => 'file|nullable',
            'rfc_date' => 'date|nullable',

            'owner_file' => 'file|nullable',
            'owner_date' => 'date|nullable',

            'account_routing_file' => 'file|nullable',
            'account_routing_date' => 'date|nullable',
        ];
    }

    private function captureDate($value){

        try {

            $parser = new \Smalot\PdfParser\Parser();
            
            $pdf = $parser->parseFile($value->getPathname());
            $content = preg_replace('/\s+/', ' ', trim($pdf->getText()));
            
            preg_match('/ Fecha: (.*?) /', $content, $dateMatches);
            
            $dateFormated = Carbon::createFromFormat('d/m/Y', $dateMatches[1])->format('Y-m-d');

            return $dateFormated;

        } catch (Exception $th) {
            return false;
        }
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
        ];
    }

    public function attributes(){
        return [
            'applicant_name' => 'Nombre Comercial',
            'business_name' => 'Razón Social',
            'rfc' => 'RFC',
            'business_type_id' => 'Tipo de Empresa',
            'business_type_activity' => 'Actividad Empresarial',
            'fiscal_address' => 'Domicilio Fiscal',
            'street_address' => 'Calle',
            'colony' => 'Colinia',
            'country_id' => 'Pais',
            'state_id' => 'Estado',
            'city_id' => 'Ciudad',
            'zip_code' => 'Código Postal',
            'phone' => 'Teléfono',
            'main_shareholder' => 'Representante Legal',
            'sales_representative' => 'Contacto de Ventas',
            'sales_phone' => 'Teléfono de Ventas',
            'email_quotation' => 'Correo para solicitud de Cotizaciones',
            'email_purchase_orders' => 'Correo para Ordenes de Compra',
            'website' => 'Sitio Web',
            'retention' => 'Retención',

            'constancia_situacion_fiscal_date' => "Fecha de la Constancia de Situación Fiscal",
            'estado_cuenta_date' => "Fecha del Estado de Cuenta",
            'formato_32d_date' => "Fecha del Formato 32d",
            'comprobante_domicilio_date' => "Fecha del Comprobante de Domicilio",
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
