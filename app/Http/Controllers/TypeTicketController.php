<?php

namespace App\Http\Controllers;

use App\Models\TypeTicket;
use Illuminate\Http\Request;

class TypeTicketController extends Controller
{
    function __invoke(Request $request)
    {
        $search = $request->get('search', null);

        return ['data' => TypeTicket::where('description', 'like', "%{$search}%")->get()];
    }
}
