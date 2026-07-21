<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['required'],
            'brand'          => ['required'],
            'category'       => ['required', 'exists:categories,id'],
            'key.*'          => ['required', 'string'],
            'value.*'        => ['required', 'string'],
            'price.*.*'      => ['nullable', 'numeric'],
            'url.*.*'        => ['nullable', 'string'],
            'new_store_id.*' => ['nullable', 'exists:stores,id'],
            'new_price.*'    => ['nullable', 'numeric'],
            'new_url.*'      => ['nullable', 'string'],
            'new_status.*'   => ['nullable', 'string'],
        ];
    }
}
