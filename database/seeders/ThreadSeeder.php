<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channel = Channel::factory()->create();
        $user = User::factory()->create();

        Thread::factory()
            ->for($channel)
            ->for($user)
            ->count(10)
            ->create();
    }
}
