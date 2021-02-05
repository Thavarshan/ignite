<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ChannelController;

Route::get('/', fn () => Inertia::render('Marketing/Welcome'))->name('welcome');

Route::middleware(['auth:sanctum', 'verified'])->get(
    '/feed',
    fn () => Inertia::render('Feed/Show')
)->name('home');

Route::group([
    'middleware' => ['auth'],
], function (): void {
    /*
     * Channels Routes....
     */
    Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');
    Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');
    Route::put('/channels/{channel}', [ChannelController::class, 'update'])->name('channels.update');
    Route::delete('/channels/{channel}', [ChannelController::class, 'destroy'])->name('channels.destroy');

    /*
     * Threads Routes....
     */
    Route::get('/{channel}/threads', [ThreadController::class, 'index'])->name('threads.index');
    Route::get('/{channel}/threads/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/{channel}/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/{channel}/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');
    Route::put('/{channel}/threads/{thread}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('/{channel}/threads/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');

    /*
     * Replies Routes...
     */
    Route::post('/{thread}/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::put('/{thread}/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/{thread}/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');
});
