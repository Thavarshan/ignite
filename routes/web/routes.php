<?php

use App\Http\Controllers\Web\ChannelController;
use App\Http\Controllers\Web\ReplyController;
use App\Http\Controllers\Web\ThreadController;
use App\Http\Controllers\Web\ThreadReplyController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get('/', [ThreadController::class, 'index'])->name('home');

Route::get('channels/{channel}', [ChannelController::class, 'show'])
    ->name('channels.show');

Route::get('dashboard', function (): Response {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('threads', ThreadController::class);
Route::resource('replies', ReplyController::class)->only(['destroy', 'update']);
Route::post('threads/{thread}/replies', [ThreadReplyController::class, 'store'])
    ->middleware(['auth'])
    ->name('threads.replies.store');

Route::group([
    'as' => 'channels.',
    'prefix' => 'channels/{channel}',
], function (): void {
    Route::resource('threads', ThreadController::class);
});

require __DIR__.'/../shared/settings.php';
require __DIR__.'/../shared/auth.php';
