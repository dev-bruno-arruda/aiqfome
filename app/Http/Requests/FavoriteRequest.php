<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => __('messages.validation.required', ['field' => 'ID do produto']),
            'product_id.integer' => __('messages.validation.integer', ['field' => 'ID do produto']),
            'product_id.min' => __('messages.validation.min', ['field' => 'ID do produto', 'min' => 1]),
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id' => 'ID do produto',
        ];
    }
}
