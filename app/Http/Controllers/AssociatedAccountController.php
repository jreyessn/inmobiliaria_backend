<?php

namespace App\Http\Controllers;

use App\Models\AssociatedAccount;
use Illuminate\Http\Request;
use App\Repositories\AssociatedAccount\AssociatedAccountRepositoryEloquent;

class AssociatedAccountController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response['data'] = AssociatedAccount::orderBy('default', 'desc')->get();

        return $response;
    }
}
