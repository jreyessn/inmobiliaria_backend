<?php

namespace App\Http\Controllers;

use App\Models\BusinessType;

use App\Repositories\BusinessType\BusinessTypeRepositoryEloquent;

class BusinessTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $response['data'] = BusinessType::all();

        return $response;
    }


}
