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
        // Admin user
        $admin = User::where('email', 'admin@example.com')->first()
            ?? User::factory()->admin()->create(['name' => 'Admin', 'email' => 'admin@example.com']);

        // Test user
        $testUser = User::where('email', 'test@example.com')->first()
            ?? User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);

        // Regular users
        $users = User::factory(18)->create();
        $allUsers = $users->merge([$admin, $testUser]);

        // Channels
        $this->call(ChannelSeeder::class);
        $channels = Channel::all();

        // Threads
        $threads = Thread::factory(50)
            ->recycle($channels)
            ->recycle($allUsers)
            ->create();

        // Replies (with some nested)
        $threads->each(function (Thread $thread) use ($allUsers) {
            $replyCount = fake()->numberBetween(0, 8);
            $replies = Reply::factory($replyCount)
                ->for($thread)
                ->recycle($allUsers)
                ->create();

            // Add nested replies to ~30% of replies
            if ($replies->isNotEmpty()) {
                $replies->random(max(1, (int) ($replies->count() * 0.3)))->each(function (Reply $reply) use ($allUsers) {
                    Reply::factory(fake()->numberBetween(1, 3))
                        ->asChild($reply)
                        ->recycle($allUsers)
                        ->create();
                });
            }

            // Update replies_count
            $thread->update(['replies_count' => $thread->replies()->count()]);
        });

        // Best answers on ~40% of threads with replies
        $threads->filter(fn (Thread $t) => $t->replies_count > 0)
            ->random((int) ($threads->count() * 0.4))
            ->each(function (Thread $thread) {
                $thread->markBestReply($thread->replies()->inRandomOrder()->first());
            });

        // Likes on threads
        $threads->random(30)->each(function (Thread $thread) use ($allUsers) {
            $likers = $allUsers->random(fake()->numberBetween(1, 8));
            $likers->each(function (User $user) use ($thread) {
                $thread->likes()->firstOrCreate(['user_id' => $user->id]);
            });
            $thread->update(['likes_count' => $thread->likes()->count()]);
        });

        // Likes on replies
        Reply::query()->inRandomOrder()->limit(60)->get()->each(function (Reply $reply) use ($allUsers) {
            $likers = $allUsers->random(fake()->numberBetween(1, 5));
            $likers->each(function (User $user) use ($reply) {
                $reply->likes()->firstOrCreate(['user_id' => $user->id]);
            });
            $reply->update(['likes_count' => $reply->likes()->count()]);
        });

        // Subscriptions
        $threads->random(25)->each(function (Thread $thread) use ($allUsers) {
            $subscribers = $allUsers->random(fake()->numberBetween(1, 6));
            $subscribers->each(fn (User $user) => $thread->subscribe($user));
        });

        // A few flags
        $threads->random(3)->each(function (Thread $thread) use ($allUsers) {
            $thread->flags()->create([
                'user_id' => $allUsers->where('id', '!=', $thread->user_id)->random()->id,
                'reason' => fake()->randomElement(['spam', 'inappropriate', 'off-topic']),
            ]);
        });
    }
}
