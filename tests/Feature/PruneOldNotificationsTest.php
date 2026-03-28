<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadReplied;

test('prune command deletes read notifications older than 30 days', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $user->notify(new ThreadReplied($thread, $reply));
    $notification = $user->notifications()->first();
    $notification->markAsRead();

    // Age the notification to 31 days
    $notification->update(['created_at' => now()->subDays(31)]);

    $this->artisan('notifications:prune')
        ->assertSuccessful();

    expect($user->notifications()->count())->toBe(0);
});

test('prune command keeps read notifications newer than 30 days', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $user->notify(new ThreadReplied($thread, $reply));
    $notification = $user->notifications()->first();
    $notification->markAsRead();

    // 29 days old — should be kept
    $notification->update(['created_at' => now()->subDays(29)]);

    $this->artisan('notifications:prune')
        ->assertSuccessful();

    expect($user->notifications()->count())->toBe(1);
});

test('prune command deletes unread notifications older than 90 days', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $user->notify(new ThreadReplied($thread, $reply));
    $notification = $user->notifications()->first();

    // Age the notification to 91 days
    $notification->update(['created_at' => now()->subDays(91)]);

    $this->artisan('notifications:prune')
        ->assertSuccessful();

    expect($user->notifications()->count())->toBe(0);
});

test('prune command keeps unread notifications newer than 90 days', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $user->notify(new ThreadReplied($thread, $reply));
    $notification = $user->notifications()->first();

    // 89 days old — should be kept
    $notification->update(['created_at' => now()->subDays(89)]);

    $this->artisan('notifications:prune')
        ->assertSuccessful();

    expect($user->notifications()->count())->toBe(1);
});
