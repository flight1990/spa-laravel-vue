<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ];
    }
}
