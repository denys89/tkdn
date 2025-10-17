<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\EstimationItem;
use App\Models\Hpp;
use App\Models\HppItem;
use App\Models\Material;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HppProjectTypeFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_filters_hpp_items_for_tkdn_jasa_project()
    {
        // Create TKDN Jasa project
        $project = Project::factory()->create(['project_type' => 'tkdn_jasa']);
        $hpp = Hpp::factory()->create(['project_id' => $project->id]);

        // Create workers with TKDN Jasa classifications (using integer values)
        $worker31 = Worker::factory()->create(['classification_tkdn' => 1]); // Overhead & Manajemen
        $worker32 = Worker::factory()->create(['classification_tkdn' => 2]); // Alat Kerja / Fasilitas

        // Create workers with TKDN Barang Jasa classifications (should be filtered out)
        $worker41 = Worker::factory()->create(['classification_tkdn' => 5]); // Material (Bahan Baku)
        $worker42 = Worker::factory()->create(['classification_tkdn' => 6]); // Peralatan (Barang Jadi)

        // Create estimation items
        $estimationItem31 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker31->id,
        ]);

        $estimationItem32 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker32->id,
        ]);

        $estimationItem41 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker41->id,
        ]);

        $estimationItem42 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker42->id,
        ]);

        // Create HPP items with different classifications
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem31->id,
            'description' => 'TKDN Jasa Item 3.1',
            'total_price' => 1000000,
        ]);

        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem32->id,
            'description' => 'TKDN Jasa Item 3.2',
            'total_price' => 2000000,
        ]);

        // Create items that should be filtered out (4.x classifications)
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem41->id,
            'description' => 'TKDN Barang Jasa Item 4.1',
            'total_price' => 3000000,
        ]);

        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem42->id,
            'description' => 'TKDN Barang Jasa Item 4.2',
            'total_price' => 4000000,
        ]);

        // Test getHppData API
        $response = $this->get("/service/get-hpp-data?project_id={$project->id}");

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertTrue($data['success']);
        $this->assertCount(1, $data['data']);

        $hppData = $data['data'][0];
        $this->assertEquals(2, $hppData['items_count']); // Only 3.1 and 3.2 items
        $this->assertEquals('tkdn_jasa', $hppData['project_type']);

        // Check tkdn_breakdown only contains TKDN Jasa classifications
        $this->assertArrayHasKey('Overhead & Manajemen', $hppData['tkdn_breakdown']);
        $this->assertArrayHasKey('Alat Kerja / Fasilitas', $hppData['tkdn_breakdown']);
        $this->assertArrayNotHasKey('Material (Bahan Baku)', $hppData['tkdn_breakdown']);
        $this->assertArrayNotHasKey('Peralatan (Barang Jadi)', $hppData['tkdn_breakdown']);

        // Check counts and costs
        $this->assertEquals(1, $hppData['tkdn_breakdown']['Overhead & Manajemen']['count']);
        $this->assertEquals(1000000, $hppData['tkdn_breakdown']['Overhead & Manajemen']['total_cost']);
        $this->assertEquals(1, $hppData['tkdn_breakdown']['Alat Kerja / Fasilitas']['count']);
        $this->assertEquals(2000000, $hppData['tkdn_breakdown']['Alat Kerja / Fasilitas']['total_cost']);
    }

    /** @test */
    public function it_filters_hpp_items_for_tkdn_barang_jasa_project()
    {
        // Create TKDN Barang & Jasa project
        $project = Project::factory()->create(['project_type' => 'tkdn_barang_jasa']);
        $hpp = Hpp::factory()->create(['project_id' => $project->id]);

        // Create materials with TKDN Barang Jasa classifications (using integer values)
        $material41 = Material::factory()->create(['classification_tkdn' => 5]); // Material (Bahan Baku)
        $equipment42 = Equipment::factory()->create(['classification_tkdn' => 6]); // Peralatan (Barang Jadi)

        // Create workers with TKDN Jasa classifications (should be filtered out for tkdn_barang_jasa)
        // Note: These classifications are valid for both project types, so we need to use different approach
        // Let's create workers that are only valid for tkdn_jasa by using classifications that don't exist in tkdn_barang_jasa
        $worker31 = Worker::factory()->create(['classification_tkdn' => 1]); // Overhead & Manajemen
        $worker32 = Worker::factory()->create(['classification_tkdn' => 2]); // Alat Kerja / Fasilitas

        // Create estimation items
        $estimationItem41 = EstimationItem::factory()->create([
            'category' => 'material',
            'reference_id' => $material41->id,
        ]);

        $estimationItem42 = EstimationItem::factory()->create([
            'category' => 'equipment',
            'reference_id' => $equipment42->id,
        ]);

        $estimationItem31 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker31->id,
        ]);

        $estimationItem32 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker32->id,
        ]);

        // Create HPP items with different classifications
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem41->id,
            'description' => 'TKDN Barang Jasa Item 4.1',
            'total_price' => 1000000,
        ]);

        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem42->id,
            'description' => 'TKDN Barang Jasa Item 4.2',
            'total_price' => 2000000,
        ]);

        // Create items that should be filtered out (3.x classifications)
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem31->id,
            'description' => 'TKDN Jasa Item 3.1',
            'total_price' => 3000000,
        ]);

        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem32->id,
            'description' => 'TKDN Jasa Item 3.2',
            'total_price' => 4000000,
        ]);

        // Test getHppData API
        $response = $this->get("/service/get-hpp-data?project_id={$project->id}");

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertTrue($data['success']);
        $this->assertCount(1, $data['data']);

        $hppData = $data['data'][0];
        $this->assertEquals(4, $hppData['items_count']); // 4.1, 4.2, 4.3, and 4.4 items
        $this->assertEquals('tkdn_barang_jasa', $hppData['project_type']);

        // Check tkdn_breakdown contains TKDN Barang Jasa classifications
        $this->assertArrayHasKey('Material (Bahan Baku)', $hppData['tkdn_breakdown']);
        $this->assertArrayHasKey('Peralatan (Barang Jadi)', $hppData['tkdn_breakdown']);
        $this->assertArrayHasKey('Overhead & Manajemen', $hppData['tkdn_breakdown']); // Valid for form 4.3
        $this->assertArrayHasKey('Alat Kerja / Fasilitas', $hppData['tkdn_breakdown']); // Valid for form 4.4

        // Check counts and costs
        $this->assertEquals(1, $hppData['tkdn_breakdown']['Material (Bahan Baku)']['count']);
        $this->assertEquals(1000000, $hppData['tkdn_breakdown']['Material (Bahan Baku)']['total_cost']);
        $this->assertEquals(1, $hppData['tkdn_breakdown']['Peralatan (Barang Jadi)']['count']);
        $this->assertEquals(2000000, $hppData['tkdn_breakdown']['Peralatan (Barang Jadi)']['total_cost']);
        $this->assertEquals(1, $hppData['tkdn_breakdown']['Overhead & Manajemen']['count']);
        $this->assertEquals(3000000, $hppData['tkdn_breakdown']['Overhead & Manajemen']['total_cost']);
        $this->assertEquals(1, $hppData['tkdn_breakdown']['Alat Kerja / Fasilitas']['count']);
        $this->assertEquals(4000000, $hppData['tkdn_breakdown']['Alat Kerja / Fasilitas']['total_cost']);
    }

    /** @test */
    public function it_generates_service_with_only_matching_hpp_items()
    {
        // Create TKDN Jasa project
        $project = Project::factory()->create(['project_type' => 'tkdn_jasa']);
        $hpp = Hpp::factory()->create(['project_id' => $project->id]);

        // Create workers with TKDN Jasa classifications (should be included)
        $worker31 = Worker::factory()->create(['classification_tkdn' => 1]); // Overhead & Manajemen
        $worker32 = Worker::factory()->create(['classification_tkdn' => 2]); // Alat Kerja / Fasilitas

        // Create material with TKDN Barang Jasa classification (should be filtered out)
        $material41 = Material::factory()->create(['classification_tkdn' => 5]); // Material (Bahan Baku)

        // Create estimation items
        $estimationItem31 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker31->id,
        ]);

        $estimationItem32 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker32->id,
        ]);

        $estimationItem41 = EstimationItem::factory()->create([
            'category' => 'material',
            'reference_id' => $material41->id,
        ]);

        // Create HPP items with 3.x classifications (should be included)
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem31->id,
            'description' => 'TKDN Jasa Item 3.1',
            'total_price' => 1000000,
        ]);

        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem32->id,
            'description' => 'TKDN Jasa Item 3.2',
            'total_price' => 2000000,
        ]);

        // Create items with 4.x classifications (should be filtered out)
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem41->id,
            'description' => 'TKDN Barang Jasa Item 4.1',
            'total_price' => 3000000,
        ]);

        // Generate service
        $response = $this->post(route('service.store'), [
            'hpp_id' => $hpp->id,
        ]);

        $response->assertRedirect();

        $service = Service::latest()->first();
        $this->assertEquals('tkdn_jasa', $service->form_category);
        $this->assertEquals('project', $service->service_type);

        // Check that only 3.x service items were created
        $serviceItems = $service->items;
        $this->assertGreaterThan(0, $serviceItems->count());

        $classifications = $serviceItems->pluck('tkdn_classification')->unique()->toArray();
        $this->assertContains('3.1', $classifications);
        $this->assertContains('3.2', $classifications);
        $this->assertNotContains('4.1', $classifications);
    }

    /** @test */
    public function it_handles_empty_hpp_items_for_project_type()
    {
        // Create TKDN Jasa project
        $project = Project::factory()->create(['project_type' => 'tkdn_jasa']);
        $hpp = Hpp::factory()->create(['project_id' => $project->id]);

        // Create material with TKDN Barang Jasa classification (should be filtered out)
        $material41 = Material::factory()->create(['classification_tkdn' => 5]); // Material (Bahan Baku)

        $estimationItem41 = EstimationItem::factory()->create([
            'category' => 'material',
            'reference_id' => $material41->id,
        ]);

        // Create only 4.x items (should be filtered out)
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem41->id,
            'description' => 'TKDN Barang Jasa Item 4.1',
            'total_price' => 1000000,
        ]);

        // Test getHppData API
        $response = $this->get("/service/get-hpp-data?project_id={$project->id}");

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertTrue($data['success']);
        $this->assertCount(1, $data['data']);

        $hppData = $data['data'][0];
        $this->assertEquals(0, $hppData['items_count']); // No matching items
        $this->assertEquals('tkdn_jasa', $hppData['project_type']);
        $this->assertEmpty($hppData['tkdn_breakdown']);
    }

    /** @test */
    public function it_handles_mixed_classifications_correctly()
    {
        // Create TKDN Jasa project
        $project = Project::factory()->create(['project_type' => 'tkdn_jasa']);
        $hpp = Hpp::factory()->create(['project_id' => $project->id]);

        // Create workers with TKDN Jasa classifications
        $worker31 = Worker::factory()->create(['classification_tkdn' => 1]); // Overhead & Manajemen
        $worker32 = Worker::factory()->create(['classification_tkdn' => 2]); // Alat Kerja / Fasilitas

        // Create material with TKDN Barang Jasa classification (should be filtered out)
        $material41 = Material::factory()->create(['classification_tkdn' => 5]); // Material (Bahan Baku)

        // Create estimation items
        $estimationItem31 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker31->id,
        ]);

        $estimationItem32 = EstimationItem::factory()->create([
            'category' => 'worker',
            'reference_id' => $worker32->id,
        ]);

        $estimationItem41 = EstimationItem::factory()->create([
            'category' => 'material',
            'reference_id' => $material41->id,
        ]);

        // Create mixed items
        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem31->id,
            'description' => 'TKDN Jasa Item 3.1',
            'total_price' => 1000000,
        ]);

        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem41->id,
            'description' => 'TKDN Barang Jasa Item 4.1',
            'total_price' => 2000000,
        ]);

        HppItem::factory()->create([
            'hpp_id' => $hpp->id,
            'estimation_item_id' => $estimationItem32->id,
            'description' => 'TKDN Jasa Item 3.2',
            'total_price' => 3000000,
        ]);

        // Test getHppData API
        $response = $this->get("/service/get-hpp-data?project_id={$project->id}");

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertTrue($data['success']);
        $hppData = $data['data'][0];

        // Should only include TKDN Jasa items
        $this->assertEquals(2, $hppData['items_count']);
        $this->assertArrayHasKey('Overhead & Manajemen', $hppData['tkdn_breakdown']);
        $this->assertArrayHasKey('Alat Kerja / Fasilitas', $hppData['tkdn_breakdown']);
        $this->assertArrayNotHasKey('Material (Bahan Baku)', $hppData['tkdn_breakdown']);

        // Check total cost only includes filtered items
        $totalCost = $hppData['tkdn_breakdown']['Overhead & Manajemen']['total_cost'] + $hppData['tkdn_breakdown']['Alat Kerja / Fasilitas']['total_cost'];
        $this->assertEquals(4000000, $totalCost); // 1000000 + 3000000
    }
}
