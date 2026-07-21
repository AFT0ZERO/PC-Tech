<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Services\FormFieldResolver;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = Category::find($this->input('category'));

        if (!$category) {
            return ['category' => ['required', 'exists:categories,id']];
        }

        $resolver = app(FormFieldResolver::class);
        $fields = $resolver->resolve($category);

        $rules = ['category' => ['required', 'exists:categories,id']];

        foreach ($fields['product_fields'] as $field) {
            $rules[$field['name']] = $this->fieldValidation($field);
        }

        foreach ($fields['spec_fields'] as $field) {
            $rules[$field['name']] = $this->fieldValidation($field);
        }

        $rules['store_id']   = ['nullable', 'array'];
        $rules['store_id.*'] = ['nullable', 'exists:stores,id'];
        $rules['price']      = ['nullable', 'array'];
        $rules['url']        = ['nullable', 'array'];
        $rules['status']     = ['nullable', 'array'];
        $rules['price.*']    = ['nullable', 'numeric'];
        $rules['url.*']      = ['nullable', 'string'];
        $rules['status.*']   = ['nullable', 'string'];
        $rules['key']      = ['required', 'array'];
        $rules['value']    = ['required', 'array'];
        $rules['key.*']    = ['required', 'string'];
        $rules['value.*']  = ['required', 'string'];

        return $rules;
    }

    private function fieldValidation(array $field): array
    {
        $rule = $field['required'] ? ['required'] : ['nullable'];

        if ($field['type'] === 'number') {
            $rule[] = 'numeric';
        } elseif ($field['type'] === 'textarea') {
            $rule[] = 'string';
        } else {
            $rule[] = 'string';
            $rule[] = 'max:255';
        }

        return $rule;
    }
}
