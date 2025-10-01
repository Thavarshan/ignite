<?php

namespace Tests\Feature\Threads;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_thread_edit(): void
    {
        $thread = Thread::factory()->create();

        $this->get(route('threads.edit', $thread))->assertRedirect(route('login'));
    }

    public function test_author_can_access_thread_edit_form(): void
    {
        $thread = Thread::factory()->create();

        $this->actingAs($thread->author)
            ->get(route('threads.edit', $thread))
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page): Assert => $page
                    ->component('threads/Edit')
                    ->where('thread.id', $thread->id)
            );
    }
}
