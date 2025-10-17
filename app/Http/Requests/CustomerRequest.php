<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customerId)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.validation.required', ['field' => 'nome']),
            'name.string' => __('messages.validation.string', ['field' => 'nome']),
            'name.max' => __('messages.validation.max', ['field' => 'nome', 'max' => 255]),
            'name.min' => __('messages.validation.min', ['field' => 'nome', 'min' => 2]),
            'email.required' => __('messages.validation.required', ['field' => 'e-mail']),
            'email.email' => __('messages.validation.email', ['field' => 'e-mail']),
            'email.max' => __('messages.validation.max', ['field' => 'e-mail', 'max' => 255]),
            'email.unique' => __('messages.validation.unique', ['field' => 'e-mail']),
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                ApiResponse::validationError($validator->errors()->toArray(), 'validation.failed')
            );
        }

        parent::failedValidation($validator);
    }
}
