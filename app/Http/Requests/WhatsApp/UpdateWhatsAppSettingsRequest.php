<?php

declare(strict_types=1);

namespace App\Http\Requests\WhatsApp;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWhatsAppSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_key' => ['nullable', 'string'],
            'sender' => ['nullable', 'string'],
            'session_id' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'reminder_days' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
