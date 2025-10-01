<?php

namespace Tests\Unit\Support;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_attributes_returns_only_allowed_keys(): void
    {
        $data = ['name' => 'Taylor', 'email' => 'taylor@example.com', 'role' => 'admin'];
        $allowed = ['name', 'email'];

        $result = filter_attributes($data, $allowed);

        $this->assertSame(['name' => 'Taylor', 'email' => 'taylor@example.com'], $result);
    }

    public function test_filter_attributes_reverse_removes_disallowed_keys(): void
    {
        $data = ['name' => 'Taylor', 'email' => 'taylor@example.com', 'role' => 'admin'];
        $forbidden = ['role'];

        $result = filter_attributes_reverse($data, $forbidden);

        $this->assertSame(['name' => 'Taylor', 'email' => 'taylor@example.com'], $result);
    }

    public function test_reverse_filter_attributes_alias_behaves_like_reverse_filter(): void
    {
        $data = ['name' => 'Taylor', 'email' => 'taylor@example.com', 'role' => 'admin'];
        $forbidden = ['role'];

        $result = reverse_filter_attributes($data, $forbidden);

        $this->assertSame(['name' => 'Taylor', 'email' => 'taylor@example.com'], $result);
    }

    public function test_has_fillable_attributes_requires_all_keys(): void
    {
        $data = ['name' => 'Taylor', 'email' => 'taylor@example.com'];
        $fillable = ['name', 'email'];

        $this->assertTrue(has_fillable_attributes($data, $fillable));
        $this->assertFalse(has_fillable_attributes($data, ['name', 'email', 'role']));
    }

    public function test_has_attribute_checks_database_columns(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(has_attribute($user, 'email'));
        $this->assertFalse(has_attribute($user, 'non_existent_column'));
    }
}
