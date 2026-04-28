<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{


    use AuthenticatesUsers;


   protected $redirectTo = '/';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        if ($user->role == "admin" || $user->role == "super-admin") {
            // Redirect admin and super-admin to website home
            return to_route('landing');
        }

        // Redirect regular users to website home
        return to_route('landing');
    }
}
