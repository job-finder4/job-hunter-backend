<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->get();

        return response($notifications, 200);
    }


    public function update(Request $request)
    {
        $notification_id = $request->notification_id;

        auth()->user()->unreadNotifications()
            ->whereId($notification_id)
            ->update(['read_at' => Carbon::now()]);

        return response('marked as read', 200);
    }

}
