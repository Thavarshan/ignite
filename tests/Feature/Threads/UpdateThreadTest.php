<?php

namespace Tests\Feature\Threads;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadTest extends TestCase
{
    use HandlesThreadSetup, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpThread();
    }

    public function test_update_existing_thread(): void
    {
        $response = $this->put(route('channels.threads.update', [
            'channel' => $this->channel->slug,
            'thread' => $this->thread->slug,
        ]), [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
        ]);

        $response->assertRedirect($this->thread->fresh()->path);
    }
}
