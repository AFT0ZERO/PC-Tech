<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserManagementService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct(private UserManagementService $userManagementService)
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fname'    => ['required', 'min:3'],
            'lname'    => ['required', 'min:3'],
            'email'    => ['required', 'email'],
            'mobile'   => ['required', 'min:9', 'numeric', 'unique:users'],
            'gender'   => ['required'],
            'password' => ['required', 'min:5', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return $this->userManagementService->create([
            'fname'    => $data['fname'],
            'lname'    => $data['lname'],
            'email'    => $data['email'],
            'mobile'   => $data['mobile'],
            'gender'   => $data['gender'],
            'role'     => 'user',
            'password' => $data['password'],
        ]);
    }
}
