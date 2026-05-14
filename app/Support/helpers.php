<?php

declare(strict_types=1);

use App\Models\Setting;

if (!function_exists('app_setting')) {
    function app_setting(string $key, mixed $default = null): mixed
    {
        return Setting::getValue($key, $default);
    }
}
