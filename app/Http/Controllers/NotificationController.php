<?php

namespace App\Http\Controllers;

use App\Models\UserPlayer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        return $user->unreadNotifications->map(function($notification){
            $notification->ago = ucwords(Carbon::parse($notification->created_at)->diffForHumans());

            return $notification;
        });
    }

    function read(Request $request)
    {
        $id = $request->get('id', []);
        $user = $request->user();

        $unread = $user->notifications()->when(count($id) > 0, function($query) use ($id){
            $query->whereIn('id', $id);
        })->get();

        $unread->markAsRead();
    }

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
