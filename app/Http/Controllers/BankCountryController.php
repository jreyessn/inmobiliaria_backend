<?php

namespace App\Http\Controllers;

use App\Models\BankCountry;
use Illuminate\Http\Request;

class BankCountryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response['data'] = BankCountry::all();

        return $response;
    }
}
