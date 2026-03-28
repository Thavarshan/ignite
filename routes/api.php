<?php

use App\Http\Controllers\Api\Admin\FlagController as AdminFlagController;
use App\Http\Controllers\Api\BestReplyController;
use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\Api\FlagController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReplyController;
use App\Http\Controllers\Api\ReplyLikeController;
use App\Http\Controllers\Api\ThreadController;
use App\Http\Controllers\Api\ThreadLikeController;
use App\Http\Controllers\Api\ThreadSubscriptionController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'throttle:api'])
    ->name('api.')
    ->group(function (): void {
        Route::apiResource('channels', ChannelController::class);
        Route::apiResource('threads', ThreadController::class)->except(['store']);
        Route::apiResource('threads.replies', ReplyController::class)->only(['index', 'update', 'destroy']);
        Route::post('threads/{thread}/best-reply/{reply}', [BestReplyController::class, 'store'])->name('threads.best-reply.store');
        Route::delete('threads/{thread}/best-reply', [BestReplyController::class, 'destroy'])->name('threads.best-reply.destroy');

        Route::middleware('throttle:create-content')->group(function (): void {
            Route::post('threads', [ThreadController::class, 'store'])->name('threads.store');
            Route::post('threads/{thread}/replies', [ReplyController::class, 'store'])->name('threads.replies.store');
            Route::post('threads/{thread}/flag', [FlagController::class, 'flagThread'])->name('threads.flag');
            Route::post('replies/{reply}/flag', [FlagController::class, 'flagReply'])->name('replies.flag');
        });

        Route::post('threads/{thread}/like', [ThreadLikeController::class, 'store'])->name('threads.like.store');
        Route::delete('threads/{thread}/like', [ThreadLikeController::class, 'destroy'])->name('threads.like.destroy');

        Route::post('replies/{reply}/like', [ReplyLikeController::class, 'store'])->name('replies.like.store');
        Route::delete('replies/{reply}/like', [ReplyLikeController::class, 'destroy'])->name('replies.like.destroy');

        Route::post('threads/{thread}/subscribe', [ThreadSubscriptionController::class, 'store'])->name('threads.subscribe.store');
        Route::delete('threads/{thread}/subscribe', [ThreadSubscriptionController::class, 'destroy'])->name('threads.subscribe.destroy');

        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
        Route::post('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

        Route::get('users/{user}', [UserProfileController::class, 'show'])->name('users.show');
        Route::get('users/{user}/threads', [UserProfileController::class, 'threads'])->name('users.threads');
        Route::get('users/{user}/replies', [UserProfileController::class, 'replies'])->name('users.replies');

        Route::post('threads/{thread}/restore', [ThreadController::class, 'restore'])->withTrashed()->name('threads.restore');
        Route::delete('threads/{thread}/force-delete', [ThreadController::class, 'forceDelete'])->withTrashed()->name('threads.force-delete');
        Route::post('threads/{thread}/replies/{reply}/restore', [ReplyController::class, 'restore'])->withTrashed()->name('threads.replies.restore');
        Route::delete('threads/{thread}/replies/{reply}/force-delete', [ReplyController::class, 'forceDelete'])->withTrashed()->name('threads.replies.force-delete');

        Route::middleware(\App\Http\Middleware\EnsureUserIsAdmin::class)->prefix('admin')->name('admin.')->group(function (): void {
            Route::get('flags', [AdminFlagController::class, 'index'])->name('flags.index');
            Route::post('flags/{flag}/resolve', [AdminFlagController::class, 'resolve'])->name('flags.resolve');
        });
    });
