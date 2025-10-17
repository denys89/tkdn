<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->info('Tidak ada proyek yang tersedia. Silakan jalankan ProjectSeeder terlebih dahulu.');

            return;
        }

        $project = $projects->first();

        // Jasa 1: Manajemen Proyek (Form 3.1)
        $service1 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Manajemen Proyek dan Perekayasaan',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '3.1',
            'provider_name' => 'PT Konstruksi Maju',
            'provider_address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'user_name' => 'PT Pembangunan Indonesia',
            'document_number' => 'DOC-2024-001',
            'status' => 'approved',
        ]);

        // Item untuk jasa 1
        ServiceItem::create([
            'service_id' => $service1->id,
            'item_number' => 1,
            'tkdn_classification' => '3.1',
            'description' => 'Overhead management',
            'qualification' => 'S1 Teknik Sipil',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 1.00,
            'duration_unit' => 'Is',
            'wage' => 103522080,
        ]);

        ServiceItem::create([
            'service_id' => $service1->id,
            'item_number' => 2,
            'tkdn_classification' => '3.1',
            'description' => 'Management',
            'qualification' => 'S1 Manajemen',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 1.00,
            'duration_unit' => 'Is',
            'wage' => 155283120,
        ]);

        // Hitung total
        $service1->calculateTotals();

        // Jasa 2: Konsultasi Teknis (Form 3.4)
        $service2 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Konsultasi Teknis dan Desain',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '3.4',
            'provider_name' => 'PT Konsultan Teknik',
            'provider_address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-002',
            'status' => 'submitted',
        ]);

        // Item untuk jasa 2
        ServiceItem::create([
            'service_id' => $service2->id,
            'item_number' => 1,
            'tkdn_classification' => '3.4',
            'description' => 'Konsultan Teknik Senior',
            'qualification' => 'S2 Teknik Sipil',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 2,
            'duration' => 6.00,
            'duration_unit' => 'Bl',
            'wage' => 25000000,
        ]);

        ServiceItem::create([
            'service_id' => $service2->id,
            'item_number' => 2,
            'tkdn_classification' => '3.4',
            'description' => 'Konsultan Desain',
            'qualification' => 'S1 Arsitektur',
            'nationality' => 'WNA',
            'tkdn_percentage' => 0,
            'quantity' => 1,
            'duration' => 3.00,
            'duration_unit' => 'Bl',
            'wage' => 35000000,
        ]);

        // Hitung total
        $service2->calculateTotals();

        // Jasa 3: Pengawasan Konstruksi (Form 3.3)
        $service3 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Pengawasan Konstruksi',
            'service_type' => Service::TYPE_CONSTRUCTION,
            'tkdn_classification' => '3.3',
            'provider_name' => 'PT Supervisi Konstruksi',
            'provider_address' => 'Jl. Gatot Subroto No. 67, Jakarta Selatan',
            'user_name' => 'PT Pembangunan Indonesia',
            'document_number' => 'DOC-2024-003',
            'status' => 'draft',
        ]);

        // Item untuk jasa 3
        ServiceItem::create([
            'service_id' => $service3->id,
            'item_number' => 1,
            'tkdn_classification' => '3.3',
            'description' => 'Pengawas Lapangan',
            'qualification' => 'S1 Teknik Sipil',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 3,
            'duration' => 12.00,
            'duration_unit' => 'Bl',
            'wage' => 15000000,
        ]);

        ServiceItem::create([
            'service_id' => $service3->id,
            'item_number' => 2,
            'tkdn_classification' => '3.3',
            'description' => 'Kepala Pengawas',
            'qualification' => 'S1 Teknik Sipil',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 12.00,
            'duration_unit' => 'Bl',
            'wage' => 25000000,
        ]);

        ServiceItem::create([
            'service_id' => $service3->id,
            'item_number' => 3,
            'tkdn_classification' => '3.3',
            'description' => 'Konsultan QA/QC',
            'qualification' => 'S1 Teknik Sipil',
            'nationality' => 'WNA',
            'tkdn_percentage' => 30,
            'quantity' => 1,
            'duration' => 6.00,
            'duration_unit' => 'Bl',
            'wage' => 40000000,
        ]);

        // Hitung total
        $service3->calculateTotals();

        // Jasa 4: Alat Kerja (Form 3.2)
        $service4 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Sewa Alat Kerja dan Peralatan',
            'service_type' => Service::TYPE_EQUIPMENT,
            'tkdn_classification' => '3.2',
            'provider_name' => 'PT Sewa Alat',
            'provider_address' => 'Jl. Industri No. 89, Jakarta Utara',
            'user_name' => 'PT Pembangunan Indonesia',
            'document_number' => 'DOC-2024-004',
            'status' => 'approved',
        ]);

        // Item untuk jasa 4
        ServiceItem::create([
            'service_id' => $service4->id,
            'item_number' => 1,
            'tkdn_classification' => '3.2',
            'description' => 'Sewa Excavator',
            'qualification' => 'Operator Berpengalaman',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 2,
            'duration' => 8.00,
            'duration_unit' => 'Bl',
            'wage' => 50000000,
        ]);

        ServiceItem::create([
            'service_id' => $service4->id,
            'item_number' => 2,
            'tkdn_classification' => '3.2',
            'description' => 'Sewa Crane',
            'qualification' => 'Operator Berpengalaman',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 6.00,
            'duration_unit' => 'Bl',
            'wage' => 35000000,
        ]);

        ServiceItem::create([
            'service_id' => $service4->id,
            'item_number' => 3,
            'tkdn_classification' => '3.2',
            'description' => 'Sewa Generator',
            'qualification' => 'Teknisi',
            'nationality' => 'WNA',
            'tkdn_percentage' => 0,
            'quantity' => 1,
            'duration' => 12.00,
            'duration_unit' => 'Bl',
            'wage' => 20000000,
        ]);

        // Hitung total
        $service4->calculateTotals();

        // Form 4.1 - Jasa Teknik dan Rekayasa
        $service5 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Teknik dan Rekayasa',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.1',
            'provider_name' => 'PT Teknik Rekayasa Indonesia',
            'provider_address' => 'Jl. Prof. Dr. Soepomo No. 12, Jakarta Selatan',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-005',
            'status' => 'approved',
        ]);

        ServiceItem::create([
            'service_id' => $service5->id,
            'item_number' => 1,
            'tkdn_classification' => '4.1',
            'description' => 'Rekayasa Struktur',
            'qualification' => 'S2 Teknik Sipil',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 2,
            'duration' => 8.00,
            'duration_unit' => 'Bl',
            'wage' => 30000000,
        ]);

        ServiceItem::create([
            'service_id' => $service5->id,
            'item_number' => 2,
            'tkdn_classification' => '4.1',
            'description' => 'Rekayasa Sistem',
            'qualification' => 'S1 Teknik Elektro',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 6.00,
            'duration_unit' => 'Bl',
            'wage' => 25000000,
        ]);

        $service5->calculateTotals();

        // Form 4.2 - Jasa Pengadaan dan Logistik
        $service6 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Pengadaan dan Logistik',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.2',
            'provider_name' => 'PT Logistik Indonesia',
            'provider_address' => 'Jl. Raya Bekasi Km 15, Jakarta Timur',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-006',
            'status' => 'submitted',
        ]);

        ServiceItem::create([
            'service_id' => $service6->id,
            'item_number' => 1,
            'tkdn_classification' => '4.2',
            'description' => 'Manajemen Pengadaan',
            'qualification' => 'S1 Manajemen',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 3,
            'duration' => 12.00,
            'duration_unit' => 'Bl',
            'wage' => 18000000,
        ]);

        ServiceItem::create([
            'service_id' => $service6->id,
            'item_number' => 2,
            'tkdn_classification' => '4.2',
            'description' => 'Koordinator Logistik',
            'qualification' => 'S1 Teknik Industri',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 2,
            'duration' => 10.00,
            'duration_unit' => 'Bl',
            'wage' => 22000000,
        ]);

        $service6->calculateTotals();

        // Form 4.3 - Jasa Operasi dan Pemeliharaan
        $service7 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Operasi dan Pemeliharaan',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.3',
            'provider_name' => 'PT Operasi Maintenance',
            'provider_address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-007',
            'status' => 'approved',
        ]);

        ServiceItem::create([
            'service_id' => $service7->id,
            'item_number' => 1,
            'tkdn_classification' => '4.3',
            'description' => 'Teknisi Operasi',
            'qualification' => 'D3 Teknik Mesin',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 4,
            'duration' => 24.00,
            'duration_unit' => 'Bl',
            'wage' => 12000000,
        ]);

        ServiceItem::create([
            'service_id' => $service7->id,
            'item_number' => 2,
            'tkdn_classification' => '4.3',
            'description' => 'Supervisor Maintenance',
            'qualification' => 'S1 Teknik Mesin',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 24.00,
            'duration_unit' => 'Bl',
            'wage' => 20000000,
        ]);

        $service7->calculateTotals();

        // Form 4.4 - Jasa Pelatihan dan Sertifikasi
        $service8 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Pelatihan dan Sertifikasi',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.4',
            'provider_name' => 'PT Pelatihan Profesional',
            'provider_address' => 'Jl. HR Rasuna Said No. 45, Jakarta Selatan',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-008',
            'status' => 'submitted',
        ]);

        ServiceItem::create([
            'service_id' => $service8->id,
            'item_number' => 1,
            'tkdn_classification' => '4.4',
            'description' => 'Instruktur Pelatihan',
            'qualification' => 'S2 Teknik Sipil',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 2,
            'duration' => 4.00,
            'duration_unit' => 'Bl',
            'wage' => 35000000,
        ]);

        ServiceItem::create([
            'service_id' => $service8->id,
            'item_number' => 2,
            'tkdn_classification' => '4.4',
            'description' => 'Koordinator Sertifikasi',
            'qualification' => 'S1 Teknik Sipil',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 6.00,
            'duration_unit' => 'Bl',
            'wage' => 28000000,
        ]);

        $service8->calculateTotals();

        // Form 4.5 - Jasa Teknologi Informasi
        $service9 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Teknologi Informasi',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.5',
            'provider_name' => 'PT Solusi IT Indonesia',
            'provider_address' => 'Jl. Sudirman No. 456, Jakarta Pusat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-009',
            'status' => 'approved',
        ]);

        ServiceItem::create([
            'service_id' => $service9->id,
            'item_number' => 1,
            'tkdn_classification' => '4.5',
            'description' => 'System Analyst',
            'qualification' => 'S1 Teknik Informatika',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 2,
            'duration' => 8.00,
            'duration_unit' => 'Bl',
            'wage' => 25000000,
        ]);

        ServiceItem::create([
            'service_id' => $service9->id,
            'item_number' => 2,
            'tkdn_classification' => '4.5',
            'description' => 'Database Administrator',
            'qualification' => 'S1 Teknik Informatika',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 10.00,
            'duration_unit' => 'Bl',
            'wage' => 30000000,
        ]);

        $service9->calculateTotals();

        // Form 4.6 - Jasa Lingkungan dan Keamanan
        $service10 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Lingkungan dan Keamanan',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.6',
            'provider_name' => 'PT Lingkungan Aman',
            'provider_address' => 'Jl. Kebon Jeruk No. 78, Jakarta Barat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-010',
            'status' => 'submitted',
        ]);

        ServiceItem::create([
            'service_id' => $service10->id,
            'item_number' => 1,
            'tkdn_classification' => '4.6',
            'description' => 'Environmental Officer',
            'qualification' => 'S1 Teknik Lingkungan',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 2,
            'duration' => 12.00,
            'duration_unit' => 'Bl',
            'wage' => 20000000,
        ]);

        ServiceItem::create([
            'service_id' => $service10->id,
            'item_number' => 2,
            'tkdn_classification' => '4.6',
            'description' => 'Security Coordinator',
            'qualification' => 'S1 Manajemen',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 12.00,
            'duration_unit' => 'Bl',
            'wage' => 18000000,
        ]);

        $service10->calculateTotals();

        // Form 4.7 - Jasa Lainnya
        $service11 = Service::create([
            'project_id' => $project->id,
            'service_name' => 'Jasa Lainnya',
            'service_type' => Service::TYPE_PROJECT,
            'tkdn_classification' => '4.7',
            'provider_name' => 'PT Jasa Terpadu',
            'provider_address' => 'Jl. M.H. Thamrin No. 234, Jakarta Pusat',
            'user_name' => 'PT Infrastruktur Nasional',
            'document_number' => 'DOC-2024-011',
            'status' => 'draft',
        ]);

        ServiceItem::create([
            'service_id' => $service11->id,
            'item_number' => 1,
            'tkdn_classification' => '4.7',
            'description' => 'Konsultan Hukum',
            'qualification' => 'S1 Hukum',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 6.00,
            'duration_unit' => 'Bl',
            'wage' => 40000000,
        ]);

        ServiceItem::create([
            'service_id' => $service11->id,
            'item_number' => 2,
            'tkdn_classification' => '4.7',
            'description' => 'Konsultan Keuangan',
            'qualification' => 'S1 Akuntansi',
            'nationality' => 'WNI',
            'tkdn_percentage' => 100,
            'quantity' => 1,
            'duration' => 4.00,
            'duration_unit' => 'Bl',
            'wage' => 35000000,
        ]);

        $service11->calculateTotals();

        $this->command->info('ServiceSeeder berhasil dijalankan!');
        $this->command->info('Dibuat 11 service dengan form 3.1, 3.2, 3.3, 3.4, 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7');
    }
}
