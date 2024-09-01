<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if (!auth()->check()) {
            return redirect(route('login.index'))->with('error', 'Вы должны быть авторизованы для доступа к этой странице.');
        }

        return $next($request);
    }
}
