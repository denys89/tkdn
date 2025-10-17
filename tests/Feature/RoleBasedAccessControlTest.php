<?php

namespace Tests\Feature;

use App\Models\Hpp;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_hpp_management(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get('/hpp');

        $response->assertStatus(200);
        $response->assertSee('Tambah HPP');
    }

    public function test_admin_can_access_hpp_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/hpp');

        $response->assertStatus(200);
        $response->assertSee('Tambah HPP');
    }

    public function test_operator_cannot_see_hpp_management_buttons(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)->get('/hpp');

        $response->assertStatus(200);
        $response->assertDontSee('Tambah HPP');
    }

    public function test_super_admin_can_access_service_management(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get('/service');

        $response->assertStatus(200);
        $response->assertSee('Tambah Service');
    }

    public function test_admin_can_access_service_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/service');

        $response->assertStatus(200);
        $response->assertSee('Tambah Service');
    }

    public function test_operator_cannot_see_service_management_buttons(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)->get('/service');

        $response->assertStatus(200);
        $response->assertDontSee('Tambah Service');
    }

    public function test_super_admin_can_create_hpp(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get('/hpp/create');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_hpp(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/hpp/create');

        $response->assertStatus(200);
    }

    public function test_operator_cannot_create_hpp(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)->get('/hpp/create');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_service(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get('/service/create');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/service/create');

        $response->assertStatus(200);
    }

    public function test_operator_cannot_create_service(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)->get('/service/create');

        $response->assertStatus(403);
    }

    public function test_hpp_edit_button_only_visible_to_managers(): void
    {
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        // Super admin should see edit button
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $response = $this->actingAs($superAdmin)->get('/hpp');
        $response->assertStatus(200);
        $response->assertSee('Edit');

        // Admin should see edit button
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/hpp');
        $response->assertStatus(200);
        $response->assertSee('Edit');

        // Operator should not see edit button
        $operator = User::factory()->create(['role' => 'operator']);
        $response = $this->actingAs($operator)->get('/hpp');
        $response->assertStatus(200);
        $response->assertDontSee('Edit');
    }

    public function test_service_edit_button_only_visible_to_managers(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        // Super admin should see edit button
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $response = $this->actingAs($superAdmin)->get('/service');
        $response->assertStatus(200);
        $response->assertSee('Edit');

        // Admin should see edit button
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/service');
        $response->assertStatus(200);
        $response->assertSee('Edit');

        // Operator should not see edit button
        $operator = User::factory()->create(['role' => 'operator']);
        $response = $this->actingAs($operator)->get('/service');
        $response->assertStatus(200);
        $response->assertDontSee('Edit');
    }
}
