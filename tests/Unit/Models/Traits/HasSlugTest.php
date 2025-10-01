<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_a_slug_when_creating(): void
    {
        $channel = Channel::factory()->create(['name' => 'Laravel News', 'slug' => null]);

        $this->assertSame('laravel-news', $channel->slug);
    }

    public function test_it_appends_incrementing_suffix_for_duplicate_slugs(): void
    {
        Channel::factory()->create(['name' => 'Community', 'slug' => 'community']);
        $duplicate = Channel::factory()->create(['name' => 'Community', 'slug' => null]);

        $this->assertSame('community-1', $duplicate->slug);
    }

    public function test_it_updates_slug_when_source_changes(): void
    {
        $channel = Channel::factory()->create(['name' => 'General', 'slug' => null]);

        $channel->update(['name' => 'General Chat']);
        $channel->refresh();

        $this->assertSame('general-chat', $channel->slug);
    }
}
