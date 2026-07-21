<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:150'],
            'notes'    => ['nullable', 'string'],
            'part_ids' => ['required', 'array', 'min:1'],
            'part_ids.*' => ['integer', 'exists:products,id'],
        ];
    }
}
