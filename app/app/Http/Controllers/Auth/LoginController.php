<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('login', [
            'error' => session()->get('error'),
            'success' => session()->get('success'),
        ]);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect()->intended(route('home'));
        }


        return Inertia::render('login', [
            'errors' => [
                'email' => 'Неверный логин или пароль.',
            ],
        ]);
    }
    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }
}
