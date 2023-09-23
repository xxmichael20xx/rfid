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
        return view('auth.login');
    }
}
