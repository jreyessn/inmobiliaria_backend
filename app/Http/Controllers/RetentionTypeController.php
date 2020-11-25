<?php

namespace App\Http\Controllers;

use App\Models\RetentionType;
use Illuminate\Http\Request;

class RetentionTypeController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $response['data'] = RetentionType::all();

        return $response;
    }
}
