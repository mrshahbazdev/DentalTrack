<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\CustomerPortal;
use App\Livewire\QrScanner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware(SetLocale::class);

Route::get('/scan/{uuid?}', QrScanner::class)
    ->middleware([SetLocale::class, 'auth', 'throttle:scan'])
    ->name('scan');

Route::get('/track', CustomerPortal::class)
    ->middleware([SetLocale::class, 'throttle:tracking'])
    ->name('track');

Route::get('/health', function () {
    $checks = [
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'database' => false,
        'cache' => false,
    ];

    try {
        DB::connection()->getPdo();
        $checks['database'] = true;
    } catch (Throwable) {
        $checks['status'] = 'degraded';
    }

    try {
        Cache::put('health_check', true, 10);
        $checks['cache'] = (bool) Cache::get('health_check');
    } catch (Throwable) {
        $checks['status'] = 'degraded';
    }

    $httpStatus = $checks['status'] === 'ok' ? 200 : 503;

    return response()->json($checks, $httpStatus);
})->name('health');
