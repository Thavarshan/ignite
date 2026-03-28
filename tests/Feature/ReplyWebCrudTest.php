<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('authenticated users can post a reply to a thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('replies.store', $thread), ['body' => 'This is my reply.'])
        ->assertRedirect(route('threads.show', $thread, absolute: false));

    expect(Reply::query()->count())->toBe(1);
    expect(Reply::query()->first()->body)->toBe('This is my reply.');
    expect(Reply::query()->first()->user_id)->toBe($user->id);
    expect(Reply::query()->first()->thread_id)->toBe($thread->id);
});

test('authenticated users can post a nested reply (max depth 1)', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->post(route('replies.store', $thread), [
            'body' => 'Nested reply.',
            'parent_id' => $parent->id,
        ])
        ->assertRedirect(route('threads.show', $thread, absolute: false));

    $child = Reply::query()->where('parent_id', $parent->id)->first();
    expect($child)->not->toBeNull();
    expect($child->parent_id)->toBe($parent->id);
});

test('replies cannot be nested beyond depth 1', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();
    $child = Reply::factory()->asChild($parent)->for($user)->create();

    $this->actingAs($user)
        ->post(route('replies.store', $thread), [
            'body' => 'Too deep.',
            'parent_id' => $child->id,
        ])
        ->assertSessionHasErrors('parent_id');
});

test('reply owner can update their reply', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create(['body' => 'Original body.']);

    $this->actingAs($user)
        ->put(route('replies.update', [$thread, $reply]), ['body' => 'Updated body.'])
        ->assertRedirect(route('threads.show', $thread, absolute: false));

    expect($reply->fresh()->body)->toBe('Updated body.');
});

test('non-owner cannot update a reply', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($owner)->create();

    $this->actingAs($other)
        ->put(route('replies.update', [$thread, $reply]), ['body' => 'Should not update.'])
        ->assertForbidden();
});

test('reply owner can delete their reply', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->delete(route('replies.destroy', [$thread, $reply]))
        ->assertRedirect(route('threads.show', $thread, absolute: false));

    expect($reply->fresh()->trashed())->toBeTrue();
});

test('non-owner cannot delete a reply', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($owner)->create();

    $this->actingAs($other)
        ->delete(route('replies.destroy', [$thread, $reply]))
        ->assertForbidden();
});

test('admin can delete any reply', function () {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($owner)->create();

    $this->actingAs($admin)
        ->delete(route('replies.destroy', [$thread, $reply]))
        ->assertRedirect();

    expect($reply->fresh()->trashed())->toBeTrue();
});

test('users cannot reply to a locked thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create(['locked' => true]);

    $this->actingAs($user)
        ->post(route('replies.store', $thread), ['body' => 'Trying to reply.'])
        ->assertForbidden();
});

test('admin can reply to a locked thread', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create(['locked' => true]);

    $this->actingAs($admin)
        ->post(route('replies.store', $thread), ['body' => 'Admin override.'])
        ->assertRedirect();

    expect(Reply::query()->count())->toBe(1);
});

test('guest cannot post a reply', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->post(route('replies.store', $thread), ['body' => 'Anonymous.'])
        ->assertRedirect(route('login'));
});

test('reply body is required', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('replies.store', $thread), ['body' => ''])
        ->assertSessionHasErrors('body');
});

test('parent_id must belong to the same thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $otherThread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $replyOnOtherThread = Reply::factory()->for($otherThread)->for($user)->create();

    $this->actingAs($user)
        ->post(route('replies.store', $thread), [
            'body' => 'Trying to use foreign parent.',
            'parent_id' => $replyOnOtherThread->id,
        ])
        ->assertSessionHasErrors('parent_id');
});
