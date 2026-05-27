<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\CustomerPortal;
use App\Livewire\QrScanner;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scan/{uuid?}', QrScanner::class)
    ->middleware('auth')
    ->name('scan');

Route::get('/track', CustomerPortal::class)
    ->middleware(SetLocale::class)
    ->name('track');
