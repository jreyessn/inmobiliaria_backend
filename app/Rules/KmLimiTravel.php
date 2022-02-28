<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class KmLimiTravel implements Rule
{
    private $km_current = 0;

    private $km_limit = 0;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($km_current, $km_limit)
    {
        $this->km_current = $km_current;
        $this->km_limit   = $km_limit;
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
        if(is_null($this->km_current) || $this->km_limit == 0){
            return true;
        }

        if($this->km_current > $this->km_limit){
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
        return "El registro no debe sobrepasar el kilometraje de {$this->km_limit} puesto que alteraría los calculos de registros posteriores.";
    }
}
