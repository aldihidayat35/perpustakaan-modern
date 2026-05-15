<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

class WhatsAppSettingsService
{
    public function getApiKey(): string
    {
        return (string) Setting::getValue('whatsapp_api_key', '');
    }

    public function getSender(): string
    {
        return (string) Setting::getValue('whatsapp_sender', '');
    }

    public function getSessionId(): string
    {
        return (string) Setting::getValue('whatsapp_session_id', '');
    }

    public function isActive(): bool
    {
        return (bool) Setting::getValue('whatsapp_is_active', false);
    }

    public function getReminderDays(): int
    {
        return (int) Setting::getValue('whatsapp_reminder_days', 1);
    }

    public function update(array $data): void
    {
        Setting::setValue('whatsapp_api_key', $data['api_key'] ?? '', 'text', 'whatsapp');
        Setting::setValue('whatsapp_sender', $data['sender'] ?? '', 'text', 'whatsapp');
        Setting::setValue('whatsapp_session_id', $data['session_id'] ?? '', 'text', 'whatsapp');
        Setting::setValue('whatsapp_is_active', (bool) ($data['is_active'] ?? false), 'boolean', 'whatsapp');
        Setting::setValue('whatsapp_reminder_days', (int) ($data['reminder_days'] ?? 1), 'number', 'whatsapp');
    }
}
