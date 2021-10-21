<?php

namespace App\Rules;

use App\Models\Customer\Customer;
use Illuminate\Contracts\Validation\Rule;

class CustomerCouponsAvailables implements Rule
{

    private $messageValidation = "El cliente no dispone de esa cantidad de cupones";

    private $id;

    private $type_movement;

    private $io;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id, $type_movement, $io = 1)
    {
        $this->id = $id;
        $this->type_movement = $type_movement;
        $this->io = $io;
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
        $customer = Customer::find($this->id);

        if(
            $this->type_movement == getMovement(1) || 
            $this->type_movement == getMovement(2) || 
            ($this->type_movement == getMovement(4) && $this->io == 1)
        )
            return true;

        if(is_null($customer))
            return false;

        if($customer->coupons == 0){
            $this->messageValidation = "El cliente no dispone de cupones";
            return false;
        }

        if($value > $customer->coupons){
            $this->messageValidation = "El cliente solo dispone de {$customer->coupons} cupones";
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
