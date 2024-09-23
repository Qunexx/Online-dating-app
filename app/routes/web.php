<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PairSearchingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\AuthMiddleware;

Route::get('/',[MainController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.post');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.index');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware(AuthMiddleware::class);
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware(AuthMiddleware::class);
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware(AuthMiddleware::class);
Route::get('/profile/{user_id}', [ProfileController::class, 'show'])->name('profile.show')->middleware(AuthMiddleware::class);


Route::get('/messages', [MessageController::class, 'index'])->name('messages.index')->middleware(AuthMiddleware::class);
Route::get('/messages/{recipient}', [MessageController::class, 'fetchMessages'])->name('messages.fetchMessages')->middleware(AuthMiddleware::class);
Route::post('/messages', [MessageController::class, 'store'])->name('messages.post')->middleware(AuthMiddleware::class);



Route::get('/search-pair', [PairSearchingController::class, 'searchPair'])->name('search-pair')->middleware(AuthMiddleware::class);


Route::post('/profiles/{profile}/like', [PairSearchingController::class, 'like'])->name('profiles.like')->middleware(AuthMiddleware::class);
Route::post('/profiles/{profile}/dislike', [PairSearchingController::class, 'dislike'])->name('profiles.dislike')->middleware(AuthMiddleware::class);



Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications')->middleware(AuthMiddleware::class);
Route::post('/hide-notification/{notificationId}', [NotificationController::class, 'hideNotification'])->middleware(AuthMiddleware::class);

Route::get('/about', function () {
    return Inertia::render('about');
})->name('about');


Route::get('/blog', function () {
    return Inertia::render('blog');
})->name('blog')->middleware(AuthMiddleware::class);

Route::get('/contact', function () {
    return Inertia::render('contact');
})->name('contact');








