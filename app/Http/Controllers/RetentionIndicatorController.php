<?php

namespace App\Http\Controllers;

use App\Models\RetentionIndicator;
use Illuminate\Http\Request;

class RetentionIndicatorController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response['data'] = RetentionIndicator::all();

        return $response;
    }
}
