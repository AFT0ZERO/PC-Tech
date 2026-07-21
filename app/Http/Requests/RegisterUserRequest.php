<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fname'    => ['required', 'min:3'],
            'lname'    => ['required', 'min:3'],
            'email'    => ['required', 'email'],
            'mobile'   => ['required', 'min:9', 'numeric', 'unique:users'],
            'gender'   => ['required'],
            'password' => ['required', 'min:5', 'confirmed'],
        ];
    }
}
