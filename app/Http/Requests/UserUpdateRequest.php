<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191,unique:users,email',
            'phone' => 'nullable|phone:BY',
            'password' => 'nullable|min:6|max:50|confirmed',
            'banned_at' => 'nullable'
        ];
    }
}
