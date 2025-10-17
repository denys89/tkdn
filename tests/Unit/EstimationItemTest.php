<?php

namespace Tests\Unit;

use App\Models\Equipment;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\Material;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstimationItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_estimation_item_fillable_fields()
    {
        $data = [
            'estimation_id' => '01K32ZZDAXSGD35CYTVB9F6ZC8',
            'category' => 'worker',
            'reference_id' => '01K32ZZD8WGSJ1XKYN3GKG2RM9',
            'code' => 'W001',
            'coefficient' => 2.0,
            'unit_price' => 50000,
            'total_price' => 100000,
        ];
        $item = new EstimationItem($data);
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $item->$key);
        }
    }

    public function test_estimation_item_belongs_to_estimation()
    {
        // Buat estimation manual
        $estimation = Estimation::create([
            'code' => 'A.1.1',
            'title' => 'Test Estimation',
            'total' => 10000,
            'margin' => 15,
            'total_unit_price' => 11500,
        ]);

        $item = EstimationItem::create([
            'estimation_id' => $estimation->id,
            'category' => 'worker',
            'reference_id' => '01K32ZZD8WGSJ1XKYN3GKG2RM9',
            'code' => 'W001',
            'coefficient' => 2.0,
            'unit_price' => 50000,
            'total_price' => 100000,
        ]);

        $this->assertInstanceOf(Estimation::class, $item->estimation);
        $this->assertEquals($estimation->id, $item->estimation->id);
    }

    public function test_estimation_item_worker_relation()
    {
        // Buat worker manual
        $worker = Worker::create([
            'code' => 'WK-001',
            'name' => 'Test Worker',
            'unit' => 'hari',
            'category_id' => '01K32ZZD8WGSJ1XKYN3GKG2RM9',
            'price' => 100000,
            'tkdn' => 1,
            'location' => 'Jakarta',
        ]);

        $item = EstimationItem::create([
            'estimation_id' => '01K32ZZDAXSGD35CYTVB9F6ZC8',
            'category' => 'worker',
            'reference_id' => $worker->id,
            'code' => 'W001',
            'coefficient' => 2.0,
            'unit_price' => 50000,
            'total_price' => 100000,
        ]);

        $this->assertInstanceOf(Worker::class, $item->worker);
        $this->assertEquals($worker->id, $item->worker->id);
    }

    public function test_estimation_item_material_relation()
    {
        // Buat material manual
        $material = Material::create([
            'code' => 'MT-001',
            'name' => 'Test Material',
            'specification' => 'Test Spec',
            'category_id' => '01K32ZZD8VZ1NJ056K2RQ7TWKE',
            'brand' => 'Test Brand',
            'tkdn' => 1,
            'price' => 50000,
            'unit' => 'kg',
            'link' => 'https://test.com',
            'price_inflasi' => 50000,
            'description' => 'Test Description',
            'location' => 'Jakarta',
        ]);

        $item = EstimationItem::create([
            'estimation_id' => '01K32ZZDAXSGD35CYTVB9F6ZC8',
            'category' => 'material',
            'reference_id' => $material->id,
            'code' => 'M001',
            'coefficient' => 1.5,
            'unit_price' => 30000,
            'total_price' => 45000,
        ]);

        $this->assertInstanceOf(Material::class, $item->material);
        $this->assertEquals($material->id, $item->material->id);
    }

    public function test_estimation_item_equipment_relation()
    {
        // Buat equipment manual
        $equipment = Equipment::create([
            'code' => 'EQ-001',
            'name' => 'Test Equipment',
            'category_id' => '01K32ZZD8X3MBS0FEAQSTQ4SHM',
            'tkdn' => 85.5,
            'period' => 6,
            'price' => 500000,
            'description' => 'Test Description',
            'location' => 'Jakarta',
        ]);

        $item = EstimationItem::create([
            'estimation_id' => '01K32ZZDAXSGD35CYTVB9F6ZC8',
            'category' => 'equipment',
            'reference_id' => $equipment->id,
            'code' => 'E001',
            'coefficient' => 0.8,
            'unit_price' => 200000,
            'total_price' => 160000,
        ]);

        $this->assertInstanceOf(Equipment::class, $item->equipment);
        $this->assertEquals($equipment->id, $item->equipment->id);
    }
}
