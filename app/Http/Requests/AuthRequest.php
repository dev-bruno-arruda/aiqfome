<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('messages.validation.required', ['field' => 'email']),
            'email.email' => __('messages.validation.email', ['field' => 'email']),
            'password.required' => __('messages.validation.password', ['field' => 'password']),
        ];
    }
}
