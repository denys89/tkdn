<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\Material;
use App\Models\Worker;
use Illuminate\Database\Seeder;

class EstimationItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua estimation yang sudah ada
        $estimations = Estimation::all();

        // Ambil sample data untuk reference
        $workers = Worker::take(5)->get();
        $materials = Material::take(10)->get();
        $equipments = Equipment::take(5)->get();

        // Data estimation items berdasarkan kategori pekerjaan
        $estimationItems = [
            // A.1.1 - Pekerjaan Galian Tanah
            [
                'estimation_code' => 'A.1.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-001',
                        'coefficient' => 2.5,
                        'unit_price' => 150000,
                        'total_price' => 375000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-001',
                        'coefficient' => 1.0,
                        'unit_price' => 50000,
                        'total_price' => 50000,
                    ],
                    [
                        'category' => 'equipment',
                        'code' => 'EQ-001',
                        'coefficient' => 0.5,
                        'unit_price' => 200000,
                        'total_price' => 100000,
                    ],
                ],
            ],

            // A.1.2 - Pekerjaan Urugan Tanah
            [
                'estimation_code' => 'A.1.2',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-002',
                        'coefficient' => 2.0,
                        'unit_price' => 120000,
                        'total_price' => 240000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-002',
                        'coefficient' => 1.5,
                        'unit_price' => 40000,
                        'total_price' => 60000,
                    ],
                ],
            ],

            // A.2.1 - Pekerjaan Fondasi Batu Kali
            [
                'estimation_code' => 'A.2.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-003',
                        'coefficient' => 3.0,
                        'unit_price' => 180000,
                        'total_price' => 540000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-003',
                        'coefficient' => 2.0,
                        'unit_price' => 80000,
                        'total_price' => 160000,
                    ],
                    [
                        'category' => 'equipment',
                        'code' => 'EQ-002',
                        'coefficient' => 0.8,
                        'unit_price' => 150000,
                        'total_price' => 120000,
                    ],
                ],
            ],

            // A.2.2 - Pekerjaan Fondasi Beton
            [
                'estimation_code' => 'A.2.2',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-004',
                        'coefficient' => 4.0,
                        'unit_price' => 200000,
                        'total_price' => 800000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-004',
                        'coefficient' => 2.5,
                        'unit_price' => 120000,
                        'total_price' => 300000,
                    ],
                    [
                        'category' => 'equipment',
                        'code' => 'EQ-003',
                        'coefficient' => 1.0,
                        'unit_price' => 180000,
                        'total_price' => 180000,
                    ],
                ],
            ],

            // A.3.1 - Pekerjaan Dinding Bata
            [
                'estimation_code' => 'A.3.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-005',
                        'coefficient' => 3.5,
                        'unit_price' => 160000,
                        'total_price' => 560000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-005',
                        'coefficient' => 2.0,
                        'unit_price' => 90000,
                        'total_price' => 180000,
                    ],
                ],
            ],

            // A.3.2 - Pekerjaan Plesteran
            [
                'estimation_code' => 'A.3.2',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-001',
                        'coefficient' => 2.0,
                        'unit_price' => 140000,
                        'total_price' => 280000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-006',
                        'coefficient' => 1.5,
                        'unit_price' => 60000,
                        'total_price' => 90000,
                    ],
                ],
            ],

            // A.4.1 - Pekerjaan Atap
            [
                'estimation_code' => 'A.4.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-002',
                        'coefficient' => 3.0,
                        'unit_price' => 170000,
                        'total_price' => 510000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-007',
                        'coefficient' => 2.0,
                        'unit_price' => 110000,
                        'total_price' => 220000,
                    ],
                    [
                        'category' => 'equipment',
                        'code' => 'EQ-004',
                        'coefficient' => 0.6,
                        'unit_price' => 160000,
                        'total_price' => 96000,
                    ],
                ],
            ],

            // A.4.2 - Pekerjaan Kusen Kayu
            [
                'estimation_code' => 'A.4.2',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-003',
                        'coefficient' => 4.5,
                        'unit_price' => 220000,
                        'total_price' => 990000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-008',
                        'coefficient' => 2.5,
                        'unit_price' => 150000,
                        'total_price' => 375000,
                    ],
                ],
            ],

            // A.5.1 - Pekerjaan Lantai Keramik
            [
                'estimation_code' => 'A.5.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-004',
                        'coefficient' => 3.0,
                        'unit_price' => 180000,
                        'total_price' => 540000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-009',
                        'coefficient' => 2.0,
                        'unit_price' => 130000,
                        'total_price' => 260000,
                    ],
                ],
            ],

            // A.5.2 - Pekerjaan Cat Tembok
            [
                'estimation_code' => 'A.5.2',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-005',
                        'coefficient' => 2.0,
                        'unit_price' => 120000,
                        'total_price' => 240000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-010',
                        'coefficient' => 1.5,
                        'unit_price' => 80000,
                        'total_price' => 120000,
                    ],
                ],
            ],

            // A.6.1 - Pekerjaan Instalasi Listrik
            [
                'estimation_code' => 'A.6.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-001',
                        'coefficient' => 3.5,
                        'unit_price' => 200000,
                        'total_price' => 700000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-001',
                        'coefficient' => 2.0,
                        'unit_price' => 100000,
                        'total_price' => 200000,
                    ],
                    [
                        'category' => 'equipment',
                        'code' => 'EQ-005',
                        'coefficient' => 0.8,
                        'unit_price' => 140000,
                        'total_price' => 112000,
                    ],
                ],
            ],

            // A.6.2 - Pekerjaan Instalasi Air
            [
                'estimation_code' => 'A.6.2',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-002',
                        'coefficient' => 3.0,
                        'unit_price' => 160000,
                        'total_price' => 480000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-002',
                        'coefficient' => 2.0,
                        'unit_price' => 90000,
                        'total_price' => 180000,
                    ],
                ],
            ],

            // A.7.1 - Pekerjaan Sanitasi
            [
                'estimation_code' => 'A.7.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-003',
                        'coefficient' => 2.5,
                        'unit_price' => 140000,
                        'total_price' => 350000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-003',
                        'coefficient' => 1.5,
                        'unit_price' => 80000,
                        'total_price' => 120000,
                    ],
                ],
            ],

            // A.8.1 - Pekerjaan Pagar
            [
                'estimation_code' => 'A.8.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-004',
                        'coefficient' => 3.0,
                        'unit_price' => 150000,
                        'total_price' => 450000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-004',
                        'coefficient' => 2.0,
                        'unit_price' => 110000,
                        'total_price' => 220000,
                    ],
                ],
            ],

            // A.9.1 - Pekerjaan Taman
            [
                'estimation_code' => 'A.9.1',
                'items' => [
                    [
                        'category' => 'worker',
                        'code' => 'WK-005',
                        'coefficient' => 2.5,
                        'unit_price' => 130000,
                        'total_price' => 325000,
                    ],
                    [
                        'category' => 'material',
                        'code' => 'MT-005',
                        'coefficient' => 1.5,
                        'unit_price' => 70000,
                        'total_price' => 105000,
                    ],
                ],
            ],
        ];

        // Buat estimation items
        foreach ($estimationItems as $estimationData) {
            $estimation = $estimations->where('code', $estimationData['estimation_code'])->first();

            if ($estimation) {
                foreach ($estimationData['items'] as $itemData) {
                    // Tentukan reference_id berdasarkan kategori
                    $referenceId = null;

                    switch ($itemData['category']) {
                        case 'worker':
                            $referenceId = $workers->random()->id;
                            break;
                        case 'material':
                            $referenceId = $materials->random()->id;
                            break;
                        case 'equipment':
                            $referenceId = $equipments->random()->id;
                            break;
                    }

                    EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'category' => $itemData['category'],
                        'reference_id' => $referenceId,
                        'code' => $itemData['code'],
                        'coefficient' => $itemData['coefficient'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $itemData['total_price'],
                    ]);
                }
            }
        }

        $this->command->info('EstimationItemSeeder completed successfully!');
        $this->command->info('Created '.EstimationItem::count().' estimation items.');
    }
}
