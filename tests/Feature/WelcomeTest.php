<?php

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('welcome page renders for guests', function () {
    $channel = Channel::factory()->create();
    Thread::factory()->count(5)->for($channel)->for(User::factory())->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Welcome', false)
                ->has('latestThreads', 5)
                ->has('popularChannels', 1)
        );
});

test('welcome page shows latest threads ordered by pinned and last posted', function () {
    $channel = Channel::factory()->create();
    $user = User::factory()->create();

    $regular = Thread::factory()->for($channel)->for($user)->create(['title' => 'Regular']);
    $pinned = Thread::factory()->for($channel)->for($user)->create(['title' => 'Pinned', 'pinned' => true]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Welcome', false)
                ->has('latestThreads', 2)
                ->where('latestThreads.0.title', 'Pinned')
        );
});

test('welcome page limits to 10 threads and 8 channels', function () {
    Channel::factory()->count(12)->create();
    $channel = Channel::factory()->create();
    Thread::factory()->count(15)->for($channel)->for(User::factory())->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Welcome', false)
                ->has('latestThreads', 10)
                ->has('popularChannels', 8)
        );
});

test('welcome page shares canRegister prop', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Welcome', false)
                ->has('canRegister')
        );
});
