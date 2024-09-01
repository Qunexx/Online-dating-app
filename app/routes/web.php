<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\AuthMiddleware;

Route::get('/', function () {
    return Inertia::render('main');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.post');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.index');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


Route::get('/about', function () {
    return Inertia::render('about');
})->name('about');


Route::get('/blog', function () {
    return Inertia::render('blog');
})->name('blog')->middleware(AuthMiddleware::class);

Route::get('/contact', function () {
    return Inertia::render('contact');
})->name('contact');








