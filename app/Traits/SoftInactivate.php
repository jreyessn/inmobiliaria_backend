<?php

namespace App\Traits;

trait SoftInactivate
{

    protected $inactivate_field = 'inactivated_at';

    /**
     * Inactivar el registro en base a la propiedad $inactivate_field
     *
     * @return void
     */
    public static function inactivate()
    {

    }

}
