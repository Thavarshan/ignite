<?php

namespace App\Actions;

use App\Contracts\CreatesThreads;
use App\Models\Thread;
use Illuminate\Support\Facades\DB;

class CreateThread implements CreatesThreads
{
    /**
     * Create a new thread.
     */
    public function create(array $data): Thread
    {
        return DB::transaction(fn (): Thread => Thread::create($data));
    }
}
