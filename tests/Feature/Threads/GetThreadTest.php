<?php

namespace Tests\Feature\Threads;

use App\Enums\Role;
use App\Models\Channel;
use App\Models\Thread;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GetThreadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_get_specific_thread(): void
    {
        $this->actingAs($user = $this->createUser(Role::ADMIN));

        $channel = Channel::factory()->create();
        $thread = Thread::factory()
            ->for($user)
            ->for($channel)
            ->create();

        $this->assertEquals(0, $thread->views);

        $response = $this->get(route('channels.threads.show', [$channel, $thread]));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('threads/show')
            ->has('thread', fn (Assert $prop) => $prop
                ->where('id', $thread->id)
                ->where('views', 1)
                ->etc()
            )
        );

        $thread->refresh();

        $this->assertEquals(1, $thread->views);
    }
}
