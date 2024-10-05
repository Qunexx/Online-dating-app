<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('blog');
    }
}
