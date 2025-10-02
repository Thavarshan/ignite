<?php

namespace Tests\Feature\Threads;

use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected Collection $channels;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channels = Channel::factory()->count(3)->create();
    }

    public function test_home_route_renders_threads_index(): void
    {
        $threads = $this->channels->map(fn (Channel $channel): Thread => Thread::factory()->for($channel)->create());

        $this->get(route('home'))
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page): Assert => $page
                    ->component('threads/Index')
                    ->has('threads.data', $threads->count())
                    ->where('threads.data', function ($threadsData) use ($threads): bool {
                        return collect($threadsData)->pluck('id')->sort()->values()->all() === $threads->pluck('id')->sort()->values()->all();
                    })
            );
    }

    public function test_thread_index_route_renders_threads_index(): void
    {
        $threads = $this->channels->map(fn (Channel $channel): Thread => Thread::factory()->for($channel)->create());

        $this->get(route('threads.index'))
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page): Assert => $page
                    ->component('threads/Index')
                    ->has('threads.data', $threads->count())
                    ->where('threads.data', function ($threadsData) use ($threads): bool {
                        return collect($threadsData)->pluck('id')->sort()->values()->all() === $threads->pluck('id')->sort()->values()->all();
                    })
            );
    }

    public function test_channel_threads_index_filters_threads_by_channel(): void
    {
        $channel = $this->channels->first();
        $otherChannel = $this->channels->last();

        $channelThreads = Thread::factory()->count(2)->for($channel)->create();
        Thread::factory()->for($otherChannel)->create();

        $this->get(route('channels.threads.index', ['channel' => $channel]))
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page): Assert => $page
                    ->component('threads/Index')
                    ->has('threads.data', $channelThreads->count())
                    ->where('threads.data', function ($threadsData) use ($channel): bool {
                        return collect($threadsData)->every(fn (array $thread): bool => (int) $thread['channel']['id'] === $channel->id);
                    })
            );
    }
}
