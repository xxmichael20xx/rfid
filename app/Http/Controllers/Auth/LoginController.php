<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        switch (auth()->user()->role) {
            case 'Admin':
                $redirect = RouteServiceProvider::DASHBOARD;
                break;

            case 'Guard':
                $redirect = RouteServiceProvider::GUARD;
                break;

            case 'Treasurer':
                $redirect = RouteServiceProvider::TREASURER;
                break;

            default:
                $redirect = 'login';
                auth()->logout();
                break;
        }

        return $redirect;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
