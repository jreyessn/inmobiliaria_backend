<?php

namespace App\Rules;

use App\Models\Vehicle\Vehicle;
use Illuminate\Contracts\Validation\Rule;

class KmLessThat implements Rule
{
    private $km_current = 0;

    private $km_traveled = 0;

    private $km_sub = 0;

    /**
     * Create a new rule instance.
     *
     * @param $km_current Kilometraje actual a verificar
     * @param $km_sub Se resta kilometraje (del registro actual para casos de actualizar) al recorrido
     * @return void
     */
    public function __construct($km_current, $km_sub = 0)
    {
        $this->km_current = $km_current;
        $this->km_sub     = $km_sub;
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
        $vehicle = Vehicle::find($value);
        
        if(is_null($this->km_current))
            return true;
        
        $this->km_traveled = $vehicle->km_traveled - $this->km_sub;

        if($this->km_current >= $this->km_traveled){
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
        return "La unidad tiene un kilometraje de {$this->km_traveled}, no puede colocar menos que esa cantidad.";
    }
}
