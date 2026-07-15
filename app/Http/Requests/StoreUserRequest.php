<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'pin' => 'required|string|size:4|unique:users,pin',
            'role' => 'required|in:admin,saleshead,deliveryboy',
        ];
    }

    public function messages(): array
    {
        return [
            'pin.unique' => __('messages.pin_taken'),
            'pin.size' => __('messages.pin_four_digits'),
        ];
    }
}
