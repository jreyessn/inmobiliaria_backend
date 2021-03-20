<?php

namespace App\Http\Controllers;

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
}
