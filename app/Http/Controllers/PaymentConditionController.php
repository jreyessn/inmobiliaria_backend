<?php

namespace App\Http\Controllers;

use App\Models\PaymentCondition;
use Illuminate\Http\Request;

class PaymentConditionController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response['data'] = PaymentCondition::all();

        return $response;
    }
}
