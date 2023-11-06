<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    /**
     * Authenticate the home owner
     */
    public function login(Request $request)
    {
        // validate the form
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        // check if the email and password is valid
        if (Auth::attempt($request->only('email', 'password'))) {
            // create a authorization token
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            // send a response to the application
            return response()->json([
                'status' => true,
                'message' => 'Successfully logged in!',
                'token' => $token
            ]);
        }

        // send a response with a invalid credentials
        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 422);
    }
}
