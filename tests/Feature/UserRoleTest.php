<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Admin Dashboard');
    }

    public function test_user_cannot_access_admin_page()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertStatus(403); // forbidden
    }

    public function test_guest_redirected_from_admin_page()
    {
        $this->get('/admin/dashboard')
            ->assertRedirect('/login');
    }
} 