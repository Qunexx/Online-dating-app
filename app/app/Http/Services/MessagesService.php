<?php

namespace App\Http\Services;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class MessagesService
{
    public function getDialogUsers($user): Collection //коллекций диалогов пользователя
    {
        $dialogUsers = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with('sender', 'receiver')
            ->get()
            ->pluck('sender')
            ->merge(Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->with('sender', 'receiver')
                ->get()
                ->pluck('receiver'))
            ->unique('id')
            ->values();

        return $dialogUsers;
    }

    public function getUserMessages($user, int $recipient_id): Collection //коллекция сообщений с конкретным пользователем
    {
        $messages = Message::where(function ($query) use ($user, $recipient_id) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $recipient_id);
        })->orWhere(function ($query) use ($user, $recipient_id) {
            $query->where('sender_id', $recipient_id)
                ->where('receiver_id', $user->id);
        })->with('sender')->get();

        return $messages;
    }

    public function createMessage(Request $request): Message //создание сообщения
    {
        return Message::create([
            'content' => $request->message,
            'sender_id' => auth()->id(),
            'receiver_id' => $request->recipient_id,
        ]);
    }

}
