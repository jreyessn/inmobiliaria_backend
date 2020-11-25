<?php

namespace App\Http\Controllers;

use App\Models\AccountsGroup;
use Illuminate\Http\Request;

class AccountsGroupController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response['data'] = AccountsGroup::all();

        return $response;
    }
}
