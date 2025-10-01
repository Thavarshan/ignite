<?php

namespace Tests\Feature\Threads;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_delete_thread(): void
    {
        $thread = Thread::factory()->create();

        $this->delete(route('threads.destroy', $thread))->assertRedirect(route('login'));
    }

    public function test_author_can_delete_thread(): void
    {
        $thread = Thread::factory()->create();

        $this->actingAs($thread->author)
            ->delete(route('threads.destroy', $thread))
            ->assertRedirect(route('threads.index'));

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }
}
