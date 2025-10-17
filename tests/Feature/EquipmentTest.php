<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_equipment_index_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('master.equipment.index'))
            ->assertStatus(200)
            ->assertSee('Equipment');
    }

    public function test_equipment_can_be_created()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Equipment',
            'category_id' => null,
            'tkdn' => 50,
            'equipment_type' => 'reusable',
            'period' => 10,
            'price' => 100000,
            'description' => 'Test Desc',
        ];
        $this->actingAs($user)
            ->post(route('master.equipment.store'), $data)
            ->assertRedirect(route('master.equipment.index'));
        $this->assertDatabaseHas('equipment', ['name' => 'Test Equipment']);
    }

    public function test_equipment_can_be_updated()
    {
        $user = User::factory()->create();
        $equipment = Equipment::factory()->create();
        $this->actingAs($user)
            ->put(route('master.equipment.update', $equipment), [
                'name' => 'Updated',
                'category_id' => $equipment->category_id,
                'tkdn' => $equipment->tkdn,
                'equipment_type' => 'reusable',
                'period' => $equipment->period,
                'price' => $equipment->price,
                'description' => $equipment->description,
            ])
            ->assertRedirect(route('master.equipment.index'));
        $this->assertDatabaseHas('equipment', ['name' => 'Updated']);
    }

    public function test_equipment_can_be_deleted()
    {
        $user = User::factory()->create();
        $equipment = Equipment::factory()->create();
        $this->actingAs($user)
            ->delete(route('master.equipment.destroy', $equipment))
            ->assertRedirect(route('master.equipment.index'));
        $this->assertDatabaseMissing('equipment', ['id' => $equipment->id]);
    }
}
