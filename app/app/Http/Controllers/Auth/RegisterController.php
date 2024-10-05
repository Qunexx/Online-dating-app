<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    public function showRegistrationForm() : Response | RedirectResponse
    {
        if(auth()->check()){
            return redirect()->intended(route('profile.index'));
        }
        return Inertia::render('register');
    }

    public function register(Request $request) : RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

       $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->profile()->create([
            'bio' => '',
            'gender' => '',
            'birthdate' => null,
            'location' => '',
            'interests' => '',
        ]);

        return redirect('/login')->with('success', 'Вы успешно зарегистрированы! Пожалуйста авторизуйтесь, чтобы начать пользоваться сайтом');
    }
}
