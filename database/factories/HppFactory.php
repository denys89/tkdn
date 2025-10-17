<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hpp>
 */
class HppFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'HPP-'.date('Ymd').'-'.strtoupper($this->faker->lexify('????')),
            'project_id' => Project::factory(),
            'sub_total_hpp' => $this->faker->numberBetween(1000000, 50000000),
            'overhead_percentage' => $this->faker->numberBetween(5, 20),
            'overhead_amount' => $this->faker->numberBetween(50000, 2500000),
            'margin_percentage' => $this->faker->numberBetween(10, 30),
            'margin_amount' => $this->faker->numberBetween(100000, 5000000),
            'sub_total' => $this->faker->numberBetween(1150000, 57500000),
            'ppn_percentage' => 11,
            'ppn_amount' => $this->faker->numberBetween(126500, 6325000),
            'grand_total' => $this->faker->numberBetween(1276500, 63825000),
            'notes' => $this->faker->optional()->sentence,
            'status' => $this->faker->randomElement(['draft', 'submitted', 'approved', 'rejected']),
        ];
    }
}
