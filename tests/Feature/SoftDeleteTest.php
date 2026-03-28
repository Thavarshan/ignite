<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('deleting a thread soft deletes it', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($user)->create();

    $this->actingAs($user)
        ->deleteJson(route('api.threads.destroy', $thread))
        ->assertSuccessful();

    expect(Thread::find($thread->id))->toBeNull()
        ->and(Thread::withTrashed()->find($thread->id))->not->toBeNull();
});

test('deleting a reply soft deletes it', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->deleteJson(route('api.threads.replies.destroy', [$thread, $reply]))
        ->assertSuccessful();

    expect(Reply::find($reply->id))->toBeNull()
        ->and(Reply::withTrashed()->find($reply->id))->not->toBeNull();
});

test('soft deleted threads are excluded from listings', function () {
    $user = User::factory()->create();
    $channel = Channel::factory()->create();
    $thread = Thread::factory()->for($channel)->for($user)->create();
    $thread->delete();

    $this->actingAs($user)
        ->getJson(route('api.threads.index'))
        ->assertSuccessful()
        ->assertJsonCount(0, 'data');
});

test('admin can restore a soft deleted thread', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->delete();

    $this->actingAs($admin)
        ->postJson(route('api.threads.restore', $thread))
        ->assertSuccessful();

    expect(Thread::find($thread->id))->not->toBeNull();
});

test('non-admin cannot restore a soft deleted thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($user)->create();
    $thread->delete();

    $this->actingAs($user)
        ->postJson(route('api.threads.restore', $thread))
        ->assertForbidden();
});

test('admin can force delete a thread', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $thread->delete();

    $this->actingAs($admin)
        ->deleteJson(route('api.threads.force-delete', $thread))
        ->assertNoContent();

    expect(Thread::withTrashed()->find($thread->id))->toBeNull();
});

test('admin can restore a soft deleted reply', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply->delete();

    $this->actingAs($admin)
        ->postJson(route('api.threads.replies.restore', [$thread, $reply]))
        ->assertSuccessful();

    expect(Reply::find($reply->id))->not->toBeNull();
});

test('admin can force delete a reply', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $reply->delete();

    $this->actingAs($admin)
        ->deleteJson(route('api.threads.replies.force-delete', [$thread, $reply]))
        ->assertNoContent();

    expect(Reply::withTrashed()->find($reply->id))->toBeNull();
});
