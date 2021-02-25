<?php

namespace App\Http\Controllers;

use App\Models\StatusTicket;
use Illuminate\Http\Request;

class StatusTicketController extends Controller
{
    function __invoke(Request $request)
    {
        $search = $request->get('search', null);

        return ['data' => StatusTicket::where('description', 'like', "%{$search}%")->get()];
    }
}
