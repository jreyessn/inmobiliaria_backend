<?php

namespace App\Rules;

use App\Models\Coupons\CouponsRequest;
use Illuminate\Contracts\Validation\Rule;

class IsApprovedRequestCoupon implements Rule
{
    private $id;

    private $messageValidation = "";

    /**
     * Create a new rule instance.
     *
     * @param $id ID de la solicitud
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        $couponRequest = CouponsRequest::find($this->id);

        if(is_null($couponRequest)){
            $this->messageValidation = "No se ha logrado encontrar la solicitud";
            return false;
        }

        if($couponRequest->approved == 1){
            $this->messageValidation = "No puede actualizar porque esta solicitud ya se encuentra aprobada";
            return false;
        }

        if($couponRequest->approved == 2){
            $this->messageValidation = "No puede actualizar porque esta solicitud ya se encuentra rechazada";
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
        return $this->messageValidation;
    }
}
