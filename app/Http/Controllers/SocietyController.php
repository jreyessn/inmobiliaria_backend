<?php

namespace App\Http\Controllers;

use App\Models\Society;

class SocietyController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $response['data'] = Society::all();

        return $response;
    }
}
