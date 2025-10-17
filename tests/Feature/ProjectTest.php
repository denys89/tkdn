<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_index_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('master.project.index'))
            ->assertStatus(200)
            ->assertSee('Project');
    }

    public function test_project_can_be_created()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Project',
            'description' => 'Test Desc',
        ];
        $this->actingAs($user)
            ->post(route('master.project.store'), $data)
            ->assertRedirect(route('master.project.index'));
        $this->assertDatabaseHas('projects', ['name' => 'Test Project']);
    }

    public function test_project_can_be_updated()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $this->actingAs($user)
            ->put(route('master.project.update', $project), [
                'name' => 'Updated',
                'description' => $project->description,
            ])
            ->assertRedirect(route('master.project.index'));
        $this->assertDatabaseHas('projects', ['name' => 'Updated']);
    }

    public function test_project_can_be_deleted()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $this->actingAs($user)
            ->delete(route('master.project.destroy', $project))
            ->assertRedirect(route('master.project.index'));
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
} 