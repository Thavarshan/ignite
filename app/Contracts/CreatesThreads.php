<?php

namespace App\Contracts;

use App\Models\Thread;

interface CreatesThreads
{
    /**
     * Create a new thread.
     */
    public function create(array $data): Thread;
}
