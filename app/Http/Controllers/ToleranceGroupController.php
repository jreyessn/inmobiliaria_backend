<?php

namespace App\Http\Controllers;

use App\Models\ToleranceGroup;
use Illuminate\Http\Request;

class ToleranceGroupController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response['data'] = ToleranceGroup::all();

        return $response;
    }
}
