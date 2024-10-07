<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChannelController;

Route::get('/', function () {
    return Inertia::render('index');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('dashboard/index');
    })->name('dashboard');

    Route::resource('channels', ChannelController::class);
});
