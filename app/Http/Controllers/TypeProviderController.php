<?php

namespace App\Http\Controllers;

use App\Models\TypeProvider;

class TypeProviderController extends Controller
{
    
    public function __invoke()
    {
        $response['data'] = TypeProvider::all();

        return $response;
    }
}
