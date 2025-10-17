<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Database\Seeder;

class ServiceItemTkdnSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->info('Tidak ada proyek yang tersedia. Silakan jalankan ProjectSeeder terlebih dahulu.');

            return;
        }

        $project = $projects->first();

        // Form 3.1 - Manajemen Proyek dan Perekayasaan
        $service31 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Manajemen Proyek dan Perekayasaan - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '3.1',
            'provider_name' => 'PT Manajemen Proyek Indonesia',
            'provider_address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'user_name' => 'PT Pembangunan Indonesia',
            'document_number' => 'DOC-2024-031',
            'status' => 'approved',
        ]);

        // Service Items untuk Form 3.1
        $this->createServiceItems($service31, '3.1', [
            [
                'description' => 'Project Manager Senior',
                'qualification' => 'S2 Teknik Sipil + PMP Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 35000000,
            ],
            [
                'description' => 'Site Engineer',
                'qualification' => 'S1 Teknik Sipil',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 25000000,
            ],
            [
                'description' => 'Quality Control Manager',
                'qualification' => 'S1 Teknik Sipil + ISO Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 10.00,
                'duration_unit' => 'Bl',
                'wage' => 28000000,
            ],
        ]);

        // Form 3.2 - Jasa Alat Kerja dan Peralatan
        $service32 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Alat Kerja dan Peralatan - Detail',
            'service_type' => Service::TYPE_EQUIPMENT,
            'tkdn_classification' => '3.2',
            'provider_name' => 'PT Sewa Alat Konstruksi',
            'provider_address' => 'Jl. Industri No. 89, Jakarta Utara',
            'user_name' => 'PT Pembangunan Indonesia',
            'document_number' => 'DOC-2024-032',
            'status' => 'approved',
        ]);

        // Service Items untuk Form 3.2
        $this->createServiceItems($service32, '3.2', [
            [
                'description' => 'Excavator 200 HP + Operator',
                'qualification' => 'Operator Berpengalaman 5+ tahun',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 8.00,
                'duration_unit' => 'Bl',
                'wage' => 50000000,
            ],
            [
                'description' => 'Crane 50 Ton + Operator',
                'qualification' => 'Operator Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 6.00,
                'duration_unit' => 'Bl',
                'wage' => 40000000,
            ],
            [
                'description' => 'Generator 100 KVA + Teknisi',
                'qualification' => 'Teknisi Listrik',
                'nationality' => 'WNA',
                'tkdn_percentage' => 30,
                'quantity' => 1,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 25000000,
            ],
        ]);

        // Form 3.3 - Jasa Konstruksi dan Pembangunan
        $service33 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Konstruksi dan Pembangunan - Detail',
            'service_type' => Service::TYPE_CONSTRUCTION,
            'tkdn_classification' => '3.3',
            'provider_name' => 'PT Konstruksi Maju Jaya',
            'provider_address' => 'Jl. Gatot Subroto No. 67, Jakarta Selatan',
            'user_name' => 'PT Pembangunan Indonesia',
            'document_number' => 'DOC-2024-033',
            'status' => 'approved',
        ]);

        // Service Items untuk Form 3.3
        $this->createServiceItems($service33, '3.3', [
            [
                'description' => 'Mandor Konstruksi',
                'qualification' => 'SMA + Pengalaman 10+ tahun',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 3,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 18000000,
            ],
            [
                'description' => 'Tukang Batu',
                'qualification' => 'Pengalaman 5+ tahun',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 8,
                'duration' => 10.00,
                'duration_unit' => 'Bl',
                'wage' => 12000000,
            ],
            [
                'description' => 'Tukang Kayu',
                'qualification' => 'Pengalaman 5+ tahun',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 5,
                'duration' => 8.00,
                'duration_unit' => 'Bl',
                'wage' => 15000000,
            ],
        ]);

        // Form 3.4 - Jasa Konsultasi dan Pengawasan
        $service34 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Konsultasi dan Pengawasan - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '3.4',
            'provider_name' => 'PT Konsultan Teknik Indonesia',
            'provider_address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-034',
            'status' => 'submitted',
        ]);

        // Service Items untuk Form 3.4
        $this->createServiceItems($service34, '3.4', [
            [
                'description' => 'Konsultan Struktur Senior',
                'qualification' => 'S2 Teknik Sipil + PE Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 8.00,
                'duration_unit' => 'Bl',
                'wage' => 40000000,
            ],
            [
                'description' => 'Konsultan MEP',
                'qualification' => 'S1 Teknik Elektro',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 6.00,
                'duration_unit' => 'Bl',
                'wage' => 30000000,
            ],
            [
                'description' => 'Pengawas Lapangan',
                'qualification' => 'S1 Teknik Sipil',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 20000000,
            ],
        ]);

        // Form 4.1 - Jasa Teknik dan Rekayasa
        $service41 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Teknik dan Rekayasa - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.1',
            'provider_name' => 'PT Teknik Rekayasa Indonesia',
            'provider_address' => 'Jl. Prof. Dr. Soepomo No. 12, Jakarta Selatan',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-041',
            'status' => 'approved',
        ]);

        // Service Items untuk Form 4.1
        $this->createServiceItems($service41, '4.1', [
            [
                'description' => 'Rekayasa Struktur Gedung',
                'qualification' => 'S2 Teknik Sipil + PE',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 8.00,
                'duration_unit' => 'Bl',
                'wage' => 35000000,
            ],
            [
                'description' => 'Rekayasa Sistem MEP',
                'qualification' => 'S1 Teknik Elektro',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 6.00,
                'duration_unit' => 'Bl',
                'wage' => 30000000,
            ],
            [
                'description' => 'Rekayasa Geoteknik',
                'qualification' => 'S2 Teknik Sipil',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 4.00,
                'duration_unit' => 'Bl',
                'wage' => 25000000,
            ],
        ]);

        // Form 4.2 - Jasa Pengadaan dan Logistik
        $service42 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Pengadaan dan Logistik - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.2',
            'provider_name' => 'PT Logistik Indonesia',
            'provider_address' => 'Jl. Raya Bekasi Km 15, Jakarta Timur',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-042',
            'status' => 'submitted',
        ]);

        // Service Items untuk Form 4.2
        $this->createServiceItems($service42, '4.2', [
            [
                'description' => 'Manajer Pengadaan',
                'qualification' => 'S1 Manajemen + CPM Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 25000000,
            ],
            [
                'description' => 'Koordinator Logistik',
                'qualification' => 'S1 Teknik Industri',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 10.00,
                'duration_unit' => 'Bl',
                'wage' => 20000000,
            ],
            [
                'description' => 'Analis Pengadaan',
                'qualification' => 'S1 Teknik Sipil',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 3,
                'duration' => 8.00,
                'duration_unit' => 'Bl',
                'wage' => 18000000,
            ],
        ]);

        // Form 4.3 - Jasa Operasi dan Pemeliharaan
        $service43 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Operasi dan Pemeliharaan - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.3',
            'provider_name' => 'PT Operasi Maintenance Indonesia',
            'provider_address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-043',
            'status' => 'approved',
        ]);

        // Service Items untuk Form 4.3
        $this->createServiceItems($service43, '4.3', [
            [
                'description' => 'Teknisi HVAC',
                'qualification' => 'D3 Teknik Mesin + Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 24.00,
                'duration_unit' => 'Bl',
                'wage' => 15000000,
            ],
            [
                'description' => 'Teknisi Listrik',
                'qualification' => 'D3 Teknik Elektro + Sertifikasi',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 3,
                'duration' => 24.00,
                'duration_unit' => 'Bl',
                'wage' => 14000000,
            ],
            [
                'description' => 'Supervisor Maintenance',
                'qualification' => 'S1 Teknik Mesin',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 24.00,
                'duration_unit' => 'Bl',
                'wage' => 22000000,
            ],
        ]);

        // Form 4.4 - Jasa Pelatihan dan Sertifikasi
        $service44 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Pelatihan dan Sertifikasi - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.4',
            'provider_name' => 'PT Pelatihan Profesional Indonesia',
            'provider_address' => 'Jl. HR Rasuna Said No. 45, Jakarta Selatan',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-044',
            'status' => 'submitted',
        ]);

        // Service Items untuk Form 4.4
        $this->createServiceItems($service44, '4.4', [
            [
                'description' => 'Instruktur Pelatihan Teknis',
                'qualification' => 'S2 Teknik Sipil + Certified Trainer',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 4.00,
                'duration_unit' => 'Bl',
                'wage' => 40000000,
            ],
            [
                'description' => 'Koordinator Sertifikasi',
                'qualification' => 'S1 Teknik Sipil + ISO Lead Auditor',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 6.00,
                'duration_unit' => 'Bl',
                'wage' => 35000000,
            ],
            [
                'description' => 'Asisten Instruktur',
                'qualification' => 'S1 Teknik Sipil',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 3,
                'duration' => 4.00,
                'duration_unit' => 'Bl',
                'wage' => 25000000,
            ],
        ]);

        // Form 4.5 - Jasa Teknologi Informasi
        $service45 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Teknologi Informasi - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.5',
            'provider_name' => 'PT Solusi IT Indonesia',
            'provider_address' => 'Jl. Sudirman No. 456, Jakarta Pusat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-045',
            'status' => 'approved',
        ]);

        // Service Items untuk Form 4.5
        $this->createServiceItems($service45, '4.5', [
            [
                'description' => 'System Analyst Senior',
                'qualification' => 'S1 Teknik Informatika + PMP',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 8.00,
                'duration_unit' => 'Bl',
                'wage' => 30000000,
            ],
            [
                'description' => 'Database Administrator',
                'qualification' => 'S1 Teknik Informatika + Oracle Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 10.00,
                'duration_unit' => 'Bl',
                'wage' => 35000000,
            ],
            [
                'description' => 'Network Engineer',
                'qualification' => 'S1 Teknik Informatika + CCNA',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 6.00,
                'duration_unit' => 'Bl',
                'wage' => 28000000,
            ],
        ]);

        // Form 4.6 - Jasa Lingkungan dan Keamanan
        $service46 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Lingkungan dan Keamanan - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.6',
            'provider_name' => 'PT Lingkungan Aman Indonesia',
            'provider_address' => 'Jl. Kebon Jeruk No. 78, Jakarta Barat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-046',
            'status' => 'submitted',
        ]);

        // Service Items untuk Form 4.6
        $this->createServiceItems($service46, '4.6', [
            [
                'description' => 'Environmental Officer',
                'qualification' => 'S1 Teknik Lingkungan + AMDAL Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 25000000,
            ],
            [
                'description' => 'Security Coordinator',
                'qualification' => 'S1 Manajemen + Security Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 22000000,
            ],
            [
                'description' => 'Safety Officer',
                'qualification' => 'S1 Teknik Industri + K3 Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 2,
                'duration' => 12.00,
                'duration_unit' => 'Bl',
                'wage' => 20000000,
            ],
        ]);

        // Form 4.7 - Jasa Lainnya
        $service47 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Lainnya - Detail',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.7',
            'provider_name' => 'PT Jasa Terpadu Indonesia',
            'provider_address' => 'Jl. M.H. Thamrin No. 234, Jakarta Pusat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-047',
            'status' => 'draft',
        ]);

        // Service Items untuk Form 4.7
        $this->createServiceItems($service47, '4.7', [
            [
                'description' => 'Konsultan Hukum Konstruksi',
                'qualification' => 'S1 Hukum + SH + Pengalaman Konstruksi',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 6.00,
                'duration_unit' => 'Bl',
                'wage' => 45000000,
            ],
            [
                'description' => 'Konsultan Keuangan Proyek',
                'qualification' => 'S1 Akuntansi + CPA + Pengalaman Proyek',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 4.00,
                'duration_unit' => 'Bl',
                'wage' => 40000000,
            ],
            [
                'description' => 'Konsultan Manajemen Risiko',
                'qualification' => 'S1 Manajemen + Risk Management Certified',
                'nationality' => 'WNI',
                'tkdn_percentage' => 100,
                'quantity' => 1,
                'duration' => 5.00,
                'duration_unit' => 'Bl',
                'wage' => 35000000,
            ],
        ]);

        $this->command->info('ServiceItemTkdnSeeder berhasil dijalankan!');
        $this->command->info('Dibuat 11 service detail dengan form 3.1, 3.2, 3.3, 3.4, 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7');
    }

    private function createServiceItems(Service $service, string $tkdnClassification, array $items): void
    {
        foreach ($items as $index => $itemData) {
            ServiceItem::create([
                'service_id' => $service->id,
                'item_number' => $index + 1,
                'tkdn_classification' => $tkdnClassification,
                'description' => $itemData['description'],
                'qualification' => $itemData['qualification'],
                'nationality' => $itemData['nationality'],
                'tkdn_percentage' => $itemData['tkdn_percentage'],
                'quantity' => $itemData['quantity'],
                'duration' => $itemData['duration'],
                'duration_unit' => $itemData['duration_unit'],
                'wage' => $itemData['wage'],
            ]);
        }

        // Hitung total untuk service
        $service->calculateTotals();
    }
}
