<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkerTest extends TestCase
{
    use RefreshDatabase;

    public function test_worker_index_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('master.worker.index'))
            ->assertStatus(200)
            ->assertSee('Worker');
    }

    public function test_worker_can_be_created()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Worker',
            'unit' => 'Orang',
            'price' => 10000,
            'tkdn' => 80,
        ];
        $this->actingAs($user)
            ->post(route('master.worker.store'), $data)
            ->assertRedirect(route('master.worker.index'));
        $this->assertDatabaseHas('workers', ['name' => 'Test Worker']);
    }

    public function test_worker_can_be_updated()
    {
        $user = User::factory()->create();
        $worker = Worker::factory()->create();
        $this->actingAs($user)
            ->put(route('master.worker.update', $worker), [
                'name' => 'Updated',
                'unit' => $worker->unit,
                'price' => $worker->price,
                'tkdn' => $worker->tkdn,
            ])
            ->assertRedirect(route('master.worker.index'));
        $this->assertDatabaseHas('workers', ['name' => 'Updated']);
    }

    public function test_worker_can_be_deleted()
    {
        $user = User::factory()->create();
        $worker = Worker::factory()->create();
        $this->actingAs($user)
            ->delete(route('master.worker.destroy', $worker))
            ->assertRedirect(route('master.worker.index'));
        $this->assertDatabaseMissing('workers', ['id' => $worker->id]);
    }
} 