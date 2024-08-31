<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('main');
})->name('home');

Route::get('/register', function () {
    return Inertia::render('register');
})->name('register');

Route::get('/login', function () {
    return Inertia::render('login');
})->name('login');

Route::get('/about', function () {
    return Inertia::render('about');
})->name('about');

Route::get('/blog', function () {
    return Inertia::render('blog');
})->name('blog');

Route::get('/contact', function () {
    return Inertia::render('contact');
})->name('contact');






