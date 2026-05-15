<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\WhatsAppLog;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    private const BASE_URL = 'https://jokiin35.space';

    public function __construct(private readonly WhatsAppSettingsService $settings) {}

    /**
     * Kirim pesan WhatsApp via API Jokiin35.space
     *
     * Payload: { to, message, session_id }
     * Header:  X-Api-Key: <api_key>
     */
    public function sendMessage(?Member $member, string $phone, string $message): bool
    {
        if (! $this->settings->isActive()) {
            $this->logFailure($member, $phone, $message, 'WhatsApp API tidak aktif');

            return false;
        }

        $apiKey = $this->settings->getApiKey();
        if ($apiKey === '') {
            $this->logFailure($member, $phone, $message, 'X-Api-Key belum diatur');

            return false;
        }

        $sessionId = $this->settings->getSessionId();
        if ($sessionId === '') {
            $this->logFailure($member, $phone, $message, 'Session ID belum diatur');

            return false;
        }

        $normalized = $this->normalizePhone($phone);
        if ($normalized === '') {
            $this->logFailure($member, $phone, $message, 'Nomor tidak valid');

            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-Api-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post(self::BASE_URL.'/api/wa/send', [
                'to' => $normalized,
                'message' => $message,
                'session_id' => $sessionId,
            ]);
        } catch (ConnectionException $e) {
            $this->logFailure($member, $normalized, $message, 'Timeout / tidak bisa terhubung ke server WA: '.$e->getMessage());

            return false;
        }

        if ($response->successful()) {
            $this->logSuccess($member, $normalized, $message);

            return true;
        }

        $this->logFailure($member, $normalized, $message, (string) $response->body());

        return false;
    }

    /**
     * Normalisasi nomor telepon ke format internasional (62...)
     */
    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?? '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        return '62'.$digits;
    }

    private function logSuccess(?Member $member, string $phone, string $message): void
    {
        WhatsAppLog::create([
            'member_id' => $member?->id,
            'phone' => $phone,
            'message' => $message,
            'status' => 'sent',
            'provider' => 'jokiin35',
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
            'provider' => 'jokiin35',
            'error_message' => $error,
        ]);
    }
}
