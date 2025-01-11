<?php

namespace Tests\Feature\Threads;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyToThreadTest extends TestCase
{
    use HandlesThreadSetup, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpThread();
    }

    public function test_reply_to_thread(): void
    {
        $response = $this
            ->from($this->thread->path)
            ->post(route('channels.threads.replies.store', [
                'channel' => $this->channel->slug,
                'thread' => $this->thread->slug,
            ]), [
                'body' => 'This is a reply to the thread.',
            ]);

        $response->assertRedirect($this->thread->path);
    }
}
