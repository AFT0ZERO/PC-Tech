<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message'    => ['required', 'string'],
            'rate'       => ['required', 'integer', 'min:1', 'max:5'],
            'product_id' => ['required', 'exists:products,id'],
            'user_id'    => ['required', 'exists:users,id'],
        ];
    }
}
