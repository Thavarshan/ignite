<?php

namespace App\Jobs;

use App\Contracts\CreatesThreads;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateNewThread implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(CreatesThreads $creator): void
    {
        $creator->create($this->data);
    }
}
