<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServiceGenerateFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_form_3_1(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->post(route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.1']));

        $response->assertRedirect(route('service.show', $service));
        $response->assertSessionHas('success', 'Form 3.1 TKDN berhasil dibuat.');
    }

    public function test_can_generate_form_3_2(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->post(route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.2']));

        $response->assertRedirect(route('service.show', $service));
        $response->assertSessionHas('success', 'Form 3.2 TKDN berhasil dibuat.');
    }

    public function test_can_generate_form_3_3(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->post(route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.3']));

        $response->assertRedirect(route('service.show', $service));
        $response->assertSessionHas('success', 'Form 3.3 TKDN berhasil dibuat.');
    }

    public function test_can_generate_form_3_4(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->post(route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.4']));

        $response->assertRedirect(route('service.show', $service));
        $response->assertSessionHas('success', 'Form 3.4 TKDN berhasil dibuat.');
    }

    public function test_can_generate_form_3_5(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->post(route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.5']));

        $response->assertRedirect(route('service.show', $service));
        $response->assertSessionHas('success', 'Form 3.5 TKDN berhasil dibuat.');
    }

    public function test_rejects_invalid_form_number(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        $response = $this->post(route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.6']));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Nomor form TKDN tidak valid.');
    }

    public function test_generate_form_creates_service_items(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'draft',
        ]);

        // Mock HPP items data
        DB::table('hpp_items')->insert([
            'id' => 'test-hpp-1',
            'hpp_id' => 'test-hpp',
            'description' => 'Test Item 1',
            'tkdn_classification' => '3.1',
            'volume' => 1,
            'duration' => 12,
            'duration_unit' => 'bulan',
            'total_price' => 1000000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post(route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.1']));

        $response->assertRedirect(route('service.show', $service));

        // Check if service items were created
        $this->assertDatabaseHas('service_items', [
            'service_id' => $service->id,
            'tkdn_classification' => '3.1',
        ]);
    }
}
