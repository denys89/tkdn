<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    public function test_material_index_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('master.material.index'))
            ->assertStatus(200)
            ->assertSee('Material');
    }

    public function test_material_can_be_created()
    {
        $user = User::factory()->create();
        $category = \App\Models\Category::factory()->create();
        $data = [
            'name' => 'Test Material',
            'category_id' => $category->id,
            'specification' => 'Spec',
            'unit' => 'Kg',
            'price' => 5000,
            'tkdn' => 70,
        ];
        $this->actingAs($user)
            ->post(route('master.material.store'), $data)
            ->assertRedirect(route('master.material.index'));
        $this->assertDatabaseHas('material', ['name' => 'Test Material']);
    }

    public function test_material_can_be_updated()
    {
        $user = User::factory()->create();
        $category = \App\Models\Category::factory()->create();
        $material = Material::factory()->create(['category_id' => $category->id]);
        $this->actingAs($user)
            ->put(route('master.material.update', $material), [
                'name' => 'Updated',
                'category_id' => $material->category_id,
                'specification' => $material->specification,
                'unit' => $material->unit,
                'price' => $material->price,
                'tkdn' => $material->tkdn,
            ])
            ->assertRedirect(route('master.material.index'));
        $this->assertDatabaseHas('material', ['name' => 'Updated']);
    }

    public function test_material_can_be_deleted()
    {
        $user = User::factory()->create();
        $material = Material::factory()->create();
        $this->actingAs($user)
            ->delete(route('master.material.destroy', $material))
            ->assertRedirect(route('master.material.index'));
        $this->assertDatabaseMissing('material', ['id' => $material->id]);
    }
}
