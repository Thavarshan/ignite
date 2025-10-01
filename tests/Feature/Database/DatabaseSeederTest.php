<?php

namespace Tests\Feature\Database;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_expected_relations(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(5, Channel::count());
        $this->assertSame(50, Thread::count());
        $this->assertSame(250, Reply::count());
        $this->assertTrue(User::query()->where('email', 'test@example.com')->exists());

        Channel::withCount('threads')->each(fn ($channel) => $this->assertSame(10, $channel->threads_count));
        Thread::withCount('replies')->each(fn ($thread) => $this->assertSame(5, $thread->replies_count));
    }
}
