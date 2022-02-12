<?php

namespace App\Rules;

use App\Models\Vehicle\Fuel;
use Illuminate\Contracts\Validation\Rule;

class IsLastFuel implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $fuel = Fuel::find($value); 

        if($fuel->is_last_loaded){
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Solo puede modificar la última carga de combustible realizada para evitar inconvenientes con los cálculos de Kilometrajes.';
    }
}
