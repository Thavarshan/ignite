<?php

namespace Tests\Feature\Threads;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetFullPathToThreadTest extends TestCase
{
    use HandlesThreadSetup, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpThread();
    }

    public function test_get_full_path_to_single_thread(): void
    {
        $this->assertEquals(route('channels.threads.show', [
            'channel' => $this->channel->slug,
            'thread' => $this->thread->slug,
        ]), $this->thread->path);
    }
}
