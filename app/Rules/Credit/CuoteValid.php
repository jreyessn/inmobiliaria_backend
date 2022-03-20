<?php

namespace App\Rules\Credit;

use App\Models\Sale\CreditCuote;
use Illuminate\Contracts\Validation\Rule;

class CuoteValid implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determina si las cuotas anteriores ya han sido pagadas para poder validar que esta
     * es la que debe pagarse.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $credit              = CreditCuote::find($value)->credit ?? collect([]);
        $is_cuotes_prev_paid = $credit->cuotes()->where("id", "<", $value)->get()->every(function ($value) {
            return $value->status == 1;
        }) ?? false;

        return $is_cuotes_prev_paid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No puede realizar el pago de esta cuota sin haber amortizado las anteriores.';
    }
}
