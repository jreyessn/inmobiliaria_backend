<?php

namespace App\Http\Controllers;

use App\Notifications\SendLinkRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SendMailController extends Controller
{
    function __invoke(Request $request)
    {
        foreach ($request->mails as $value) {
            Notification::route('mail', $value)->notify(new SendLinkRegister($request->link));
        }

        return response()->json([
            'message' => 'Se ha enviado el correo exitosamente'
        ], 200);
    }
}
