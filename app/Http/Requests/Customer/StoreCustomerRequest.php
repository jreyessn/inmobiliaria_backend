<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'tradename'      => "required|string|max:100|unique:customers,tradename,NULL,id,deleted_at,NULL",
            'business_name'  => "required|string|max:200|unique:customers,business_name,NULL,id,deleted_at,NULL",
            'price_coupon'   => 'required|numeric',
            'subscriptions'  => 'array',
            'subscriptions.*.start_date'          => 'required|date',
            'subscriptions.*.every_day'        => 'required|numeric',
            'subscriptions.*.quantity_coupons' => 'required|numeric',
        ];
    }

    public function attributes()
    {
    
        foreach ($this->get('subscriptions') as $key => $val) {
            $titles["subscriptions.$key.start_date"] = "Fecha de Inicio";
            $titles["subscriptions.$key.every_day"] = "Frecuencia de Días";
            $titles["subscriptions.$key.quantity_coupons"] = "Cantidad de Cupones";
        }
        
        return $titles;
    }
    

}
