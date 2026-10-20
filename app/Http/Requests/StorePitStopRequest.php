<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePitStopRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'stop_name' => 'required|string|max:255',
            'stop_km' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:5120',
            'audio' => 'nullable|file|max:10240',
        ];
    }
}
