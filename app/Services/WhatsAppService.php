<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\WhatsAppLog;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function __construct(private readonly WhatsAppSettingsService $settings)
    {
    }

    public function sendMessage(?Member $member, string $phone, string $message): bool
    {
        if (!$this->settings->isActive()) {
            return false;
        }

        if ($this->settings->getApiKey() === '') {
            $this->logFailure($member, $phone, $message, 'API key belum diatur');
            return false;
        }

        $normalized = $this->normalizePhone($phone);
        if ($normalized === '') {
            $this->logFailure($member, $phone, $message, 'Nomor tidak valid');
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => $this->settings->getApiKey(),
        ])->post('https://api.fonnte.com/send', [
            'target' => $normalized,
            'message' => $message,
            'sender' => $this->settings->getSender(),
        ]);

        if ($response->successful()) {
            $this->logSuccess($member, $normalized, $message);
            return true;
        }

        $this->logFailure($member, $normalized, $message, $response->body());
        return false;
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?? '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        return '62' . $digits;
    }

    private function logSuccess(?Member $member, string $phone, string $message): void
    {
        WhatsAppLog::create([
            'member_id' => $member?->id,
            'phone' => $phone,
            'message' => $message,
            'status' => 'sent',
            'provider' => 'fonnte',
            'sent_at' => now(),
        ]);
    }

    private function logFailure(?Member $member, string $phone, string $message, string $error): void
    {
        WhatsAppLog::create([
            'member_id' => $member?->id,
            'phone' => $phone,
            'message' => $message,
            'status' => 'failed',
            'provider' => 'fonnte',
            'error_message' => $error,
        ]);
    }
}
