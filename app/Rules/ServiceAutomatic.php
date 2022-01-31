<?php

namespace App\Rules;

use App\Models\Services\Service;
use Illuminate\Contracts\Validation\Rule;

class ServiceAutomatic implements Rule
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
        $service = Service::find($value); 

        if($service->is_automatic ?? false){
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
        return "No puede realizar esta acción con servicios generados por el sistema";
    }
}
