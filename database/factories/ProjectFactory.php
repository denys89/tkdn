<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectType = $this->faker->randomElement(['tkdn_jasa', 'tkdn_barang_jasa']);

        // Generate project name based on project type
        $projectName = $this->generateProjectName($projectType);

        return [
            'name' => $projectName,
            'project_type' => $projectType,
            'description' => $this->generateProjectDescription($projectType),
            'company' => $this->faker->company,
            'location' => $this->faker->city,
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement(['draft', 'on_progress', 'completed']),
        ];
    }

    /**
     * Generate project name based on project type
     */
    private function generateProjectName(string $projectType): string
    {
        if ($projectType === 'tkdn_jasa') {
            $jasaProjects = [
                'Gedung Perkantoran',
                'Rumah Sakit',
                'Mall Shopping Center',
                'Apartemen',
                'Jembatan Layang',
                'Bandara',
                'Hotel',
                'Sekolah',
                'Gedung Parkir',
                'Masjid',
                'Stasiun Kereta Api',
                'Gedung Pemerintahan',
                'Universitas',
                'Pusat Olahraga',
                'Convention Center',
                'Terminal Bus',
                'Pelabuhan',
                'Gedung Laboratorium',
                'Data Center',
                'Gedung Komersial',
            ];

            return $this->faker->randomElement($jasaProjects).' '.$this->faker->numberBetween(1, 20).' Lantai';
        } else {
            $pabrikProjects = [
                'Pabrik Manufaktur',
                'Pabrik Tekstil',
                'Pabrik Makanan',
                'Pabrik Kimia',
                'Pabrik Otomotif',
                'Pabrik Plastik',
                'Pabrik Kertas',
                'Pabrik Semen',
                'Pabrik Logam',
                'Pabrik Keramik',
                'Pabrik Furniture',
                'Pabrik Alat Berat',
                'Pabrik Elektronik',
                'Pabrik Teknologi Hijau',
                'Pabrik Farmasi',
                'Pabrik Kemasan',
                'Pabrik Komponen',
                'Pabrik Bahan Baku',
                'Pabrik Peralatan',
                'Pabrik Produk Jadi',
            ];

            return $this->faker->randomElement($pabrikProjects).' '.$this->faker->word();
        }
    }

    /**
     * Generate project description based on project type
     */
    private function generateProjectDescription(string $projectType): string
    {
        if ($projectType === 'tkdn_jasa') {
            $descriptions = [
                'Konstruksi gedung modern dengan teknologi terbaru',
                'Pembangunan infrastruktur dengan standar internasional',
                'Proyek konstruksi ramah lingkungan dan berkelanjutan',
                'Pembangunan fasilitas publik dengan desain modern',
                'Konstruksi bangunan dengan sistem manajemen proyek terintegrasi',
                'Pembangunan gedung dengan teknologi smart building',
                'Proyek konstruksi dengan fokus pada efisiensi energi',
                'Pembangunan infrastruktur transportasi modern',
                'Konstruksi bangunan dengan sistem keamanan terpadu',
                'Pembangunan fasilitas komersial dengan konsep green building',
            ];
        } else {
            $descriptions = [
                'Pabrik dengan teknologi produksi terbaru dan sistem otomatis',
                'Fasilitas produksi dengan standar ISO dan sertifikasi internasional',
                'Pabrik ramah lingkungan dengan sistem pengolahan limbah terpadu',
                'Fasilitas manufaktur dengan teknologi Industry 4.0',
                'Pabrik dengan sistem produksi just-in-time dan lean manufacturing',
                'Fasilitas produksi dengan laboratorium R&D terintegrasi',
                'Pabrik dengan sistem manajemen kualitas terpadu',
                'Fasilitas produksi dengan teknologi digital dan IoT',
                'Pabrik dengan sistem energi terbarukan dan efisiensi tinggi',
                'Fasilitas manufaktur dengan sertifikasi halal dan internasional',
            ];
        }

        return $this->faker->randomElement($descriptions).' di '.$this->faker->city;
    }
}
