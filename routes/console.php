<?php

use App\Console\Commands\CancelExpiredBookings;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-cancel pending bookings yang belum dibayar lebih dari 2 jam
Schedule::command(CancelExpiredBookings::class)->hourly();
