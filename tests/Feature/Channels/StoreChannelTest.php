<?php

namespace Tests\Feature\Channels;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StoreChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_channel_with_valid_data(): void
    {
        $channelData = [
            'name' => 'Laravel',
            'description' => 'A channel for Laravel discussions',
        ];

        $response = $this->postJson('/api/channels', $channelData);

        $response->assertCreated();
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->where('name', 'Laravel')
                ->where('description', 'A channel for Laravel discussions')
                ->where('slug', 'laravel')
                ->has('id')
                ->has('created_at')
                ->has('updated_at')
        );

        $this->assertDatabaseHas('channels', [
            'name' => 'Laravel',
            'description' => 'A channel for Laravel discussions',
            'slug' => 'laravel',
        ]);
    }

    public function test_create_channel_with_only_required_fields(): void
    {
        $channelData = [
            'name' => 'Vue.js',
        ];

        $response = $this->postJson('/api/channels', $channelData);

        $response->assertCreated();
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->where('name', 'Vue.js')
                ->where('slug', 'vuejs')
                ->whereNull('description')
                ->has('id')
                ->has('created_at')
                ->has('updated_at')
        );

        $this->assertDatabaseHas('channels', [
            'name' => 'Vue.js',
            'slug' => 'vuejs',
            'description' => null,
        ]);
    }

    public function test_create_channel_fails_without_name(): void
    {
        $channelData = [
            'description' => 'A channel without a name',
        ];

        $response = $this->postJson('/api/channels', $channelData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_create_channel_fails_with_empty_name(): void
    {
        $channelData = [
            'name' => '',
            'description' => 'A channel with empty name',
        ];

        $response = $this->postJson('/api/channels', $channelData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_create_channel_fails_with_name_too_long(): void
    {
        $channelData = [
            'name' => str_repeat('a', 256),
            'description' => 'A channel with a very long name',
        ];

        $response = $this->postJson('/api/channels', $channelData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_create_channel_fails_with_duplicate_name(): void
    {
        Channel::factory()->create(['name' => 'Existing Channel']);

        $channelData = [
            'name' => 'Existing Channel',
            'description' => 'Trying to create a duplicate',
        ];

        $response = $this->postJson('/api/channels', $channelData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_create_channel_generates_unique_slug(): void
    {
        $channelData = [
            'name' => 'React Development',
            'description' => 'A channel for React discussions',
        ];

        $response = $this->postJson('/api/channels', $channelData);

        $response->assertCreated();
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->where('name', 'React Development')
                ->where('slug', 'react-development')
                ->where('description', 'A channel for React discussions')
                ->has('id')
                ->has('created_at')
                ->has('updated_at')
        );
    }
}
