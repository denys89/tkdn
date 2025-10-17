<?php

namespace Tests\Feature;

use App\Models\Hpp;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HppApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_approve_hpp(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($superAdmin)
            ->patch(route('hpp.approve', $hpp));

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('success', 'HPP berhasil disetujui.');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_approve_hpp(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('hpp.approve', $hpp));

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('success', 'HPP berhasil disetujui.');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'approved',
        ]);
    }

    public function test_operator_cannot_approve_hpp(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($operator)
            ->patch(route('hpp.approve', $hpp));

        $response->assertStatus(403);

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'submitted',
        ]);
    }

    public function test_super_admin_can_reject_hpp(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($superAdmin)
            ->patch(route('hpp.reject', $hpp));

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('success', 'HPP berhasil ditolak.');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'rejected',
        ]);
    }

    public function test_admin_can_reject_hpp(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('hpp.reject', $hpp));

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('success', 'HPP berhasil ditolak.');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'rejected',
        ]);
    }

    public function test_operator_cannot_reject_hpp(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($operator)
            ->patch(route('hpp.reject', $hpp));

        $response->assertStatus(403);

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'submitted',
        ]);
    }

    public function test_cannot_approve_hpp_with_draft_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('hpp.approve', $hpp));

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('error', 'HPP hanya dapat disetujui jika statusnya "Diajukan".');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'draft',
        ]);
    }

    public function test_cannot_reject_hpp_with_draft_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('hpp.reject', $hpp));

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('error', 'HPP hanya dapat ditolak jika statusnya "Diajukan".');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'draft',
        ]);
    }

    public function test_approve_button_only_visible_for_managers_on_submitted_hpp(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($admin)->get(route('hpp.index'));

        $response->assertStatus(200);
        $response->assertSee('Approve');
        $response->assertSee('Reject');
    }

    public function test_approve_button_not_visible_for_operators(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($operator)->get(route('hpp.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Approve');
        $response->assertDontSee('Reject');
    }
}
