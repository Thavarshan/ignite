<?php

use App\Http\Controllers\ReplyController;
use App\Http\Controllers\Web\ChannelController;
use App\Http\Controllers\Web\ThreadController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::resource('channels.threads', ThreadController::class)
        ->except(['edit'])
        ->scoped();
    Route::resource('channels.threads.replies', ReplyController::class)
        ->only(['update', 'destroy', 'store'])
        ->scoped();
});
