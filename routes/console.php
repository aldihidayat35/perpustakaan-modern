<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler - auto send reminders
Schedule::command('reminder:send --days=3')->dailyAt('08:00'); // 3 hari sebelum jatuh tempo
Schedule::command('reminder:send --days=1')->dailyAt('08:30'); // 1 hari sebelum jatuh tempo
Schedule::command('reminder:overdue')->dailyAt('09:00'); // reminder untuk yang telat