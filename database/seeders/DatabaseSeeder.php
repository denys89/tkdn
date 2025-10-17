<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            CounterSeeder::class,
            WorkerSeeder::class,
            MaterialSeeder::class,
            EquipmentSeeder::class,
            ProjectSeeder::class,
            EstimationSeeder::class,
            EstimationItemSeeder::class,
            ServiceSeeder::class,
            ServiceItemTkdnSeeder::class,
            HppSeeder::class,
        ]);
    }
}
