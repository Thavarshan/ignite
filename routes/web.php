<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get('/', function (): Response {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function (): Response {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('channels', ChannelController::class)->only(['show']);

Route::group([
    'prefix' => 'channels/{channel}',
], function (): void {
    Route::resource('threads', ThreadController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
