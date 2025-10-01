<?php

namespace Tests\Feature\Threads;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreThreadReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_post_replies(): void
    {
        $thread = Thread::factory()->create();

        $this->post(route('threads.replies.store', $thread), [
            'body' => 'This is a reply body.',
        ])->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_post_reply(): void
    {
        $thread = Thread::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('threads.show', $thread))
            ->post(route('threads.replies.store', $thread), [
                'body' => 'This is a reply body.',
            ]);

        $response->assertRedirect(route('threads.show', $thread));

        $this->assertDatabaseHas('replies', [
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'body' => 'This is a reply body.',
        ]);
    }

    public function test_reply_validation_requires_minimum_length(): void
    {
        $thread = Thread::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('threads.show', $thread))
            ->post(route('threads.replies.store', $thread), [
                'body' => 'bad',
            ])
            ->assertInvalid(['body']);

        $this->assertDatabaseCount('replies', 0);
    }
}
