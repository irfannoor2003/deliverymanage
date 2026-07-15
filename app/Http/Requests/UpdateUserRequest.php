<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'pin' => 'nullable|string|size:4',
            'role' => 'required|in:admin,saleshead,deliveryboy',
        ];
    }

    public function messages(): array
    {
        return ['pin.size' => __('messages.pin_four_digits')];
    }
}
