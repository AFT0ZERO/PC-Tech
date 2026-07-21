<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutocompleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query'       => ['required', 'string', 'min:1', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
