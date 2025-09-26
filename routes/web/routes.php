<?php

use App\Http\Controllers\Web\ThreadController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get('/', function (): Response {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function (): Response {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group([
    'prefix' => 'channels/{channel}',
], function (): void {
    Route::resource('threads', ThreadController::class);
});

require __DIR__.'/../shared/settings.php';
require __DIR__.'/../shared/auth.php';
