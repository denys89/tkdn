<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'service_type' => $this->faker->randomElement(['project', 'equipment', 'construction']),
            'service_name' => $this->faker->sentence(3),
            'provider_name' => $this->faker->company,
            'provider_address' => $this->faker->address,
            'user_name' => $this->faker->name,
            'document_number' => 'DOC-'.$this->faker->unique()->numberBetween(1000, 9999),
            'total_domestic_cost' => $this->faker->numberBetween(1000000, 50000000),
            'total_foreign_cost' => $this->faker->numberBetween(0, 10000000),
            'total_cost' => $this->faker->numberBetween(1000000, 50000000),
            'tkdn_percentage' => $this->faker->numberBetween(25, 100),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'approved', 'rejected']),
        ];
    }
}
