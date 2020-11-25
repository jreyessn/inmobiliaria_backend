<?php

namespace App\Http\Controllers;

use App\Models\RetentionCountry;
use Illuminate\Http\Request;

class RetentionCountryController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response['data'] = RetentionCountry::all();

        return $response;
    }
}
