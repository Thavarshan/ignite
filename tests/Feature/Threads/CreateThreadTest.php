<?php

namespace Tests\Feature\Threads;

use App\Enums\Role;
use App\Models\Channel;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_create_new_thread(): void
    {
        $channel = Channel::factory()->create();
        $this->signIn($user = $this->createUser(Role::ADMIN));

        $response = $this->post(route('channels.threads.store', [$channel]), [
            'title' => 'Test Title',
            'body' => 'Test Body',
            'channel_id' => $channel->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('threads', [
            'title' => 'Test Title',
            'body' => 'Test Body',
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_create_thread(): void
    {
        $channel = Channel::factory()->create();

        $response = $this->post(route('channels.threads.store', [$channel]), [
            'title' => 'Test Title',
            'body' => 'Test Body',
            'channel_id' => $channel->id,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_thread_requires_title_and_body(): void
    {
        $this->signIn($user = $this->createUser(Role::ADMIN));
        $channel = Channel::factory()->create();

        $response = $this->post(route('channels.threads.store', [$channel]), [
            'title' => '',
            'body' => 'Test Body',
            'channel_id' => $channel->id,
        ]);

        $response->assertSessionHasErrors('title');

        $response = $this->post(route('channels.threads.store', [$channel]), [
            'title' => 'Test Title',
            'body' => '',
            'channel_id' => $channel->id,
        ]);

        $response->assertSessionHasErrors('body');
    }
}
