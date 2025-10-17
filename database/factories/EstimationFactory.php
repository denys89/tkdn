<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estimation>
 */
class EstimationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'A.'.$this->faker->numberBetween(1, 9).'.'.$this->faker->numberBetween(1, 2),
            'title' => $this->faker->sentence(3),
            'total' => $this->faker->numberBetween(5000, 25000),
            'total_unit_price' => function (array $attributes) {
                return $attributes['total'];
            },
        ];
    }
}
