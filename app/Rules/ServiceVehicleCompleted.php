<?php

namespace App\Rules;

use App\Models\Vehicle\ServiceVehicle;
use App\Models\Vehicle\Vehicle;
use Illuminate\Contracts\Validation\Rule;

class ServiceVehicleCompleted implements Rule
{

    private $status_text = "";

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
        $service = ServiceVehicle::find($value); 
        $status  = $service->status ?? 0; 

        if($status > 0){
            $this->status_text = $service->status_text ?? "";
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
        return "El servicio ya se encuentra {$this->status_text}, no puede modificarlo.";
    }
}
