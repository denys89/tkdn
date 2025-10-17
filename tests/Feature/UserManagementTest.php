<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_users_management(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Users Management');
    }

    public function test_admin_can_access_users_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Users Management');
    }

    public function test_operator_cannot_access_users_management(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)->get('/users');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get('/users/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah User Baru');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/users/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah User Baru');
    }

    public function test_operator_cannot_create_user(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)->get('/users/create');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_store_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
        ];

        $response = $this->actingAs($superAdmin)->post('/users', $userData);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'operator',
        ]);
    }

    public function test_admin_can_store_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
        ];

        $response = $this->actingAs($admin)->post('/users', $userData);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'operator',
        ]);
    }

    public function test_operator_cannot_store_user(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator',
        ];

        $response = $this->actingAs($operator)->post('/users', $userData);

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_own_account(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->delete("/users/{$user->id}");

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_role_validation_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'invalid_role',
        ];

        $response = $this->actingAs($admin)->post('/users', $userData);

        $response->assertSessionHasErrors(['role']);
    }
}
