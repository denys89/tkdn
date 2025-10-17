<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_project_with_tkdn_jasa_type()
    {
        $projectData = [
            'name' => 'Test Project TKDN Jasa',
            'project_type' => 'tkdn_jasa',
            'status' => 'draft',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'description' => 'Test project description',
            'company' => 'Test Company',
            'location' => 'Jakarta',
        ];

        $response = $this->post(route('master.project.store'), $projectData);

        $response->assertRedirect(route('master.project.index'));
        $response->assertSessionHas('success', 'Project created successfully.');

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project TKDN Jasa',
            'project_type' => 'tkdn_jasa',
        ]);
    }

    /** @test */
    public function it_can_create_project_with_tkdn_barang_jasa_type()
    {
        $projectData = [
            'name' => 'Test Project TKDN Barang & Jasa',
            'project_type' => 'tkdn_barang_jasa',
            'status' => 'draft',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'description' => 'Test project description',
            'company' => 'Test Company',
            'location' => 'Jakarta',
        ];

        $response = $this->post(route('master.project.store'), $projectData);

        $response->assertRedirect(route('master.project.index'));
        $response->assertSessionHas('success', 'Project created successfully.');

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project TKDN Barang & Jasa',
            'project_type' => 'tkdn_barang_jasa',
        ]);
    }

    /** @test */
    public function it_validates_project_type_is_required()
    {
        $projectData = [
            'name' => 'Test Project',
            'project_type' => '',
            'status' => 'draft',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'company' => 'Test Company',
            'location' => 'Jakarta',
        ];

        $response = $this->post(route('master.project.store'), $projectData);

        $response->assertSessionHasErrors(['project_type']);
    }

    /** @test */
    public function it_validates_project_type_must_be_valid()
    {
        $projectData = [
            'name' => 'Test Project',
            'project_type' => 'invalid_type',
            'status' => 'draft',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'company' => 'Test Company',
            'location' => 'Jakarta',
        ];

        $response = $this->post(route('master.project.store'), $projectData);

        $response->assertSessionHasErrors(['project_type']);
    }

    /** @test */
    public function it_can_update_project_type()
    {
        $project = Project::factory()->create(['project_type' => 'tkdn_jasa']);

        $updateData = [
            'name' => $project->name,
            'project_type' => 'tkdn_barang_jasa',
            'status' => $project->status,
            'start_date' => $project->start_date->format('Y-m-d'),
            'end_date' => $project->end_date->format('Y-m-d'),
            'description' => $project->description,
            'company' => $project->company,
            'location' => $project->location,
        ];

        $response = $this->put(route('master.project.update', $project), $updateData);

        $response->assertRedirect(route('master.project.index'));
        $response->assertSessionHas('success', 'Project updated successfully.');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'project_type' => 'tkdn_barang_jasa',
        ]);
    }

    /** @test */
    public function it_can_get_project_types_from_model()
    {
        $projectTypes = Project::getProjectTypes();

        $this->assertIsArray($projectTypes);
        $this->assertArrayHasKey('tkdn_jasa', $projectTypes);
        $this->assertArrayHasKey('tkdn_barang_jasa', $projectTypes);
        $this->assertEquals('TKDN Jasa (Form 3.1 - 3.5)', $projectTypes['tkdn_jasa']);
        $this->assertEquals('TKDN Barang & Jasa (Form 4.1 - 4.7)', $projectTypes['tkdn_barang_jasa']);
    }

    /** @test */
    public function it_displays_project_type_in_index_view()
    {
        Project::factory()->create([
            'name' => 'Test Project',
            'project_type' => 'tkdn_jasa',
        ]);

        $response = $this->get(route('master.project.index'));

        $response->assertStatus(200);
        $response->assertSee('TKDN Jasa');
    }

    /** @test */
    public function it_displays_project_type_options_in_create_view()
    {
        $response = $this->get(route('master.project.create'));

        $response->assertStatus(200);
        $response->assertSee('TKDN Jasa (Form 3.1 - 3.5)');
        $response->assertSee('TKDN Barang & Jasa (Form 4.1 - 4.7)');
    }

    /** @test */
    public function it_displays_project_type_options_in_edit_view()
    {
        $project = Project::factory()->create(['project_type' => 'tkdn_jasa']);

        $response = $this->get(route('master.project.edit', $project));

        $response->assertStatus(200);
        $response->assertSee('TKDN Jasa (Form 3.1 - 3.5)');
        $response->assertSee('TKDN Barang & Jasa (Form 4.1 - 4.7)');
        $response->assertSee('tkdn_jasa');
    }

    /** @test */
    public function it_can_import_projects_with_project_type()
    {
        $excelData = [
            ['Project A', 'tkdn_jasa', 'draft', '2024-01-01', '2024-12-31', 'Description A', 'Company A', 'Jakarta'],
            ['Project B', 'tkdn_barang_jasa', 'on_progress', '2024-06-01', '2024-11-30', 'Description B', 'Company B', 'Bandung'],
        ];

        // Create a temporary Excel file
        $tempFile = tempnam(sys_get_temp_dir(), 'test_import');
        $file = fopen($tempFile, 'w');
        fputcsv($file, ['Name', 'Project Type', 'Status', 'Start Date', 'End Date', 'Description', 'Company', 'Location']);
        foreach ($excelData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        $response = $this->post(route('master.project.import'), [
            'excel_file' => new \Illuminate\Http\UploadedFile($tempFile, 'test.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
        ]);

        $response->assertRedirect(route('master.project.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('projects', [
            'name' => 'Project A',
            'project_type' => 'tkdn_jasa',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Project B',
            'project_type' => 'tkdn_barang_jasa',
        ]);

        unlink($tempFile);
    }
}
