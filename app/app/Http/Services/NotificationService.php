<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function createNotification(int $user_id, int $user_id_from, string $message): bool
    {
        $notification = new Notification();
        $notification->user_id = $user_id;
        $notification->message = $message;
        $notification->from_user_id = $user_id_from;
        $notification->is_read = false;
        $notification->is_hide = false;
        $notification->save();

        //event(new NewNotification($notification));
        broadcast(new NewNotification($notification))->toOthers();
        return true;
    }

    public function getUserNotifications(): array
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_hide', false)
            ->get();

        return [
            'user' => $user,
            'notifications' => $notifications,
        ];
    }

    public function hideNotification(int $id): bool
    {
        $notification = Notification::find($id);
        $notification->is_hide = true;
        $notification->save();

        return true;
    }
}
