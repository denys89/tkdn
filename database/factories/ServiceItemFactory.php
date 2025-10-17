<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceItem>
 */
class ServiceItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'estimation_item_id' => null,
            'item_number' => $this->faker->numberBetween(1, 100),
            'tkdn_classification' => $this->faker->randomElement(['3.1', '3.2', '3.3', '3.4', '3.5']),
            'description' => $this->faker->sentence(3),
            'qualification' => $this->faker->optional()->word(),
            'nationality' => 'WNI',
            'tkdn_percentage' => $this->faker->randomElement([100, 75, 50, 25, 0]),
            'quantity' => $this->faker->numberBetween(1, 10),
            'duration' => $this->faker->randomFloat(2, 0.5, 12.0),
            'duration_unit' => $this->faker->randomElement(['ls', 'bulan', 'hari', 'jam']),
            'wage' => $this->faker->numberBetween(100000, 10000000),
            'domestic_cost' => function (array $attributes) {
                $wage = $attributes['wage'];
                $percentage = $attributes['tkdn_percentage'];

                return ($wage * $percentage) / 100;
            },
            'foreign_cost' => function (array $attributes) {
                $wage = $attributes['wage'];
                $percentage = $attributes['tkdn_percentage'];

                return $wage - (($wage * $percentage) / 100);
            },
            'total_cost' => function (array $attributes) {
                return $attributes['wage'];
            },
        ];
    }

    /**
     * Indicate that the service item has 100% TKDN.
     */
    public function tkdn100(): static
    {
        return $this->state(fn (array $attributes) => [
            'tkdn_percentage' => 100,
            'domestic_cost' => $attributes['wage'],
            'foreign_cost' => 0,
        ]);
    }

    /**
     * Indicate that the service item has 0% TKDN.
     */
    public function tkdn0(): static
    {
        return $this->state(fn (array $attributes) => [
            'tkdn_percentage' => 0,
            'domestic_cost' => 0,
            'foreign_cost' => $attributes['wage'],
        ]);
    }
}
