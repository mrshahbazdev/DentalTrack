<?php

use App\Livewire\QrScanner;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scan/{uuid?}', QrScanner::class)
    ->middleware('auth')
    ->name('scan');
