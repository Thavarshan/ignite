<?php

namespace Tests\Feature\Channels;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowChannelTest extends TestCase
{
    use RefreshDatabase;

    protected Channel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = Channel::factory()->create([
            'name' => 'Laravel Framework',
            'description' => 'Discussion about the Laravel PHP framework',
        ]);
    }

    public function test_show_channel_by_slug(): void
    {
        $this->get("/channels/{$this->channel->slug}")
            ->assertInertia(
                fn (Assert $page): AssertableJson => $page
                    ->component('channels/Show')
                    ->has(
                        'channel',
                        fn (Assert $page): AssertableJson => $page
                            ->where('id', $this->channel->id)
                            ->where('name', 'Laravel Framework')
                            ->where('description', 'Discussion about the Laravel PHP framework')
                            ->where('slug', $this->channel->slug)
                            ->has('created_at')
                            ->has('updated_at')
                    )
            );
    }

    public function test_show_channel_with_null_description(): void
    {
        $channelWithoutDescription = Channel::factory()->create([
            'name' => 'Vue.js',
            'description' => null,
        ]);

        $this->get("/channels/{$channelWithoutDescription->slug}")
            ->assertInertia(
                fn (Assert $page): AssertableJson => $page
                    ->component('channels/Show')
                    ->has(
                        'channel',
                        fn (Assert $page): AssertableJson => $page
                            ->where('id', $channelWithoutDescription->id)
                            ->where('name', 'Vue.js')
                            ->whereNull('description')
                            ->where('slug', $channelWithoutDescription->slug)
                            ->has('created_at')
                            ->has('updated_at')
                    )
            );
    }

    public function test_show_nonexistent_channel_returns_404(): void
    {
        $response = $this->get('/channels/nonexistent-channel');

        $response->assertNotFound();
    }

    public function test_show_channel_returns_correct_component(): void
    {
        $this->get("/channels/{$this->channel->slug}")
            ->assertInertia(
                fn (Assert $page): AssertableJson => $page
                    ->component('channels/Show')
            );
    }

    public function test_show_channel_includes_all_required_data(): void
    {
        $this->get("/channels/{$this->channel->slug}")
            ->assertInertia(
                fn (Assert $page): AssertableJson => $page
                    ->has('channel')
                    ->has('channel.id')
                    ->has('channel.name')
                    ->has('channel.description')
                    ->has('channel.slug')
                    ->has('channel.created_at')
                    ->has('channel.updated_at')
            );
    }

    public function test_show_channel_with_special_characters_in_slug(): void
    {
        $specialChannel = Channel::factory()->create([
            'name' => 'C++ Programming',
            'description' => 'Discussion about C++ programming language',
        ]);

        $this->get("/channels/{$specialChannel->slug}")
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page): AssertableJson => $page
                    ->component('channels/Show')
                    ->has(
                        'channel',
                        fn (Assert $page): AssertableJson => $page
                            ->where('name', 'C++ Programming')
                            ->where('slug', $specialChannel->slug)
                            ->has('created_at')
                            ->has('updated_at')
                            ->has('id')
                            ->has('description')
                    )
            );
    }

    public function test_show_multiple_channels_with_different_slugs(): void
    {
        $channels = Channel::factory()->count(3)->create();

        foreach ($channels as $channel) {
            $this->get("/channels/{$channel->slug}")
                ->assertStatus(200)
                ->assertInertia(
                    fn (Assert $page): AssertableJson => $page
                        ->component('channels/Show')
                        ->has(
                            'channel',
                            fn (Assert $page): AssertableJson => $page
                                ->where('id', $channel->id)
                                ->where('name', $channel->name)
                                ->where('slug', $channel->slug)
                                ->has('created_at')
                                ->has('updated_at')
                                ->has('description')
                        )
                );
        }
    }
}
