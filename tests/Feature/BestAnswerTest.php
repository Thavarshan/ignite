<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('thread owner can mark a reply as best answer', function () {
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($owner)
        ->post(route('best-reply.store', [$thread, $reply]))
        ->assertRedirect(route('threads.show', $thread, absolute: false));

    expect($thread->fresh()->best_reply_id)->toBe($reply->id);
});

test('thread owner can unmark the best answer', function () {
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $thread->markBestReply($reply);

    $this->actingAs($owner)
        ->delete(route('best-reply.destroy', $thread))
        ->assertRedirect(route('threads.show', $thread, absolute: false));

    expect($thread->fresh()->best_reply_id)->toBeNull();
});

test('admin can mark a reply as best answer on any thread', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($admin)
        ->post(route('best-reply.store', [$thread, $reply]))
        ->assertRedirect();

    expect($thread->fresh()->best_reply_id)->toBe($reply->id);
});

test('non-owner cannot mark a best answer', function () {
    $other = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($other)
        ->post(route('best-reply.store', [$thread, $reply]))
        ->assertForbidden();
});

test('non-owner cannot unmark the best answer', function () {
    $other = User::factory()->create();
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $thread->markBestReply($reply);

    $this->actingAs($other)
        ->delete(route('best-reply.destroy', $thread))
        ->assertForbidden();
});

test('marking replaces an existing best answer', function () {
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $first = Reply::factory()->for($thread)->for(User::factory())->create();
    $second = Reply::factory()->for($thread)->for(User::factory())->create();
    $thread->markBestReply($first);

    $this->actingAs($owner)
        ->post(route('best-reply.store', [$thread, $second]))
        ->assertRedirect();

    expect($thread->fresh()->best_reply_id)->toBe($second->id);
});

test('reply from another thread cannot be marked as best answer', function () {
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $otherThread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $foreignReply = Reply::factory()->for($otherThread)->for(User::factory())->create();

    $this->actingAs($owner)
        ->post(route('best-reply.store', [$thread, $foreignReply]))
        ->assertStatus(422);
});

test('guest cannot mark a best answer', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->post(route('best-reply.store', [$thread, $reply]))
        ->assertRedirect(route('login'));
});

// --- API tests ---

test('thread owner can mark best answer via api', function () {
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($owner)
        ->postJson(route('api.threads.best-reply.store', [$thread, $reply]))
        ->assertSuccessful()
        ->assertJsonPath('data.best_reply_id', $reply->id);
});

test('thread owner can unmark best answer via api', function () {
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();
    $thread->markBestReply($reply);

    $this->actingAs($owner)
        ->deleteJson(route('api.threads.best-reply.destroy', $thread))
        ->assertSuccessful()
        ->assertJsonPath('data.best_reply_id', null);
});

test('non-owner cannot mark best answer via api', function () {
    $other = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for(User::factory())->create();

    $this->actingAs($other)
        ->postJson(route('api.threads.best-reply.store', [$thread, $reply]))
        ->assertForbidden();
});

test('reply from another thread returns 422 via api', function () {
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for($owner)->create();
    $otherThread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $foreignReply = Reply::factory()->for($otherThread)->for(User::factory())->create();

    $this->actingAs($owner)
        ->postJson(route('api.threads.best-reply.store', [$thread, $foreignReply]))
        ->assertStatus(422);
});
