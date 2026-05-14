<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::query()
            ->orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $settings = Setting::query()->get()->keyBy('key');

        foreach ($settings as $key => $setting) {
            $value = $request->input("settings.{$key}");

            if ($setting->type === 'image') {
                if ($request->hasFile("settings.{$key}")) {
                    $file = $request->file("settings.{$key}");
                    $path = $file->store('settings', 'public');

                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    Setting::setValue($key, $path, $setting->type, $setting->group);
                }

                continue;
            }

            if ($setting->type === 'boolean') {
                $value = (bool) $value;
            }

            if ($value !== null) {
                Setting::setValue($key, $value, $setting->type, $setting->group);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
