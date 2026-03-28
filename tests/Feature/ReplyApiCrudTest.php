<?php

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

test('authenticated users can list top-level replies for a thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();
    Reply::factory()->asChild($parent)->for($user)->create();

    $this->actingAs($user)
        ->getJson(route('api.threads.replies.index', $thread))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data');
});

test('child replies are embedded in parent children array', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();
    Reply::factory()->asChild($parent)->for($user)->create();

    $this->actingAs($user)
        ->getJson(route('api.threads.replies.index', $thread))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data.0.children');
});

test('authenticated users can create a reply via api', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'My API reply.'])
        ->assertCreated()
        ->assertJsonPath('data.body', 'My API reply.')
        ->assertJsonPath('data.user_id', $user->id)
        ->assertJsonPath('data.thread_id', $thread->id)
        ->assertJsonPath('data.parent_id', null);
});

test('authenticated users can create a nested reply via api', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), [
            'body' => 'Nested API reply.',
            'parent_id' => $parent->id,
        ])
        ->assertCreated()
        ->assertJsonPath('data.parent_id', $parent->id);
});

test('nested replies cannot exceed depth 1 via api', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $parent = Reply::factory()->for($thread)->for($user)->create();
    $child = Reply::factory()->asChild($parent)->for($user)->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), [
            'body' => 'Too deep.',
            'parent_id' => $child->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('parent_id');
});

test('reply owner can update their reply via api', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create(['body' => 'Original.']);

    $this->actingAs($user)
        ->putJson(route('api.threads.replies.update', [$thread, $reply]), ['body' => 'Updated.'])
        ->assertSuccessful()
        ->assertJsonPath('data.body', 'Updated.');
});

test('non-owner cannot update a reply via api', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($owner)->create();

    $this->actingAs($other)
        ->putJson(route('api.threads.replies.update', [$thread, $reply]), ['body' => 'No access.'])
        ->assertForbidden();
});

test('reply owner can delete their reply via api', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($user)->create();

    $this->actingAs($user)
        ->deleteJson(route('api.threads.replies.destroy', [$thread, $reply]))
        ->assertNoContent();

    expect($reply->fresh()->trashed())->toBeTrue();
});

test('non-owner cannot delete a reply via api', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $reply = Reply::factory()->for($thread)->for($owner)->create();

    $this->actingAs($other)
        ->deleteJson(route('api.threads.replies.destroy', [$thread, $reply]))
        ->assertForbidden();
});

test('locked thread denies replying via api', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create(['locked' => true]);

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'Blocked.'])
        ->assertForbidden();
});

test('admin can reply to a locked thread via api', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create(['locked' => true]);

    $this->actingAs($admin)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => 'Admin override.'])
        ->assertCreated();
});

test('unauthenticated request returns 401', function () {
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->getJson(route('api.threads.replies.index', $thread))
        ->assertUnauthorized();

    $this->postJson(route('api.threads.replies.store', $thread), ['body' => 'Anon reply.'])
        ->assertUnauthorized();
});

test('can filter replies by author name', function () {
    $alice = User::factory()->create(['name' => 'Alice Smith']);
    $bob = User::factory()->create(['name' => 'Bob Jones']);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    Reply::factory()->for($thread)->for($alice)->create(['body' => 'Alice reply.']);
    Reply::factory()->for($thread)->for($bob)->create(['body' => 'Bob reply.']);

    $this->actingAs($alice)
        ->getJson(route('api.threads.replies.index', [$thread, 'author' => 'Alice']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.body', 'Alice reply.');
});

test('reply body is required via api', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $this->actingAs($user)
        ->postJson(route('api.threads.replies.store', $thread), ['body' => ''])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('body');
});
