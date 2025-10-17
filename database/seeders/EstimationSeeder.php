<?php

namespace Database\Seeders;

use App\Models\Estimation;
use Illuminate\Database\Seeder;

class EstimationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estimations = [
            [
                'code' => 'A.1.1',
                'title' => 'Pekerjaan Galian Tanah',
                'total' => 12000, // Biaya dasar
                'total_unit_price' => 12000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.1.2',
                'title' => 'Pekerjaan Urugan Tanah',
                'total' => 8000, // Biaya dasar
                'total_unit_price' => 8000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.2.1',
                'title' => 'Pekerjaan Fondasi Batu Kali',
                'total' => 15000, // Biaya dasar
                'total_unit_price' => 15000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.2.2',
                'title' => 'Pekerjaan Fondasi Beton',
                'total' => 20000, // Biaya dasar
                'total_unit_price' => 20000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.3.1',
                'title' => 'Pekerjaan Dinding Bata',
                'total' => 14000, // Biaya dasar
                'total_unit_price' => 14000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.3.2',
                'title' => 'Pekerjaan Plesteran',
                'total' => 7000, // Biaya dasar
                'total_unit_price' => 7000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.4.1',
                'title' => 'Pekerjaan Atap',
                'total' => 16000, // Biaya dasar
                'total_unit_price' => 16000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.4.2',
                'title' => 'Pekerjaan Kusen Kayu',
                'total' => 22000, // Biaya dasar
                'total_unit_price' => 22000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.5.1',
                'title' => 'Pekerjaan Lantai Keramik',
                'total' => 13000, // Biaya dasar
                'total_unit_price' => 13000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.5.2',
                'title' => 'Pekerjaan Cat Tembok',
                'total' => 6000, // Biaya dasar
                'total_unit_price' => 6000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.6.1',
                'title' => 'Pekerjaan Instalasi Listrik',
                'total' => 11000, // Biaya dasar
                'total_unit_price' => 11000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.6.2',
                'title' => 'Pekerjaan Instalasi Air',
                'total' => 9500, // Biaya dasar
                'total_unit_price' => 9500, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.7.1',
                'title' => 'Pekerjaan Sanitasi',
                'total' => 8000, // Biaya dasar
                'total_unit_price' => 8000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.8.1',
                'title' => 'Pekerjaan Pagar',
                'total' => 12000, // Biaya dasar
                'total_unit_price' => 12000, // Total sama dengan biaya dasar
            ],
            [
                'code' => 'A.9.1',
                'title' => 'Pekerjaan Taman',
                'total' => 9000, // Biaya dasar
                'total_unit_price' => 9000, // Total sama dengan biaya dasar
            ],
        ];

        foreach ($estimations as $estimation) {
            Estimation::create($estimation);
        }

        // Verifikasi perhitungan:
        // Total biaya dasar: 12000 + 8000 + 15000 + 20000 + 14000 + 7000 + 16000 + 22000 + 13000 + 6000 + 11000 + 9500 + 8000 + 12000 + 9000 = 180500
        // Total unit price: 12000 + 8000 + 15000 + 20000 + 14000 + 7000 + 16000 + 22000 + 13000 + 6000 + 11000 + 9500 + 8000 + 12000 + 9000 = 180500
    }
}
