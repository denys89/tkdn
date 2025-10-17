<?php

namespace Tests\Unit;

use App\Http\Controllers\HppController;
use App\Models\Equipment;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\Material;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class HppAhsItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_ahs_items_returns_estimation_with_items()
    {
        // Create test data manually
        $estimation = Estimation::create([
            'code' => 'AHS-001',
            'title' => 'Test AHS',
            'description' => 'Test Description',
            'total_unit_price' => 100000,
        ]);

        $estimationItem = EstimationItem::create([
            'estimation_id' => $estimation->id,
            'category' => 'worker',
            'reference_id' => 'test-ref-id',
            'code' => 'ITEM-001',
            'unit_price' => 100000,
            'total_price' => 100000,
            'coefficient' => 1.0,
            'tkdn_classification' => '3.5',
            'tkdn_value' => 65.5,
        ]);

        // Create controller instance
        $controller = new HppController;

        // Create mock request
        $request = new Request;
        $request->merge(['estimation_id' => $estimation->id]);

        // Call method
        $response = $controller->getAhsItems($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertArrayHasKey('estimation', $data);
        $this->assertArrayHasKey('items', $data);

        $this->assertEquals($estimation->code, $data['estimation']['code']);
        $this->assertEquals($estimation->title, $data['estimation']['title']);
        $this->assertCount(1, $data['items']);

        $item = $data['items'][0];
        $this->assertEquals($estimationItem->id, $item['id']);
        $this->assertEquals($estimationItem->tkdn_classification, $item['tkdn_classification']);
        $this->assertEquals($estimationItem->tkdn_value, $item['tkdn_value']);
    }

    public function test_get_ahs_items_returns_404_for_invalid_estimation()
    {
        // Create controller instance
        $controller = new HppController;

        // Create mock request with invalid ID
        $request = new Request;
        $request->merge(['estimation_id' => 99999]);

        // Call method
        $response = $controller->getAhsItems($request);

        // Assert response
        $this->assertEquals(404, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Estimation tidak ditemukan', $data['error']);
    }

    public function test_get_ahs_data_returns_formatted_data()
    {
        // Create test data
        $worker = Worker::factory()->create([
            'code' => 'WK-001',
            'name' => 'Test Worker',
            'price' => 50000,
            'tkdn' => 75,
        ]);

        $material = Material::factory()->create([
            'code' => 'MT-001',
            'name' => 'Test Material',
            'price' => 25000,
            'tkdn' => 60,
        ]);

        $equipment = Equipment::factory()->create([
            'code' => 'EQ-001',
            'name' => 'Test Equipment',
            'price' => 100000,
            'tkdn' => 80,
        ]);

        // Create controller instance
        $controller = new HppController;

        // Call method using reflection to access private method
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('getAhsData');
        $method->setAccessible(true);

        $result = $method->invoke($controller);

        // Assert result structure
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // Find worker data
        $workerData = collect($result)->firstWhere('type', 'worker');
        $this->assertNotNull($workerData);
        $this->assertEquals($worker->id, $workerData['id']);
        $this->assertEquals($worker->code, $workerData['code']);
        $this->assertEquals($worker->name, $workerData['title']);

        // Find material data
        $materialData = collect($result)->firstWhere('type', 'material');
        $this->assertNotNull($materialData);
        $this->assertEquals($material->id, $materialData['id']);
        $this->assertEquals($material->code, $materialData['code']);
        $this->assertEquals($material->name, $materialData['title']);

        // Find equipment data
        $equipmentData = collect($result)->firstWhere('type', 'equipment');
        $this->assertNotNull($equipmentData);
        $this->assertEquals($equipment->id, $equipmentData['id']);
        $this->assertEquals($equipment->code, $equipmentData['code']);
        $this->assertEquals($equipment->name, $equipmentData['title']);
    }
}
