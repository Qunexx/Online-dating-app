<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Events\NewNotification;
use App\Http\Services\MessagesService;
use App\Http\Services\NotificationService;
use App\Models\Message;
use http\Env\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index(MessagesService $service) : \Inertia\Response
    {
        $user = auth()->user();
        $dialogUsers = $service->getDialogUsers($user);
        return Inertia::render('messages/index', [
            'initialMessages' => [],
            'users' => $dialogUsers,
            'selectedRecipient' => null,
        ]);
    }

    public function fetchMessages($recipientId, MessagesService $service) : \Inertia\Response
    {
        $user = auth()->user();
        $messages = $service->getUserMessages($user, $recipientId);
        $dialogUsers = $service->getDialogUsers($user);

        return Inertia::render('messages/index', [
            'initialMessages' => $messages,
            'users' => $dialogUsers,
            'selectedRecipient' => (int)$recipientId,
        ]);
    }

    public function store(Request $request,  MessagesService $service, NotificationService $notificationService) : RedirectResponse
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|max:255',
        ]);

        $message = $service->createMessage($request);
        $user = auth()->user();
        broadcast(new NewMessage($message, $request->recipient_id))->toOthers();
        $notificationService->createNotification($request->recipient_id, "Новое сообщение от " . $user->name);

        return redirect()->route('messages.index', ['recipientId' => $request->recipient_id])->with('success', 'Сообщение отправлено!');
    }
}
