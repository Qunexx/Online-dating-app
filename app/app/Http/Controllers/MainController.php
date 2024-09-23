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

class MainController extends Controller
{
    public function index(MessagesService $service) : \Inertia\Response
    {
        $user = auth()->user();
        $user ? $user : null;
        return Inertia::render('main', [
            'user' => $user,
        ]);
    }

}
