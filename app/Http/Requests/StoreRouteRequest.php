<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'total_km' => 'nullable|numeric|min:0',
            'boc' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'audio' => 'nullable|file|max:10240',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }
}
