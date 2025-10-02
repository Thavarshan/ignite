<?php

namespace Tests\Feature\Threads;

use App\Jobs\CreateNewThread;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use ReflectionProperty;
use Tests\TestCase;

class StoreThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_store_thread(): void
    {
        $channel = Channel::factory()->create();

        $this->post(route('threads.store'), [
            'title' => 'Guest thread',
            'body' => 'Guests should not be able to post threads.',
            'channel_id' => $channel->id,
        ])->assertRedirect(route('login'));
    }

    public function test_authenticated_user_dispatches_create_thread_job(): void
    {
        Bus::fake();

        $user = User::factory()->create();
        $channel = Channel::factory()->create();

        $payload = [
            'title' => 'A brand new thread',
            'body' => 'This is a new thread body full of insights.',
            'channel_id' => $channel->id,
        ];

        $this->actingAs($user)
            ->from(route('threads.create'))
            ->post(route('threads.store'), $payload)
            ->assertRedirect(route('threads.index'));

        Bus::assertDispatched(CreateNewThread::class, function (CreateNewThread $job) use ($user, $payload): bool {
            $dataProperty = new ReflectionProperty($job, 'data');
            $dataProperty->setAccessible(true);

            $data = $dataProperty->getValue($job);

            return $data['title'] === $payload['title']
                && $data['body'] === $payload['body']
                && $data['channel_id'] === $payload['channel_id']
                && $data['user_id'] === $user->id;
        });
    }
}
