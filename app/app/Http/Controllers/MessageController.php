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
    protected $notificationsService;
    protected $messagesService;

    public function __construct(MessagesService $messagesService, NotificationService $notificationService)
    {
        $this->messagesService = $messagesService;
        $this->notificationsService = $notificationService;
    }

    public function index(): \Inertia\Response
    {
        $user = auth()->user();
        $dialogUsers = $this->messagesService->getDialogUsers($user);
        return Inertia::render('messages/index', [
            'initialMessages' => [],
            'users' => $dialogUsers,
            'selectedRecipient' => null,
        ]);
    }

    public function fetchMessages(int $recipientId): \Inertia\Response
    {
        $user = auth()->user();
        $messages = $this->messagesService->getUserMessages($user, $recipientId);
        $dialogUsers = $this->messagesService->getDialogUsers($user);

        return Inertia::render('messages/index', [
            'initialMessages' => $messages,
            'users' => $dialogUsers,
            'selectedRecipient' => (int)$recipientId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|max:255',
        ]);

        $message = $this->messagesService->createMessage($request);
        $user = auth()->user();
        broadcast(new NewMessage($message, $request->recipient_id))->toOthers();
        $this->notificationsService->createNotification($request->recipient_id, $user->id, "Новое сообщение от " . $user->name);

        return redirect()->route('messages.index', ['recipientId' => $request->recipient_id])->with('success', 'Сообщение отправлено!');
    }
}
