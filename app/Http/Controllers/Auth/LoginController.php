<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{


    use AuthenticatesUsers;


//    protected $redirectTo = '/';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        if ($user->role == "admin" || $user->role == "super-admin") {
            // Redirect admin or super admin to the dashboard
            return to_route('dashboard');
        }

        // Redirect regular users to the home page
        return to_route('landing');
    }
}
