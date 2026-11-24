<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup database harian. Butuh cron: * * * * * php /path/ke/proyek/artisan schedule:run
Schedule::command('backup:database')->dailyAt('02:00');
