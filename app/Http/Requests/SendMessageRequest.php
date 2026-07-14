<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'content' => 'nullable|string|max:2000',
            'audio' => 'nullable|file|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'content.max' => 'Message must not exceed 2000 characters',
            'audio.max' => 'Audio must not exceed 10MB',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->input('content') && !$this->hasFile('audio')) {
                $validator->errors()->add('content', 'Please provide text or an audio message');
            }
        });
    }
}
