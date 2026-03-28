<?php

use App\Http\Controllers\Web\Admin\FlagController as AdminFlagController;
use App\Http\Controllers\Web\BestReplyController;
use App\Http\Controllers\Web\ChannelController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ReplyController;
use App\Http\Controllers\Web\ThreadController;
use App\Http\Controllers\Web\UserProfileController;
use App\Http\Controllers\Web\WelcomeController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');

// Auth-protected routes first so /create doesn't match /{slug}
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('channels', ChannelController::class)
        ->except(['index', 'show']);
    Route::resource('threads', ThreadController::class)
        ->except(['index', 'show']);

    Route::post('threads/{thread}/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::put('threads/{thread}/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('threads/{thread}/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');

    Route::post('threads/{thread}/best-reply/{reply}', [BestReplyController::class, 'store'])->name('best-reply.store');
    Route::delete('threads/{thread}/best-reply', [BestReplyController::class, 'destroy'])->name('best-reply.destroy');

    Route::middleware(EnsureUserIsAdmin::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('flags', AdminFlagController::class)->name('flags.index');
    });
});

// Public browsing — guests can view threads and channels
Route::resource('channels', ChannelController::class)
    ->only(['index', 'show']);
Route::resource('threads', ThreadController::class)
    ->only(['index', 'show']);
Route::get('users/{user}', UserProfileController::class)->name('users.show');

require __DIR__.'/settings.php';
