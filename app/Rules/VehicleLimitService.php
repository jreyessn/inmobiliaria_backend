<?php

namespace App\Rules;

use App\Models\Vehicle\Vehicle;
use Illuminate\Contracts\Validation\Rule;

class VehicleLimitService implements Rule
{

    private $message = "";

    private $km_current = 0;

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

        if($this->km_current > $vehicle->km_limit){
            $this->message = "Ha sobrepasado el limite de kilometraje de esta unidad. Limite: {$vehicle->km_limit} KM";
            return false;
        }

        if($vehicle->maintenance_limit_at && now()->gt($vehicle->maintenance_limit_at)){
            $dateText = $vehicle->maintenance_limit_at->format("d/m/Y");
            $this->message = "Ha sobrepasado la fecha limite de mantenimiento de esta unidad. Fecha Limite: {$dateText}";
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
        return $this->message;
    }
}
