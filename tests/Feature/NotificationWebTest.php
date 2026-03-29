<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadReplied;

test('authenticated user can mark a notification as read via web', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $user->notify(new ThreadReplied($thread, $reply));

    $notification = $user->notifications()->first();

    $this->actingAs($user)
        ->post(route('notifications.read', $notification->id))
        ->assertRedirect();

    expect($notification->fresh()->read_at)->not->toBeNull();
});

test('authenticated user can mark all notifications as read via web', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create();
    $reply1 = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply2 = Reply::factory()->for($thread)->for(User::factory())->create();

    $user->notify(new ThreadReplied($thread, $reply1));
    $user->notify(new ThreadReplied($thread, $reply2));

    $this->actingAs($user)
        ->post(route('notifications.read-all'))
        ->assertRedirect();

    expect($user->unreadNotifications()->count())->toBe(0);
});

test('authenticated user can delete a notification via web', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $user->notify(new ThreadReplied($thread, $reply));

    $notification = $user->notifications()->first();

    $this->actingAs($user)
        ->delete(route('notifications.destroy', $notification->id))
        ->assertRedirect();

    expect($user->notifications()->count())->toBe(0);
});

test('guest cannot access web notification routes', function () {
    $this->post(route('notifications.read', 'fake-id'))
        ->assertRedirect(route('login'));

    $this->post(route('notifications.read-all'))
        ->assertRedirect(route('login'));

    $this->delete(route('notifications.destroy', 'fake-id'))
        ->assertRedirect(route('login'));
});

test('user cannot mark another user notification as read', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($other)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $other->notify(new ThreadReplied($thread, $reply));

    $notification = $other->notifications()->first();

    $this->actingAs($user)
        ->post(route('notifications.read', $notification->id))
        ->assertNotFound();
});
