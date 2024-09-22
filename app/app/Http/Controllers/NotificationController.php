<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function getNotifications() : Object
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_hide', false)
            ->get();

        return response()->json([
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function hideNotification($notificationId): Object
    {
        $notification = Notification::find($notificationId);
        $notification->is_hide = true;
        $notification->save();

        return response()->json(['message' => 'Notification hidden']);
    }
}


