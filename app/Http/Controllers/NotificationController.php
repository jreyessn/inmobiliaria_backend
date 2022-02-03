<?php

namespace App\Http\Controllers;

use App\Models\UserPlayer;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    function savePlayerSignal(Request $request)
    {
        $request->validate([
            "player_id" => "required",
            "subscription" => "required"
        ]);

        $user = $request->user();

        $playerFound =  UserPlayer::where("player_id", $request->player_id)->first();

        if(is_null($playerFound) && $request->subscription){
            $playerFound = UserPlayer::create([
                "player_id" => $request->player_id,
                "user_id" => $user->id
            ]);
        }

        if(!is_null($playerFound) && !$request->subscription){
            $playerFound->delete();
        }

    }
}
