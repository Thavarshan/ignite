<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            ['name' => 'General Discussion', 'description' => 'Talk about anything and everything.'],
            ['name' => 'Help & Support', 'description' => 'Ask questions and get help from the community.'],
            ['name' => 'Feature Requests', 'description' => 'Suggest and discuss new features.'],
            ['name' => 'Bug Reports', 'description' => 'Report bugs and track fixes.'],
            ['name' => 'Show & Tell', 'description' => 'Share your projects and creations.'],
            ['name' => 'Tutorials', 'description' => 'Learn and teach through step-by-step guides.'],
            ['name' => 'Off Topic', 'description' => 'Non-technical conversations and fun stuff.'],
            ['name' => 'Announcements', 'description' => 'Official news and updates.'],
        ];

        foreach ($channels as $channel) {
            Channel::query()->firstOrCreate(['name' => $channel['name']], $channel);
        }
    }
}
