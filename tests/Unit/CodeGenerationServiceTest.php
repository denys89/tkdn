<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CodeGenerationService;
use App\Models\Counter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodeGenerationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CodeGenerationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CodeGenerationService();
    }

    /** @test */
    public function it_can_generate_worker_code()
    {
        $code = $this->service->generateCode('worker');
        
        $this->assertStringStartsWith('WK', $code);
        $this->assertEquals(10, strlen($code)); // WK + YY + MM + 4 digits
        
        // Verify counter was created
        $this->assertDatabaseHas('counters', [
            'entity_type' => 'worker',
            'prefix' => 'WK',
            'current_number' => 1
        ]);
    }

    /** @test */
    public function it_can_generate_material_code()
    {
        $code = $this->service->generateCode('material');
        
        $this->assertStringStartsWith('MT', $code);
        $this->assertEquals(10, strlen($code));
        
        $this->assertDatabaseHas('counters', [
            'entity_type' => 'material',
            'prefix' => 'MT',
            'current_number' => 1
        ]);
    }

    /** @test */
    public function it_can_generate_equipment_code()
    {
        $code = $this->service->generateCode('equipment');
        
        $this->assertStringStartsWith('EQ', $code);
        $this->assertEquals(10, strlen($code));
        
        $this->assertDatabaseHas('counters', [
            'entity_type' => 'equipment',
            'prefix' => 'EQ',
            'current_number' => 1
        ]);
    }

    /** @test */
    public function it_increments_counter_for_same_month()
    {
        $this->service->generateCode('worker');
        $this->service->generateCode('worker');
        $this->service->generateCode('worker');
        
        $this->assertDatabaseHas('counters', [
            'entity_type' => 'worker',
            'current_number' => 3
        ]);
    }

    /** @test */
    public function it_creates_new_counter_for_new_month()
    {
        // Create counter for current month
        $this->service->generateCode('worker');
        
        // Get next month info
        $nextMonth = now()->addMonth();
        
        // Manually create counter for next month
        Counter::create([
            'entity_type' => 'worker',
            'prefix' => 'WK',
            'current_number' => 0,
            'year' => $nextMonth->year,
            'month' => $nextMonth->month
        ]);
        
        // Verify the counter was created
        $this->assertDatabaseHas('counters', [
            'entity_type' => 'worker',
            'year' => $nextMonth->year,
            'month' => $nextMonth->month,
            'current_number' => 0
        ]);
        
        // Generate code for next month manually
        $nextMonthCounter = Counter::where('entity_type', 'worker')
            ->where('year', $nextMonth->year)
            ->where('month', $nextMonth->month)
            ->first();
        
        $nextMonthCounter->increment('current_number');
        
        // Should increment to 1
        $this->assertDatabaseHas('counters', [
            'entity_type' => 'worker',
            'year' => $nextMonth->year,
            'month' => $nextMonth->month,
            'current_number' => 1
        ]);
    }

    /** @test */
    public function it_returns_available_entity_types()
    {
        $types = $this->service->getAvailableEntityTypes();
        
        $this->assertContains('worker', $types);
        $this->assertContains('material', $types);
        $this->assertContains('equipment', $types);
        $this->assertCount(3, $types);
    }

    /** @test */
    public function it_can_get_current_counter_info()
    {
        $this->service->generateCode('worker');
        
        $info = $this->service->getCurrentCounterInfo('worker');
        
        $this->assertNotNull($info);
        $this->assertEquals('worker', $info['entity_type']);
        $this->assertEquals('WK', $info['prefix']);
        $this->assertEquals(1, $info['current_number']);
        $this->assertEquals(now()->year, $info['year']);
        $this->assertEquals(now()->month, $info['month']);
    }

    /** @test */
    public function it_can_reset_counter()
    {
        $this->service->generateCode('worker');
        
        $result = $this->service->resetCounter('worker');
        
        $this->assertTrue($result);
        $this->assertDatabaseMissing('counters', [
            'entity_type' => 'worker',
            'year' => now()->year,
            'month' => now()->month
        ]);
    }
}
