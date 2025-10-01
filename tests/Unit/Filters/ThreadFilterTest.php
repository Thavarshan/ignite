<?php

namespace Tests\Unit\Filters;

use App\Filters\ThreadFilter;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ThreadFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_by_title(): void
    {
        $matching = Thread::factory()->create(['title' => 'Laravel Tips']);
        Thread::factory()->create(['title' => 'Vue Tricks']);

        $request = Request::create('/', 'GET', ['title' => 'Laravel Tips']);
        $filter = new ThreadFilter($request);

        $threads = Thread::query()->filter($filter)->get();

        $this->assertCount(1, $threads);
        $this->assertTrue($threads->first()->is($matching));
    }

    public function test_it_filters_locked_threads(): void
    {
        $locked = Thread::factory()->create(['locked_at' => now()]);
        Thread::factory()->create(['locked_at' => null]);

        $request = Request::create('/', 'GET', ['locked' => true]);
        $filter = new ThreadFilter($request);

        $threads = Thread::query()->filter($filter)->get();

        $this->assertCount(1, $threads);
        $this->assertTrue($threads->first()->is($locked));
    }

    public function test_it_filters_by_channel_slug(): void
    {
        $channel = Channel::factory()->create(['slug' => 'php']);
        $otherChannel = Channel::factory()->create(['slug' => 'javascript']);

        $matching = Thread::factory()->create(['channel_id' => $channel->id]);
        Thread::factory()->create(['channel_id' => $otherChannel->id]);

        $request = Request::create('/', 'GET', ['channel' => 'php']);
        $filter = new ThreadFilter($request);

        $threads = Thread::query()->filter($filter)->get();

        $this->assertCount(1, $threads);
        $this->assertTrue($threads->first()->is($matching));
    }

    public function test_it_filters_by_author_name(): void
    {
        $john = User::factory()->create(['name' => 'John Doe']);
        $jane = User::factory()->create(['name' => 'Jane Doe']);

        $matching = Thread::factory()->create(['user_id' => $john->id]);
        Thread::factory()->create(['user_id' => $jane->id]);

        $request = Request::create('/', 'GET', ['author' => 'John Doe']);
        $filter = new ThreadFilter($request);

        $threads = Thread::query()->filter($filter)->get();

        $this->assertCount(1, $threads);
        $this->assertTrue($threads->first()->is($matching));
    }
}
