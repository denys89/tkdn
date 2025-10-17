<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect('/home');
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function test_user_register_validation_error()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'pass',
            'password_confirmation' => 'different',
        ]);
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => Hash::make('password123'),
        ]);
        $response = $this->post('/login', [
            'email' => 'loginuser@example.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_login_validation_error()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_protected_route_redirects_if_not_authenticated()
    {
        $response = $this->get('/home');
        $response->assertRedirect('/login');
    }
} 