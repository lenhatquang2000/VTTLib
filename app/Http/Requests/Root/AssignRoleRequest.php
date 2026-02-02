<?php

namespace App\Http\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user',
            'user_id.exists' => 'Selected user does not exist',
            'role_id.required' => 'Please select a role',
            'role_id.exists' => 'Selected role does not exist',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = \App\Models\User::find($this->user_id);
            $role = \App\Models\Role::find($this->role_id);
            
            if ($user && $role) {
                // Check if user already has this role
                if ($user->roles()->where('role_id', $this->role_id)->exists()) {
                    $validator->errors()->add('role_id', 'User already has this role assigned');
                }
            }
        });
    }
}
