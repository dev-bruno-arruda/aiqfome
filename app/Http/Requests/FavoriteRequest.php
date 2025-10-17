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
            'product_id.required' => 'O ID do produto é obrigatório.',
            'product_id.integer' => 'O ID do produto deve ser um número inteiro.',
            'product_id.min' => 'O ID do produto deve ser maior que zero.',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id' => 'ID do produto',
        ];
    }
}
