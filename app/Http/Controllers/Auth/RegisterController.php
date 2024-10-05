<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{


    use RegistersUsers;


    protected $redirectTo = '/home';


    public function __construct()
    {
        $this->middleware('guest');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fname'=>['required','min:3'],
            'lname'=>['required','min:3'],
            'email'=>['required','email'],
            'mobile'=>['required','min:9','numeric'],
            'gender'=>['required'],
            'password'=>['required','min:5'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'fname'=>$data['fname'],
            'lname'=>$data['lname'],
            'email'=>$data['email'],
            'mobile'=>$data['mobile'],
            'gender'=>$data['gender'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
