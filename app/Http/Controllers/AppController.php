<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class AppController extends Controller
{
    public function index()
    {
        return Inertia::render('App');
    }
}
