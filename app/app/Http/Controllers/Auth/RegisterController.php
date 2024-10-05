<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\AuthService;
use App\Models\Profile;
use App\Models\User;
use http\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use PhpParser\Node\Expr\Cast\Object_;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegistrationForm(): Response|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->intended(route('profile.index'));
        }
        return Inertia::render('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $result = $this->authService->registerUser($validated_data);
        if ($result) {
            return redirect('/login')->with('success', 'Вы успешно зарегистрированы! Пожалуйста авторизуйтесь, чтобы начать пользоваться сайтом');
        } else {
            return redirect('/login')->with('error', 'Что-то пошло не так, попробуйте снова');
        }

    }
}
