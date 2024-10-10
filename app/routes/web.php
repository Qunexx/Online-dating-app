<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RestorePasswordController;
use App\Http\Controllers\BanController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PairSearchingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Middleware\AdminRoleMiddleware;
use App\Http\Middleware\BanMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\AuthMiddleware;

Route::get('/', [MainController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.post');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.index');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/restore-password', [RestorePasswordController::class, 'showRestoreForm'])->name('restore.index');
Route::post('/restore-password', [RestorePasswordController::class, 'sendResetLinkEmail'])->name('restore.post');

Route::get('/reset-password/{token}/{email}', [RestorePasswordController::class, 'showChangePasswordForm'])->name('password.reset');
Route::post('/reset-password', [RestorePasswordController::class, 'resetPassword'])->name('password.update');

Route::get('/about',[AboutController::class, 'index'])->name('about');
Route::get('/blog',[BlogController::class, 'index'])->name('blog');
Route::get('/contact',[ContactController::class, 'index'])->name('contact');
Route::post('/contact',[ContactController::class, 'create'])->name('contact.create');
Route::get('/banned',[BanController::class, 'index'])->name('banned');

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::prefix('settings')->group(function () {
        Route::get('/', [UserSettingsController::class, 'index'])->name('settings.index');
        Route::post('/change-password', [UserSettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications');
    Route::post('/hide-notification/{notificationId}', [NotificationController::class, 'hideNotification']);

    Route::middleware([BanMiddleware::class])->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
            Route::get('/{user_id}', [ProfileController::class, 'show'])->name('profile.show');
        });

        Route::prefix('messages')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->name('messages.index');
            Route::get('/{recipient}', [MessageController::class, 'fetchMessages'])->name('messages.fetchMessages');
            Route::post('/', [MessageController::class, 'store'])->name('messages.post');
        });

        Route::get('/search-pair', [PairSearchingController::class, 'searchPair'])->name('search-pair');

        Route::post('/profiles/{profile}/like', [PairSearchingController::class, 'like'])->name('profiles.like');
        Route::post('/profiles/{profile}/dislike', [PairSearchingController::class, 'dislike'])->name('profiles.dislike');




    });
});



Route::prefix('admin')->middleware(AdminRoleMiddleware::class)->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/user/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/user/ban/{id}', [AdminController::class, 'banUser'])->name('admin.users.ban');
    Route::get('/questions', [AdminController::class, 'showQuestions'])->name('admin.questions.list');
    Route::post('/question/{id}/process', [AdminController::class, 'processQuestion'])->name('admin.questions.process');
    Route::post('/close-session/{sessionId}', [AdminController::class, 'closeUserSession'])->name('admin.session.close');
});


