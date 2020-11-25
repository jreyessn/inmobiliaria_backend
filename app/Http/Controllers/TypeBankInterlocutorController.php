<?php

namespace App\Http\Controllers;

use App\Models\TypeBankInterlocutor;

class TypeBankInterlocutorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $response['data'] = TypeBankInterlocutor::all();

        return $response;
    }
}
