<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Define the login view
     *
     * @return view
     */
    public function login()
    {
        // Check if user is already authenticated
        if (auth()->check()) {
            return redirect('dashboard');
        }

        return view('auth.login');
    }
}
