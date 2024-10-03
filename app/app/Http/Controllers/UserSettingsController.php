<?php

namespace App\Http\Controllers;

use App\Models\ProfilePhoto;
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

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);
        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'errors' => ['current_password' => 'Текущий пароль неверен.']], 422);
        }
        $user->forceFill([
            'password' => Hash::make($request->new_password)
        ])->save();

        auth()->logout();
        return response()->json(['success' => true, 'message' => 'Пароль успешно изменен.']);
    }

}
