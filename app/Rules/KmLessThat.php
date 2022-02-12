<?php

namespace App\Rules;

use App\Models\Vehicle\Vehicle;
use Illuminate\Contracts\Validation\Rule;

class KmLessThat implements Rule
{
    private $km_current = 0;

    private $km_traveled = 0;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $km_current)
    {
        $this->km_current = $km_current;
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
        $this->km_traveled = $vehicle->km_traveled;

        if($this->km_current >= $vehicle->km_traveled){
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
