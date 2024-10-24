<?php

namespace Tests\Feature\Channels;

use App\Enums\Role;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateChanenelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_create_new_channel(): void
    {
        $this->actingAs($this->createUser(Role::ADMIN));

        $response = $this->post(route('channels.store'), [
            'name' => 'Test Channel',
            'description' => 'Test Description',
        ]);

        $response->assertRedirect(route('channels.store'))
            ->assertSessionHas('success', 'Channel created successfully.');
        $this->assertDatabaseHas('channels', [
            'name' => 'Test Channel',
            'description' => 'Test Description',
        ]);
    }

    public function test_create_new_channel_without_name(): void
    {
        $this->actingAs($this->createUser(Role::ADMIN));

        $response = $this->post(route('channels.store'), [
            'description' => 'Test Description',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_new_channel_without_optional_description(): void
    {
        $this->actingAs($this->createUser(Role::ADMIN));

        $response = $this->post(route('channels.store'), [
            'name' => 'Test Channel',
        ]);

        $response->assertRedirect(route('channels.store'))
            ->assertSessionHas('success', 'Channel created successfully.');
        $this->assertDatabaseHas('channels', [
            'name' => 'Test Channel',
            'description' => null,
        ]);
    }
}
