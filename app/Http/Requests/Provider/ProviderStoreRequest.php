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

    // Una lógica hecha inicialmente para detectar una fecha dentro de los pdf

    protected function prepareForValidation() 
    {
        // if(!is_null($this->constancia_situacion_fiscal_file)){
        //     $this->merge(['constancia_situacion_fiscal_date' => $this->captureDate($this->constancia_situacion_fiscal_file)]);
        // }
        
        // if(!is_null($this->estado_cuenta_file)){
        //     $this->merge(['estado_cuenta_date' => $this->captureDate($this->estado_cuenta_file)]);
        // }
        
        // if(!is_null($this->formato_32d_file)){
        //     $this->merge(['formato_32d_date' => $this->captureDate($this->formato_32d_file)]);
        // }
        
        // if(!is_null($this->comprobante_domicilio_file)){
        //     $this->merge(['comprobante_domicilio_date' => $this->captureDate($this->comprobante_domicilio_file)]);
        // }
    } 
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'applicant_name' => '',
            'business_name' => 'required',
            'rfc' => 'required',
            'business_type_id' => 'required',
            'business_type_activity' => 'required',
            'fiscal_address' => 'required',
            'street_address' => 'required',
            'colony' => 'required',
            'country_id' => 'required',
            'state_id' => '',
            'city_id' => '',
            'zip_code' => 'required',
            'phone' => 'required',
            'main_shareholder' => '',
            'sales_representative' => 'required',
            'sales_phone' => 'required',
            'email_quotation' => 'required',
            'email_purchase_orders' => 'required',
            'website' => 'required',
            
            'retention' => '',

            'retention_type_id' => 'array|nullable',
            'retention_indicator_id' => 'array|nullable',

            "account_holder" => "array",
            "account_number" => "array",
            "bank_name" => "array",
            "bank_address" => "array",
            "bank_country_id" => "array",
            "bank_id" => "array",

            'reference_provider_name' => 'array',
            'reference_contact' => 'array',
            'reference_phone' => 'array',
            'reference_email' => 'array',

            'acta_constitutiva_file' => 'file|mimes:pdf|nullable',
            'acta_constitutiva_date' => 'date|nullable',

            'power_legal_representative_file' => 'file|mimes:pdf|nullable',
            'power_legal_representative_date' => 'date|nullable',

            'constancia_situacion_fiscal_file' => 'file|mimes:pdf|nullable',
            'constancia_situacion_fiscal_date' =>  ['date', 'nullable' ,new ValidDateDocument],

            'copia_identificacion_file' => 'file|mimes:pdf|nullable',
            'copia_identificacion_date' => 'date|nullable',

            'formato_32d_file' => 'file|mimes:pdf|nullable',
            'formato_32d_date' => ['date', 'nullable' ,new ValidDateDocument],

            'estado_cuenta_file' => 'file|mimes:pdf|nullable',
            'estado_cuenta_date' => ['date', 'nullable' ,new ValidDateDocument],

            'comprobante_domicilio_file' => 'file|mimes:pdf|nullable',
            'comprobante_domicilio_date' => ['date', 'nullable' ,new ValidDateDocument],

            'imss_file' => 'file|mimes:pdf|nullable',
            'imss_date' => 'date|nullable',

            'rfc_file' => 'file|mimes:pdf|nullable',
            'rfc_date' => 'date|nullable',

            'owner_file' => 'file|mimes:pdf|nullable',
            'owner_date' => 'date|nullable',

            'account_routing_file' => 'file|mimes:pdf|nullable',
            'account_routing_date' => 'date|nullable',
        ];
        
    }

    /* 
    * Código util para buscar patrones de palabras dentro de los PDF
    */
    // private function captureDate($value){

    //     try {

    //         $parser = new \Smalot\PdfParser\Parser();
            
    //         $pdf = $parser->parseFile($value->getPathname());
    //         $content = preg_replace('/\s+/', ' ', trim($pdf->getText()));
            
    //         preg_match('/día(.*?),/', $content, $dateMatches);

    //         if(count($dateMatches) == 2){
    //             $palabras = preg_split('/\s/', trim($dateMatches[1]), null, PREG_SPLIT_OFFSET_CAPTURE);
                
    //             $dateTransform = array();

    //             foreach($palabras as $palabraArray){
    //                 if($palabraArray[0] != "de")
    //                     array_push($dateTransform, $palabraArray[0]);
    //             }

    //             $month = month_en($dateTransform[1]);

    //             if(!$month)
    //                 return null;

    //             $dateTransform[1] = $month;
                
    //             $dateFormated = Carbon::parse(Carbon::parse($dateTransform[0].' '.$dateTransform[1].' '.$dateTransform[2]))->format('Y-m-d');
                
    //             return $dateFormated;
    //         }

    //         return null;

    //     } catch (Exception $th) {
    //         return null;
    //     }
    // }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'mimes' => "Solo se permiten archivos PDF"
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

            'constancia_situacion_fiscal_date' => "Constancia de Situación Fiscal",
            'estado_cuenta_date' => "Estado de Cuenta",
            'formato_32d_date' => "Formato 32d",
            'comprobante_domicilio_date' => "Comprobante de Domicilio",
        ];
    }


}
