<?php

namespace App\Rules;

use App\Models\Furniture\Furniture;
use App\Models\Products\Brand;
use Illuminate\Contracts\Validation\Rule;

class FurnitureHasSold implements Rule
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
        $furniture = Furniture::find($value);
        if($furniture->is_sold){
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
        return 'No puede realizar esta operación. El inmueble ya se encuentra vendido.';
    }
}
