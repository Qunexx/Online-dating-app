<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Http\Services\NotificationService;
use App\Models\Notification;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function getNotifications(): JsonResponse
    {
        $data = $this->service->getUserNotifications();

        return response()->json([
            'user' => $data['user'],
            'notifications' => $data['notifications'],
        ]);
    }

    public function hideNotification(int $notificationId): JsonResponse
    {
        $result = $this->service->hideNotification($notificationId);
        if ($result) {
            return response()->json(['message' => 'Уведомление скрыто']);
        } else {
            return response()->json(['message' => 'Ошибка при сокрытии уведомления']);
        }


    }
}


