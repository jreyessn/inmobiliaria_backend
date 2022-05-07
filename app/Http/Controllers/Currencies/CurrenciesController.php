<?php

namespace App\Http\Controllers\Currencies;

use App\Http\Controllers\Controller;
use App\Models\Currency\Currency;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return [
            "data" => Currency::all()
        ];
    }
}
