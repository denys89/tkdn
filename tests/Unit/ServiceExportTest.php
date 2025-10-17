<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Services\ServiceExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_export_service_can_be_instantiated(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'generated',
        ]);

        $exportService = new ServiceExportService($service, '3.1');

        $this->assertInstanceOf(ServiceExportService::class, $exportService);
    }

    public function test_export_service_creates_excel_file(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'generated',
        ]);

        // Create some service items
        ServiceItem::factory()->count(3)->create([
            'service_id' => $service->id,
            'tkdn_classification' => '3.1',
        ]);

        $exportService = new ServiceExportService($service, '3.1');
        $filepath = $exportService->export();

        $this->assertFileExists($filepath);
        $this->assertStringEndsWith('.xlsx', $filepath);

        // Clean up
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    public function test_export_service_with_all_classifications(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'generated',
        ]);

        // Create service items with different classifications
        ServiceItem::factory()->create([
            'service_id' => $service->id,
            'tkdn_classification' => '3.1',
        ]);
        ServiceItem::factory()->create([
            'service_id' => $service->id,
            'tkdn_classification' => '3.2',
        ]);

        $exportService = new ServiceExportService($service, 'all');
        $filepath = $exportService->export();

        $this->assertFileExists($filepath);

        // Clean up
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    public function test_export_service_handles_empty_data(): void
    {
        $project = Project::factory()->create();
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'status' => 'generated',
        ]);

        // Create HPP and HPP items to provide data for export
        $hpp = \App\Models\Hpp::factory()->create([
            'project_id' => $project->id,
        ]);

        \App\Models\HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'tkdn_classification' => '3.1',
            'description' => 'Test Item',
            'total_price' => 1000000,
        ]);

        $exportService = new ServiceExportService($service, '3.1');
        $filepath = $exportService->export();

        $this->assertFileExists($filepath);

        // Clean up
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}
