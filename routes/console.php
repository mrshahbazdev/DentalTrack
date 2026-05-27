<?php

use App\Services\DashboardCacheService;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    /** @var Command $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('predictions:generate --update-accuracy')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::call(function () {
    app(DashboardCacheService::class)->warmCaches();
})->name('warm-dashboard-caches')
    ->everyFiveMinutes()
    ->withoutOverlapping();
