<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'EQ-'.$this->faker->numberBetween(1, 999),
            'name' => $this->faker->words(3, true),
            'category_id' => Category::where('name', 'Peralatan')->first()?->id ?? Category::first()?->id,
            'classification_tkdn' => $this->faker->randomElement(['3.1', '3.2', '3.3', '3.4', '3.5', '4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7']),
            'tkdn' => $this->faker->randomFloat(2, 0, 100),
            'period' => $this->faker->numberBetween(1, 12),
            'price' => $this->faker->numberBetween(500000, 2000000),
            'description' => $this->faker->sentence(10),
            'location' => $this->faker->city(),
        ];
    }
}
