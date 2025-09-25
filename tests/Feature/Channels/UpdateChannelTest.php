<?php

namespace Tests\Feature\Channels;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UpdateChannelTest extends TestCase
{
    use RefreshDatabase;

    protected Channel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = Channel::factory()->create([
            'name' => 'Original Channel',
            'description' => 'Original description',
        ]);
    }

    public function test_update_channel_with_valid_data(): void
    {
        $updateData = [
            'name' => 'Updated Channel',
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->where('id', $this->channel->id)
                ->where('name', 'Updated Channel')
                ->where('description', 'Updated description')
                ->where('slug', 'updated-channel')
                ->has('created_at')
                ->has('updated_at')
        );

        $this->assertDatabaseHas('channels', [
            'id' => $this->channel->id,
            'name' => 'Updated Channel',
            'description' => 'Updated description',
            'slug' => 'updated-channel',
        ]);
    }

    public function test_update_channel_with_patch_method(): void
    {
        $updateData = [
            'name' => 'Patched Channel',
        ];

        $response = $this->patchJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->where('name', 'Patched Channel')
                ->where('description', 'Original description')
                ->has('id')
                ->has('slug')
                ->has('created_at')
                ->has('updated_at')
        );
    }

    public function test_update_channel_name_only(): void
    {
        $updateData = [
            'name' => 'New Name Only',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertOk();
        $this->assertDatabaseHas('channels', [
            'id' => $this->channel->id,
            'name' => 'New Name Only',
            'description' => 'Original description',
        ]);
    }

    public function test_update_channel_description_only(): void
    {
        $updateData = [
            'name' => 'Original Channel',
            'description' => 'New description only',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertOk();
        $this->assertDatabaseHas('channels', [
            'id' => $this->channel->id,
            'name' => 'Original Channel',
            'description' => 'New description only',
        ]);
    }

    public function test_update_channel_clear_description(): void
    {
        $updateData = [
            'name' => 'Original Channel',
            'description' => null,
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertOk();
        $this->assertDatabaseHas('channels', [
            'id' => $this->channel->id,
            'name' => 'Original Channel',
            'description' => null,
        ]);
    }

    public function test_update_channel_fails_without_name(): void
    {
        $updateData = [
            'description' => 'Description without name',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_update_channel_fails_with_empty_name(): void
    {
        $updateData = [
            'name' => '',
            'description' => 'Empty name update',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_update_channel_fails_with_name_too_long(): void
    {
        $updateData = [
            'name' => str_repeat('a', 256),
            'description' => 'Long name update',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_update_channel_fails_with_duplicate_name(): void
    {
        $otherChannel = Channel::factory()->create(['name' => 'Other Channel']);

        $updateData = [
            'name' => 'Other Channel',
            'description' => 'Trying to use existing name',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_update_channel_allows_keeping_same_name(): void
    {
        $updateData = [
            'name' => 'Original Channel',
            'description' => 'Updated description with same name',
        ];

        $response = $this->putJson("/api/channels/{$this->channel->slug}", $updateData);

        $response->assertOk();
        $this->assertDatabaseHas('channels', [
            'id' => $this->channel->id,
            'name' => 'Original Channel',
            'description' => 'Updated description with same name',
        ]);
    }

    public function test_update_nonexistent_channel_returns_404(): void
    {
        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ];

        $response = $this->putJson('/api/channels/999', $updateData);

        $response->assertNotFound();
    }
}