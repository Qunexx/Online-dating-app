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
    public function showLoginForm() : Response
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
            return redirect()->intended(route('home'))->with('success','Вы успешно авторизовались');
        }


        return Inertia::render('login', [
            'errors' => [
                'email' => 'Неверный логин или пароль.',
            ],
        ]);
    }
    public function logout() : RedirectResponse
    {
        auth()->logout();
        return redirect('/login');
    }
}
