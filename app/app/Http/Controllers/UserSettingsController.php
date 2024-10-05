<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\ProfilePhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use App\Models\User;
use App\Models\Profile;


class UserSettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('userSettings');
    }

    public function changePassword(Request $request, UserService $service): JsonResponse
    {
        $result = $service->changePassword($request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]));

        if ($result) {
            return response()->json(['success' => true, 'message' => 'Пароль успешно изменен.']);
        } else {
            return response()->json(['success' => false, 'errors' => ['current_password' => 'Текущий пароль неверен.']], 422);
        }
    }

}
