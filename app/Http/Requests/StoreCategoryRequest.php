<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'min:3'],
            'image'        => [$this->isMethod('put') ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg'],
            'specs_table'  => ['nullable', 'string', 'max:255'],
            'open_db_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
