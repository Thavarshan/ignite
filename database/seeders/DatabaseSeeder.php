<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $testUser = User::query()->firstWhere('email', 'test@example.com');

        if (is_null($testUser)) {
            $testUser = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $users = User::factory()
            ->count(10)
            ->create();

        $users->push($testUser);

        Channel::factory()
            ->count(5)
            ->has(
                Thread::factory()
                    ->count(10)
                    ->recycle($users)
                    ->has(
                        Reply::factory()
                            ->count(5)
                            ->recycle($users),
                        'replies'
                    ),
                'threads'
            )
            ->create();
    }
}
