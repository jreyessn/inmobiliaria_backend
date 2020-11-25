<?php

namespace App\Http\Controllers;

use App\Models\TreasuryGroup;
use Illuminate\Http\Request;

class TreasuryController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $response['data'] = TreasuryGroup::with('group')->get();

        return $response;
    }
}
