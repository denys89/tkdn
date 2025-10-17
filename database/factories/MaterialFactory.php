<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'MT-'.$this->faker->numberBetween(1, 999),
            'name' => $this->faker->words(3, true),
            'specification' => $this->faker->sentence(),
            'category_id' => Category::where('name', 'Material')->first()?->id ?? Category::first()?->id,
            'classification_tkdn' => $this->faker->randomElement(['3.1', '3.2', '3.3', '3.4', '3.5', '4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7']),
            'brand' => $this->faker->company(),
            'tkdn' => $this->faker->randomElement([0, 1]),
            'price' => $this->faker->numberBetween(10000, 200000),
            'unit' => $this->faker->randomElement(['kg', 'm', 'm2', 'm3', 'pcs', 'unit']),
            'link' => $this->faker->url(),
            'price_inflasi' => $this->faker->numberBetween(10000, 200000),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
        ];
    }
}
