<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function typeMovements(){
        return [
            'data' => [
                [
                    'id' => 1,
                    'description' => 'Compra'
                ],
                [
                    'id' => 2,
                    'description' => 'Venta'
                ],
                [
                    'id' => 3,
                    'description' => 'Devolución'
                ],
            ]
        ];
    }

    function paymentMethods(){
        return [
            'data' => PaymentMethod::all()
        ];
    }

}
