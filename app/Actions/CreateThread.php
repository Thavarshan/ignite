<?php

namespace App\Actions;

use App\Contracts\CreatesThreads;
use App\Models\Thread;

class CreateThread implements CreatesThreads
{
    /**
     * Create a new thread.
     */
    public function create(array $data): Thread
    {
        return Thread::create($data);
    }
}
