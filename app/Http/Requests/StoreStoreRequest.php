<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'min:3'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
        ];
    }
}
