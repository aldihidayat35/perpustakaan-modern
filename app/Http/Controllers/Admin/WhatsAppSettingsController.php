<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsApp\TestWhatsAppRequest;
use App\Http\Requests\WhatsApp\UpdateWhatsAppSettingsRequest;
use App\Services\WhatsAppService;
use App\Services\WhatsAppSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppSettingsController extends Controller
{
    public function index(WhatsAppSettingsService $settings): View
    {
        return view('admin.settings.whatsapp', [
            'settings' => [
                'api_key' => $settings->getApiKey(),
                'sender' => $settings->getSender(),
                'is_active' => $settings->isActive(),
                'reminder_days' => $settings->getReminderDays(),
            ],
        ]);
    }

    public function update(UpdateWhatsAppSettingsRequest $request, WhatsAppSettingsService $settings): RedirectResponse
    {
        $settings->update($request->validated());

        return redirect()->route('admin.settings.whatsapp')->with('success', 'Pengaturan WhatsApp diperbarui.');
    }

    public function test(TestWhatsAppRequest $request, WhatsAppService $whatsApp): RedirectResponse
    {
        $sent = $whatsApp->sendMessage(null, $request->input('phone'), $request->input('message'));

        return redirect()->route('admin.settings.whatsapp')
            ->with($sent ? 'success' : 'error', $sent ? 'Pesan test terkirim.' : 'Gagal mengirim pesan test.');
    }
}
