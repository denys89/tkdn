<?php

namespace Tests\Feature;

use App\Models\Hpp;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HppStatusEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_edit_hpp_status(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($superAdmin)
            ->put(route('hpp.update', $hpp), [
                'project_id' => $project->id,
                'status' => 'submitted',
                'overhead_percentage' => 10,
                'margin_percentage' => 15,
                'ppn_percentage' => 11,
                'notes' => 'Test notes',
                'items' => [
                    [
                        'description' => 'Test Item',
                        'tkdn_classification' => '3.1',
                        'volume' => 10,
                        'unit' => 'Unit',
                        'duration' => 5,
                        'duration_unit' => 'Hari',
                        'unit_price' => 1000,
                    ],
                ],
            ]);

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('success', 'HPP berhasil diperbarui!');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'submitted',
        ]);
    }

    public function test_admin_can_edit_hpp_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('hpp.update', $hpp), [
                'project_id' => $project->id,
                'status' => 'submitted',
                'overhead_percentage' => 10,
                'margin_percentage' => 15,
                'ppn_percentage' => 11,
                'notes' => 'Test notes',
                'items' => [
                    [
                        'description' => 'Test Item',
                        'tkdn_classification' => '3.1',
                        'volume' => 10,
                        'unit' => 'Unit',
                        'duration' => 5,
                        'duration_unit' => 'Hari',
                        'unit_price' => 1000,
                    ],
                ],
            ]);

        $response->assertRedirect(route('hpp.index'));
        $response->assertSessionHas('success', 'HPP berhasil diperbarui!');

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'submitted',
        ]);
    }

    public function test_operator_cannot_edit_hpp_status(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($operator)
            ->put(route('hpp.update', $hpp), [
                'project_id' => $project->id,
                'status' => 'submitted',
                'overhead_percentage' => 10,
                'margin_percentage' => 15,
                'ppn_percentage' => 11,
                'notes' => 'Test notes',
                'items' => [
                    [
                        'description' => 'Test Item',
                        'tkdn_classification' => '3.1',
                        'volume' => 10,
                        'unit' => 'Unit',
                        'duration' => 5,
                        'duration_unit' => 'Hari',
                        'unit_price' => 1000,
                    ],
                ],
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'draft',
        ]);
    }

    public function test_status_field_validation_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('hpp.update', $hpp), [
                'project_id' => $project->id,
                'status' => 'invalid_status',
                'overhead_percentage' => 10,
                'margin_percentage' => 15,
                'ppn_percentage' => 11,
                'notes' => 'Test notes',
                'items' => [
                    [
                        'description' => 'Test Item',
                        'tkdn_classification' => '3.1',
                        'volume' => 10,
                        'unit' => 'Unit',
                        'duration' => 5,
                        'duration_unit' => 'Hari',
                        'unit_price' => 1000,
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['status']);

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'draft',
        ]);
    }

    public function test_status_field_required_validation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('hpp.update', $hpp), [
                'project_id' => $project->id,
                'overhead_percentage' => 10,
                'margin_percentage' => 15,
                'ppn_percentage' => 11,
                'notes' => 'Test notes',
                'items' => [
                    [
                        'description' => 'Test Item',
                        'tkdn_classification' => '3.1',
                        'volume' => 10,
                        'unit' => 'Unit',
                        'duration' => 5,
                        'duration_unit' => 'Hari',
                        'unit_price' => 1000,
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['status']);

        $this->assertDatabaseHas('hpps', [
            'id' => $hpp->id,
            'status' => 'draft',
        ]);
    }

    public function test_status_field_accepts_all_valid_values(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        $hpp = Hpp::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $validStatuses = ['draft', 'submitted', 'approved', 'rejected'];

        foreach ($validStatuses as $status) {
            $response = $this->actingAs($admin)
                ->put(route('hpp.update', $hpp), [
                    'project_id' => $project->id,
                    'status' => $status,
                    'overhead_percentage' => 10,
                    'margin_percentage' => 15,
                    'ppn_percentage' => 11,
                    'notes' => 'Test notes',
                    'items' => [
                        [
                            'description' => 'Test Item',
                            'tkdn_classification' => '3.1',
                            'volume' => 10,
                            'unit' => 'Unit',
                            'duration' => 5,
                            'duration_unit' => 'Hari',
                            'unit_price' => 1000,
                        ],
                    ],
                ]);

            $response->assertRedirect(route('hpp.index'));
            $response->assertSessionHas('success', 'HPP berhasil diperbarui!');

            $this->assertDatabaseHas('hpps', [
                'id' => $hpp->id,
                'status' => $status,
            ]);
        }
    }
}
