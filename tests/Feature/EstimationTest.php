<?php

namespace Tests\Feature;

use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstimationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ahs_index_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('master.estimation.index'))
            ->assertStatus(200)
            ->assertSee('AHS');
    }

    public function test_ahs_can_be_created_with_items()
    {
        $user = User::factory()->create();
        $data = [
            'title' => 'AHS Pondasi',
            'total' => 100000,
            'margin' => 10,
            'total_unit_price' => 110000,
            'items' => [
                [
                    'category' => 'worker',
                    'reference_id' => null,
                    'code' => 'W001',
                    'coefficient' => 2,
                    'unit_price' => 50000,
                    'total_price' => 100000,
                ],
            ],
        ];
        $response = $this->actingAs($user)
            ->post(route('master.estimation.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('estimations', ['title' => 'AHS Pondasi']);
        $this->assertDatabaseHas('estimation_items', ['code' => 'W001']);
    }

    public function test_ahs_can_be_updated()
    {
        $user = User::factory()->create();
        $estimation = Estimation::factory()->create(['title' => 'AHS Lama']);
        $this->actingAs($user)
            ->put(route('master.estimation.update', $estimation), [
                'title' => 'AHS Baru',
                'total' => 200000,
                'total_unit_price' => 200000,
                'items' => [],
            ])
            ->assertRedirect();
        $this->assertDatabaseHas('estimations', ['title' => 'AHS Baru']);
    }

    public function test_ahs_can_be_deleted()
    {
        $user = User::factory()->create();
        $estimation = Estimation::factory()->create();
        $this->actingAs($user)
            ->delete(route('master.estimation.destroy', $estimation))
            ->assertRedirect();
        $this->assertDatabaseMissing('estimations', ['id' => $estimation->id]);
    }

    public function test_ahs_validation_error_on_empty_title()
    {
        $user = User::factory()->create();
        $data = [
            'title' => '',
            'total' => 100000,
            'total_unit_price' => 100000,
            'items' => [],
        ];
        $response = $this->actingAs($user)
            ->post(route('master.estimation.store'), $data);
        $response->assertSessionHasErrors('title');
    }

    public function test_estimation_has_many_items_relation()
    {
        $estimation = Estimation::factory()->create();
        $item1 = EstimationItem::factory()->create(['estimation_id' => $estimation->id]);
        $item2 = EstimationItem::factory()->create(['estimation_id' => $estimation->id]);
        $this->assertCount(2, $estimation->items);
        $this->assertTrue($estimation->items->contains($item1));
        $this->assertTrue($estimation->items->contains($item2));
    }

    public function test_ahs_item_validation_error_on_missing_category()
    {
        $user = \App\Models\User::factory()->create();
        $data = [
            'title' => 'AHS Test',
            'total' => 100000,
            'total_unit_price' => 100000,
            'items' => [
                [
                    'category' => '', // missing
                    'reference_id' => null,
                    'code' => 'W001',
                    'coefficient' => 2,
                    'unit_price' => 50000,
                    'total_price' => 100000,
                ],
            ],
        ];
        $response = $this->actingAs($user)
            ->post(route('master.estimation.store'), $data);
        $response->assertSessionHasErrors('items.0.category');
    }

    public function test_ahs_item_can_be_updated()
    {
        $user = \App\Models\User::factory()->create();
        $estimation = \App\Models\Estimation::factory()->create();
        $item = \App\Models\EstimationItem::factory()->create(['estimation_id' => $estimation->id, 'code' => 'TEST-001']);
        $data = [
            'title' => $estimation->title,
            'total' => $estimation->total,
            'total_unit_price' => $estimation->total_unit_price,
            'items' => [
                [
                    'id' => $item->id,
                    'category' => $item->category,
                    'reference_id' => $item->reference_id,
                    'code' => 'TEST-002',
                    'coefficient' => $item->coefficient,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ],
            ],
        ];
        $this->actingAs($user)
            ->put(route('master.estimation.update', $estimation), $data)
            ->assertRedirect();
        $this->assertDatabaseHas('estimation_items', ['id' => $item->id, 'code' => 'TEST-002']);
    }

    public function test_ahs_item_can_be_deleted_via_update()
    {
        $user = \App\Models\User::factory()->create();
        $estimation = \App\Models\Estimation::factory()->create();
        $item = \App\Models\EstimationItem::factory()->create(['estimation_id' => $estimation->id]);
        // Update tanpa menyertakan item tersebut
        $data = [
            'title' => $estimation->title,
            'total' => $estimation->total,
            'total_unit_price' => $estimation->total_unit_price,
            'items' => [],
        ];
        $this->actingAs($user)
            ->put(route('master.estimation.update', $estimation), $data)
            ->assertRedirect();
        $this->assertDatabaseMissing('estimation_items', ['id' => $item->id]);
    }
}
