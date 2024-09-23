<?php

namespace App\Http\Services;

use App\Events\NewNotification;
use App\Models\Notification;

class NotificationService
{
    public function createNotification(int $user_id, int $user_id_from, string $message) : bool
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
}
