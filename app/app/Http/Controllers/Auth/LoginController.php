<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function showLoginForm() : Response | RedirectResponse
    {
        if(auth()->check()){
            return redirect()->intended(route('profile.index'));
        }
        return Inertia::render('login', [
            'error' => session()->get('error'),
            'success' => session()->get('success'),
        ]);

    }

    public function login(Request $request) : Response | RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if ($request->remember) {
            $remember = true;
        } else{
            $remember = false;
        }
        if (auth()->attempt($credentials, $remember)) {
            if (auth()->viaRemember()) {
                return redirect()->intended(route('profile.index'))->with('success', 'Вы успешно авторизовались.".');
            } else {
                return redirect()->intended(route('profile.index'))->with('success', 'Вы успешно авторизовались.');
            }
        }


        return Inertia::render('login', [
            'errors' => [
                'email' => 'Неверный логин или пароль.',
            ],
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Вы успешно вышли из аккаунта');
    }
}
