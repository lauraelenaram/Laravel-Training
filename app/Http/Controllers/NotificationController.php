<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    public function get()
    {
        $unreadNotifications= Auth::user()->unreadNotifications;
        $actualDate= date('Y-m-d');
        foreach ($unreadNotifications as $notification)
        {
            if($actualDate != $notification->created_at->toDateString())
            {
                $notification->markAsRead();
            }
        }
        return Auth::user()->unreadNotifications;
    }
}
