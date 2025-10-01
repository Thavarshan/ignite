<?php

namespace Tests\Feature\Threads;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_thread(): void
    {
        $thread = Thread::factory()->create();

        $this->put(route('threads.update', $thread), [
            'title' => 'Updated title',
        ])->assertRedirect(route('login'));
    }

    public function test_authorized_request_returns_forbidden_response(): void
    {
        $thread = Thread::factory()->create();

        $this->actingAs($thread->author)
            ->put(route('threads.update', $thread), [
                'title' => 'Updated title',
            ])
            ->assertForbidden();
    }
}
