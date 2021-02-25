<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    function __invoke(Request $request)
    {
        $search = $request->get('search', null);

        return ['data' => Priority::where('description', 'like', "%{$search}%")->get()];
    }
}
