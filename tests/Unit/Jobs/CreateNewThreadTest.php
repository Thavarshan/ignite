<?php

namespace Tests\Unit\Jobs;

use App\Contracts\CreatesThreads;
use App\Jobs\CreateNewThread;
use Mockery;
use Tests\TestCase;

class CreateNewThreadTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_dispatches_to_the_thread_creator(): void
    {
        $payload = ['title' => 'New thread'];

        $creator = Mockery::spy(CreatesThreads::class);

        $job = new CreateNewThread($payload);
        $job->handle($creator);

        $creator->shouldHaveReceived('create')->once()->with($payload);
        $this->addToAssertionCount(1);
    }
}
