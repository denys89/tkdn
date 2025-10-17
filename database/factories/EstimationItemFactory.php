<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Estimation;
use App\Models\Material;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EstimationItem>
 */
class EstimationItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = $this->faker->randomElement(['worker', 'material', 'equipment']);

        return [
            'estimation_id' => Estimation::factory(),
            'category' => $category,
            'reference_id' => function () use ($category) {
                return match ($category) {
                    'worker' => Worker::factory(),
                    'material' => Material::factory(),
                    'equipment' => Equipment::factory(),
                    default => null,
                };
            },
            'code' => strtoupper(substr($category, 0, 2)).'-'.$this->faker->numberBetween(1, 999),
            'coefficient' => $this->faker->randomFloat(2, 0.5, 5.0),
            'unit_price' => $this->faker->numberBetween(50000, 300000),
            'total_price' => function (array $attributes) {
                return (int) ($attributes['coefficient'] * $attributes['unit_price']);
            },
        ];
    }

    /**
     * Indicate that the item is for a worker.
     */
    public function worker(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'worker',
            'reference_id' => Worker::factory(),
        ]);
    }

    /**
     * Indicate that the item is for a material.
     */
    public function material(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'material',
            'reference_id' => Material::factory(),
        ]);
    }

    /**
     * Indicate that the item is for equipment.
     */
    public function equipment(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'equipment',
            'reference_id' => Equipment::factory(),
        ]);
    }
}
