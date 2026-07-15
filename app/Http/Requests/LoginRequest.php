<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['pin' => 'required|string|size:4'];
    }

    public function messages(): array
    {
        return ['pin.size' => __('messages.pin_four_digits')];
    }
}
