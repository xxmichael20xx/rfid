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
            return match (auth()->user()->role) {
                'Admin' => redirect()->route('dashboard'),
                'Guard' => redirect()->route('guard.rfid-monitoring.index'),
                default => redirect()->route('payments.expenses')
            };
        }

        return view('auth.login');
    }
}
