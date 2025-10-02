<?php

namespace Tests\Feature\Threads;

use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowThreadTest extends TestCase
{
    use RefreshDatabase;

    protected Thread $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $channel = Channel::factory()->create();
        $this->thread = Thread::factory()->for($channel)->create([
            'title' => 'How to use Laravel 12?',
            'body' => '<p>This is a thread body.</p>',
        ]);
    }

    public function test_show_thread_by_slug(): void
    {
        $this->get(route('threads.show', $this->thread))
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page): AssertableJson => $page
                    ->component('threads/Show')
                    ->where('thread.id', $this->thread->id)
                    ->where('thread.title', 'How to use Laravel 12?')
                    ->where('thread.slug', $this->thread->slug)
                    ->has('thread.channel')
                    ->has('thread.author')
                    ->where('thread.body', '<p>This is a thread body.</p>')
            );
    }

    public function test_show_nonexistent_thread_returns_404(): void
    {
        $this->get(route('threads.show', ['thread' => 'missing-thread']))->assertNotFound();
    }

    public function test_show_thread_component_is_correct(): void
    {
        $this->get(route('threads.show', $this->thread))
            ->assertInertia(fn (Assert $page): AssertableJson => $page->component('threads/Show'));
    }
}
