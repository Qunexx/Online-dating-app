<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{recipientId}', function ($user, $recipientId) {
    return (int) $user->id === (int) $recipientId || (int) $user->id === auth()->id();
});

Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId || (int) $user->id === auth()->id();
});


