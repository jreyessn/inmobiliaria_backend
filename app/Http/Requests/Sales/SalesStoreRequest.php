<?php

namespace App\Http\Requests\Sales;

use App\Rules\FurnitureHasSold;
use Illuminate\Foundation\Http\FormRequest;

class SalesStoreRequest extends FormRequest
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
            "serie"                      => "nullable|string|max:20",
            "number"                     => "nullable|string|max:20",
            "furniture_id"               => [
                "required",
                "exists:furniture,id",
                new FurnitureHasSold()
            ],
            "document_id"                => "required|exists:documents,id",
            "customer_id"                => "required|exists:customers,id",
            "payment_method_id"          => "required|exists:payment_methods,id",
            "tax_percentage"             => "numeric|min:0",
            "subtotal"                   => "required|numeric",
            "note"                       => "nullable|string",
            "is_credit"                  => "required",
            "credit_amount_anticipated"  => "nullable|numeric|min:0",
            "credit_interest_percentage" => "nullable|numeric|min:0",

            "credit_cuotes"                 => "nullable|array",
            "credit_cuotes.*.number_letter" => "required|string|max:20",
            "credit_cuotes.*.giro_at"       => "required|date:Y-m-d",
            "credit_cuotes.*.expiration_at" => "required|date:Y-m-d",
        ];
    }

    public function attributes()
    {
        $validationMessages = [];

        foreach ($this->get('credit_cuotes') ?? [] as $key => $val) {
            $validationMessages["credit_cuotes." . $key . ".number_letter"] = "Letra de Cuota N° ".($key + 1);
            $validationMessages["credit_cuotes." . $key . ".giro_at"]       = "Fecha Giro de Cuota N° ".($key + 1);
            $validationMessages["credit_cuotes." . $key . ".expiration_at"] = "Fecha de Vencimiento de Cuota N° ".($key + 1);
        }

        return $validationMessages;
    }
}
