<?php

use App\Models\Channel;
use App\Models\Flag;
use App\Models\Thread;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('admin can view the flags page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();
    $flaggers = User::factory()->count(3)->create();
    $flaggers->each(fn (User $user) => Flag::factory()->for($thread, 'flaggable')->for($user)->create());

    $this->actingAs($admin)
        ->get(route('admin.flags.index'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('admin/Flags', false)
                ->has('flags.data', 3)
                ->where('status', 'pending')
        );
});

test('non-admin cannot view the flags page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.flags.index'))
        ->assertForbidden();
});

test('guest cannot view the flags page', function () {
    $this->get(route('admin.flags.index'))
        ->assertRedirect(route('login'));
});

test('admin can filter flags by resolved status', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $pendingUsers = User::factory()->count(2)->create();
    $pendingUsers->each(fn (User $user) => Flag::factory()->for($thread, 'flaggable')->for($user)->create());
    Flag::factory()->for($thread, 'flaggable')->for(User::factory())->create([
        'resolved_at' => now(),
        'resolved_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.flags.index', ['status' => 'resolved']))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->has('flags.data', 1)
                ->where('status', 'resolved')
        );

    $this->actingAs($admin)
        ->get(route('admin.flags.index', ['status' => 'pending']))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->has('flags.data', 2)
                ->where('status', 'pending')
        );
});

test('admin can view all flags regardless of status', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $thread = Thread::factory()->for(Channel::factory())->for(User::factory())->create();

    $pendingUsers = User::factory()->count(2)->create();
    $pendingUsers->each(fn (User $user) => Flag::factory()->for($thread, 'flaggable')->for($user)->create());
    Flag::factory()->for($thread, 'flaggable')->for(User::factory())->create([
        'resolved_at' => now(),
        'resolved_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.flags.index', ['status' => 'all']))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->has('flags.data', 3)
                ->where('status', 'all')
        );
});
