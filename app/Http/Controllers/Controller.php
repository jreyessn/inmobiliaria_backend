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
                    'description' => getMovement(1),
                    'use_payment_method' => true 
                ],
                [
                    'id' => 2,
                    'description' => getMovement(2),
                    'use_payment_method' => false 
                ],
                [
                    'id' => 3,
                    'description' => getMovement(4),
                    'use_payment_method' => false 
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
