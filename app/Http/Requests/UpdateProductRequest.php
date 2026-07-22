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
        $category = \App\Models\Category::find($this->input('category'));

        $rules = [
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['required'],
            'brand'          => ['required'],
            'category'       => ['required', 'exists:categories,id'],
            'key.*'          => ['nullable', 'string'],
            'value.*'        => ['nullable', 'string'],
            'price.*.*'      => ['nullable', 'numeric'],
            'url.*.*'        => ['nullable', 'string'],
            'new_store_id.*' => ['nullable', 'exists:stores,id'],
            'new_price.*'    => ['nullable', 'numeric'],
            'new_url.*'      => ['nullable', 'string'],
            'new_status.*'   => ['nullable', 'string'],
        ];

        if ($category && $category->specs_table) {
            $resolver = app(\App\Services\FormFieldResolver::class);
            $fields = $resolver->resolve($category);
            foreach ($fields['spec_fields'] as $field) {
                $rule = ['required'];
                if ($field['type'] === 'number') {
                    $rule[] = 'numeric';
                } else {
                    $rule[] = 'string';
                    $rule[] = 'max:255';
                }
                $rules[$field['name']] = $rule;
            }
        }

        return $rules;
    }
}
