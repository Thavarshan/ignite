<?php

namespace Tests\Feature\Threads;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteThreadsTest extends TestCase
{
    use HandlesThreadSetup, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpThread();
    }

    public function test_delete_existing_thread(): void
    {
        $response = $this->delete(route('channels.threads.destroy', [
            'channel' => $this->channel->slug,
            'thread' => $this->thread->slug,
        ]));

        $response->assertRedirect(route('channels.threads.index', [$this->channel]));
    }
}
