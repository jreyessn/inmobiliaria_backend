<?php

namespace App\Http\Requests\Coupons;

use App\Rules\CustomerCouponsAvailables;
use Illuminate\Foundation\Http\FormRequest;

class StoreCouponsRequest extends FormRequest
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
            'customer_id'        => "required|exists:customers,id",
            'type_movement'      => "required|in:Entrega,Ajuste,Devolución,Venta",
            'payment_method_id'  => "nullable|exists:payment_methods,id",
            'quantity'       => [
                'required',
                'numeric', 
                'min:1', 
                new CustomerCouponsAvailables($this->customer_id, $this->type_movement)
            ],
            'num_invoice'        => 'nullable',
            'comment'            => 'nullable',
        ];
    }
    
}
