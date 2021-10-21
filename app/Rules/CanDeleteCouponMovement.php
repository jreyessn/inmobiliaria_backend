<?php

namespace App\Rules;

use App\Models\Coupons\CouponsMovements;
use Illuminate\Contracts\Validation\Rule;

class CanDeleteCouponMovement implements Rule
{

    private $messageValidation = "No puede eliminar el movimiento porque el cliente no tiene suficientes cupones para la diferencia";

    private $id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Al eliminar un movimiento hay que ajustar el inventario de cupones. Los movimientos de venta son los que al ser eliminados, deben
     * hacer una "devolución" del inventario. Por esa razón, la validacion evita que se pueda desfasar el inventario del cliente 
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $movement = CouponsMovements::find($this->id);
        $customer = $movement->customer;

        if(is_null($movement) || is_null($customer))
            return false;

        if($customer->coupons == 0 && $movement->type_movement == getMovement(1)){
            $this->messageValidation = "No puede eliminar el movimiento porque el cliente tiene 0 cupones. No es suficiente para reajustar el inventario.";
            return false;
        }

        if($movement->quantity > $customer->coupons && $movement->type_movement == getMovement(1)){
            $this->messageValidation = "No puede eliminar el movimiento puesto que el cliente solo tiene {$customer->coupons} cupones. No es suficiente para reajustar el inventario.";
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
