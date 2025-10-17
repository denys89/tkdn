<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Counter;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Initialize counters for each entity type
        $entityTypes = [
            'worker' => 'WK',
            'material' => 'MT',
            'equipment' => 'EQ'
        ];

        foreach ($entityTypes as $entityType => $prefix) {
            Counter::updateOrCreate(
                [
                    'entity_type' => $entityType,
                    'year' => $currentYear,
                    'month' => $currentMonth
                ],
                [
                    'prefix' => $prefix,
                    'current_number' => 0
                ]
            );
        }
    }
}
