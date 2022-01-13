<?php

namespace App\Rules;

use App\Models\Services\Service;
use Illuminate\Contracts\Validation\Rule;

class ServiceCompleted implements Rule
{

    private $status_text = "";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Valida si el servicio ya tiene el estado diferente de pendiente.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $service = Service::find($value); 
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
