<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckBuildCompatibilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'part_ids'   => ['array'],
            'part_ids.*' => ['integer', 'exists:products,id'],
        ];
    }
}
