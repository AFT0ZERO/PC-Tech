<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'min:3'],
            'email'   => ['required', 'email'],
            'mobile'  => ['required', 'min:9', 'numeric'],
            'message' => ['required', 'min:10', 'max:5000'],
        ];
    }
}
