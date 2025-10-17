<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EstimationItem;
use App\Models\Material;
use App\Models\Worker;
use Illuminate\Database\Seeder;

class EstimationItemTkdnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update estimation items dengan data TKDN dari referensi
        $estimationItems = EstimationItem::all();

        foreach ($estimationItems as $item) {
            $tkdnValue = null;
            $tkdnClassification = null;

            // Ambil nilai TKDN berdasarkan kategori dan reference_id
            switch ($item->category) {
                case 'worker':
                    if ($item->reference_id) {
                        $worker = Worker::find($item->reference_id);
                        if ($worker && $worker->tkdn !== null) {
                            $tkdnValue = $worker->tkdn;
                            $tkdnClassification = $this->calculateTkdnClassification($worker->tkdn);
                        }
                    }
                    break;

                case 'material':
                    if ($item->reference_id) {
                        $material = Material::find($item->reference_id);
                        if ($material && $material->tkdn !== null) {
                            $tkdnValue = $material->tkdn;
                            $tkdnClassification = $this->calculateTkdnClassification($material->tkdn);
                        }
                    }
                    break;

                case 'equipment':
                    if ($item->reference_id) {
                        $equipment = Equipment::find($item->reference_id);
                        if ($equipment && $equipment->tkdn !== null) {
                            $tkdnValue = $equipment->tkdn;
                            $tkdnClassification = $this->calculateTkdnClassification($equipment->tkdn);
                        }
                    }
                    break;
            }

            // Update item dengan data TKDN
            if ($tkdnValue !== null || $tkdnClassification !== null) {
                $item->update([
                    'tkdn_value' => $tkdnValue,
                    'tkdn_classification' => $tkdnClassification ?? $this->calculateTkdnClassification($tkdnValue),
                ]);
            }
        }

        $this->command->info('Estimation items TKDN data updated successfully!');
    }

    /**
     * Calculate TKDN classification based on TKDN value
     */
    private function calculateTkdnClassification(?float $tkdnValue): string
    {
        if ($tkdnValue === null) {
            return '3.7'; // Default to highest TKDN
        }

        if ($tkdnValue <= 25) {
            return '3.1';
        }
        if ($tkdnValue <= 35) {
            return '3.2';
        }
        if ($tkdnValue <= 45) {
            return '3.3';
        }
        if ($tkdnValue <= 55) {
            return '3.4';
        }
        if ($tkdnValue <= 65) {
            return '3.5';
        }
        if ($tkdnValue <= 75) {
            return '3.6';
        }

        return '3.7';
    }
}
