<?php

namespace Tests\Unit\Actions;

use App\Actions\CreateThread;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_thread_record(): void
    {
        $channel = Channel::factory()->create();
        $user = User::factory()->create();

        $data = [
            'title' => 'Introduce yourself',
            'body' => '<p>Hello world</p>',
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ];

        $thread = (new CreateThread)->create($data);

        $this->assertInstanceOf(Thread::class, $thread);
        $this->assertDatabaseHas('threads', [
            'id' => $thread->id,
            'title' => 'Introduce yourself',
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ]);
    }
}
