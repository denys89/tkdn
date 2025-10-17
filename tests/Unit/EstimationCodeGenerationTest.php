<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Estimation;
use App\Models\Worker;
use App\Models\Material;
use App\Models\Equipment;
use App\Models\Category;
use App\Http\Controllers\EstimationController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstimationCodeGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed categories
        $this->artisan('db:seed', ['--class' => 'CategorySeeder']);
        
        // Seed sample data
        $this->artisan('db:seed', ['--class' => 'WorkerSeeder']);
        $this->artisan('db:seed', ['--class' => 'MaterialSeeder']);
        $this->artisan('db:seed', ['--class' => 'EquipmentSeeder']);
    }

    public function test_generate_estimation_code_with_worker_and_material()
    {
        $controller = new EstimationController();
        
        $items = [
            [
                'category' => 'worker',
                'reference_id' => Worker::where('code', 'PJ001')->first()->id,
            ],
            [
                'category' => 'material',
                'reference_id' => Material::where('code', 'MT001')->first()->id,
            ]
        ];

        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateEstimationCode');
        $method->setAccessible(true);

        $code = $method->invoke($controller, $items);

        // Format yang diharapkan: AHS.PJ.MT.PJ001.MT001.{timestamp}
        $this->assertStringStartsWith('AHS.PJ.MT.PJ001.MT001.', $code);
        $this->assertMatchesRegularExpression('/^AHS\.PJ\.MT\.PJ001\.MT001\.\d{14}$/', $code);
    }

    public function test_generate_estimation_code_with_worker_and_equipment()
    {
        $controller = new EstimationController();
        
        $items = [
            [
                'category' => 'worker',
                'reference_id' => Worker::where('code', 'PJ001')->first()->id,
            ],
            [
                'category' => 'equipment',
                'reference_id' => Equipment::where('code', 'EQ001')->first()->id,
            ]
        ];

        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateEstimationCode');
        $method->setAccessible(true);

        $code = $method->invoke($controller, $items);

        // Format yang diharapkan: AHS.PJ.EQ.PJ001.EQ001.{timestamp}
        $this->assertStringStartsWith('AHS.PJ.EQ.PJ001.EQ001.', $code);
        $this->assertMatchesRegularExpression('/^AHS\.PJ\.EQ\.PJ001\.EQ001\.\d{14}$/', $code);
    }

    public function test_generate_estimation_code_with_multiple_materials()
    {
        $controller = new EstimationController();
        
        $items = [
            [
                'category' => 'material',
                'reference_id' => Material::where('code', 'MT001')->first()->id,
            ],
            [
                'category' => 'material',
                'reference_id' => Material::where('code', 'MT002')->first()->id,
            ]
        ];

        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateEstimationCode');
        $method->setAccessible(true);

        $code = $method->invoke($controller, $items);

        // Format yang diharapkan: AHS.MT.MT001.MT002.{timestamp}
        $this->assertStringStartsWith('AHS.MT.MT001.MT002.', $code);
        $this->assertMatchesRegularExpression('/^AHS\.MT\.MT001\.MT002\.\d{14}$/', $code);
    }

    public function test_generate_estimation_code_with_empty_items()
    {
        $controller = new EstimationController();
        
        $items = [];

        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateEstimationCode');
        $method->setAccessible(true);

        $code = $method->invoke($controller, $items);

        // Format fallback yang diharapkan: AHS.{YYYYMMDD}.{0001}
        $this->assertStringStartsWith('AHS.' . date('Ymd') . '.', $code);
        $this->assertMatchesRegularExpression('/^AHS\.\d{8}\.\d{4}$/', $code);
    }

    public function test_generate_estimation_code_with_invalid_reference_id()
    {
        $controller = new EstimationController();
        
        $items = [
            [
                'category' => 'worker',
                'reference_id' => 'invalid-id',
            ]
        ];

        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('generateEstimationCode');
        $method->setAccessible(true);

        $code = $method->invoke($controller, $items);

        // Format yang diharapkan: AHS.PJ.{timestamp} (karena kategori valid tapi item tidak)
        $this->assertStringStartsWith('AHS.PJ.', $code);
        $this->assertMatchesRegularExpression('/^AHS\.PJ\.\d{14}$/', $code);
    }

    public function test_category_code_mapping()
    {
        $controller = new EstimationController();
        
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('getCategoryCode');
        $method->setAccessible(true);

        $this->assertEquals('PJ', $method->invoke($controller, 'worker'));
        $this->assertEquals('MT', $method->invoke($controller, 'material'));
        $this->assertEquals('EQ', $method->invoke($controller, 'equipment'));
        $this->assertNull($method->invoke($controller, 'invalid'));
    }

    public function test_item_code_retrieval()
    {
        $controller = new EstimationController();
        
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('getItemCode');
        $method->setAccessible(true);

        $worker = Worker::where('code', 'PJ001')->first();
        $material = Material::where('code', 'MT001')->first();
        $equipment = Equipment::where('code', 'EQ001')->first();

        $this->assertEquals('PJ001', $method->invoke($controller, 'worker', $worker->id));
        $this->assertEquals('MT001', $method->invoke($controller, 'material', $material->id));
        $this->assertEquals('EQ001', $method->invoke($controller, 'equipment', $equipment->id));
        $this->assertNull($method->invoke($controller, 'worker', 'invalid-id'));
    }

    public function test_controller_data_includes_code_field()
    {
        $controller = new EstimationController();
        
        // Test create method data
        $createResponse = $controller->create();
        $createViewData = $createResponse->getData();
        
        // Verify workers data includes code
        $workers = $createViewData['workers'] ?? [];
        if (!empty($workers)) {
            $firstWorker = $workers[0];
            $this->assertArrayHasKey('code', $firstWorker);
            $this->assertNotEmpty($firstWorker['code']);
        }
        
        // Verify materials data includes code
        $materials = $createViewData['materials'] ?? [];
        if (!empty($materials)) {
            $firstMaterial = $materials[0];
            $this->assertArrayHasKey('code', $firstMaterial);
            $this->assertNotEmpty($firstMaterial['code']);
        }
        
        // Verify equipment data includes code
        $equipment = $createViewData['equipment'] ?? [];
        if (!empty($equipment)) {
            $firstEquipment = $equipment[0];
            $this->assertArrayHasKey('code', $firstEquipment);
            $this->assertNotEmpty($firstEquipment['code']);
        }
    }

    public function test_auto_fill_code_when_saving_items()
    {
        $controller = new EstimationController();
        
        // Create a test estimation
        $estimation = Estimation::create([
            'title' => 'Test Estimation',
            'code' => 'AHS.TEST.001',
            'total' => 0,
            'margin' => 15,
            'total_unit_price' => 0,
        ]);
        
        // Get test data
        $worker = Worker::where('code', 'PJ001')->first();
        $material = Material::where('code', 'MT001')->first();
        
        $items = [
            [
                'category' => 'worker',
                'reference_id' => $worker->id,
                'code' => '', // Empty code to test auto-fill
                'coefficient' => 1.0,
                'unit_price' => 100000,
                'total_price' => 100000,
            ],
            [
                'category' => 'material',
                'reference_id' => $material->id,
                'code' => '', // Empty code to test auto-fill
                'coefficient' => 2.0,
                'unit_price' => 50000,
                'total_price' => 100000,
            ]
        ];
        
        // Use reflection to access private method
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('syncEstimationItems');
        $method->setAccessible(true);
        
        // Call the method
        $method->invoke($controller, $estimation, $items);
        
        // Verify that codes were auto-filled
        $estimation->load('items');
        
        $this->assertEquals('PJ001', $estimation->items[0]->code);
        $this->assertEquals('MT001', $estimation->items[1]->code);
        
        // Clean up
        $estimation->delete();
    }
} 