<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::all();
        return Inertia::render('messages/index', [
            'initialMessages' => [],
            'users' => $users,
            'selectedRecipient' => null,
        ]);
    }

    public function fetchMessages($recipientId)
    {
        $messages = \App\Models\Message::where('sender_id', auth()->id())
            ->where('receiver_id', $recipientId)
            ->orWhere('sender_id', $recipientId)
            ->where('receiver_id', auth()->id())
            ->with('sender')
            ->get();

        return Inertia::render('messages/index', [
            'initialMessages' => $messages,
            'users' => \App\Models\User::all(),
            'selectedRecipient' => (int)$recipientId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|max:255',
        ]);

        $message = \App\Models\Message::create([
            'content' => $request->message,
            'sender_id' => auth()->id(),
            'receiver_id' => $request->recipient_id,
        ]);

        $user = Auth::user();
        broadcast(new NewMessage($message, $request->recipient_id))->toOthers();

        return redirect()->route('messages.index', ['recipientId' => $request->recipient_id])->with('success', 'Сообщение отправлено!');
    }
}
