<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Full name is required'),
            'email.required' => __('Email address is required'),
            'email.email' => __('Please provide a valid email address'),
            'email.unique' => __('This email address is already registered'),
            'password.required' => __('Password is required'),
            'password.min' => __('Password must be at least 8 characters'),
            'role_id.required' => __('Please select a role for this user'),
            'role_id.exists' => __('Selected role is invalid'),
        ];
    }
}
