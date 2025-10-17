<?php

namespace Database\Factories;

use App\Models\Hpp;
use App\Models\HppItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HppItem>
 */
class HppItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HppItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hpp_id' => Hpp::factory(),
            'estimation_item_id' => null,
            'description' => $this->faker->sentence(3),
            'volume' => $this->faker->randomFloat(2, 0.1, 100.0),
            'unit' => $this->faker->randomElement(['ls', 'm2', 'm3', 'kg', 'unit']),
            'duration' => $this->faker->numberBetween(1, 12),
            'duration_unit' => $this->faker->randomElement(['bulan', 'hari', 'jam', 'ls']),
            'unit_price' => $this->faker->numberBetween(10000, 1000000),
            'total_price' => function (array $attributes) {
                return $attributes['volume'] * $attributes['unit_price'];
            },
        ];
    }
}
