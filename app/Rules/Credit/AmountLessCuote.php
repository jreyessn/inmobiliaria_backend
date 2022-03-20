<?php

namespace App\Rules\Credit;

use App\Models\Sale\CreditCuote;
use Illuminate\Contracts\Validation\Rule;

class AmountLessCuote implements Rule
{
    private $cuote_id;

    private $cuote;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($cuote_id)
    {
        $this->cuote_id = $cuote_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->cuote = CreditCuote::find($this->cuote_id);

        if($value > $this->cuote->amount_pending){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $format = number_format($this->cuote->amount_pending, 2);

        return "El monto no puede superar al valor de la cuota. Valor: {$format}";
    }
}
