<?php

namespace Tests\Feature\Channels;

use App\Enums\Role;
use Database\Seeders\RolesAndPermissionsSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateChanenelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function testCreateNewChannel(): void
    {
        $this->actingAs($this->createUser(Role::ADMIN));

        $response = $this->post('/channels', [
            'name' => 'Test Channel',
            'description' => 'Test Description',
        ]);

        $response->assertRedirect('/channels')
            ->assertSessionHas('success', 'Channel created successfully.');
        $this->assertDatabaseHas('channels', [
            'name' => 'Test Channel',
            'description' => 'Test Description',
        ]);
    }

    public function testCreateNewChannelWithoutName(): void
    {
        $this->actingAs($this->createUser(Role::ADMIN));

        $response = $this->post('/channels', [
            'description' => 'Test Description',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function testCreateNewChannelWithoutOptionalDescription(): void
    {
        $this->actingAs($this->createUser(Role::ADMIN));

        $response = $this->post('/channels', [
            'name' => 'Test Channel',
        ]);

        $response->assertRedirect('/channels')
            ->assertSessionHas('success', 'Channel created successfully.');
        $this->assertDatabaseHas('channels', [
            'name' => 'Test Channel',
            'description' => null,
        ]);
    }
}
