<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EquipmentImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category1;

    protected Category $category2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test categories
        $this->category1 = Category::factory()->create(['name' => 'Category 1']);
        $this->category2 = Category::factory()->create(['name' => 'Category 2']);
    }

    public function test_can_download_equipment_import_template(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/master/equipment/download-template');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename="equipment_import_template.xlsx"');
    }

    public function test_can_import_equipment_successfully(): void
    {
        // Create Excel file content
        $data = [
            ['name', 'description', 'type', 'period', 'category_name'],
            ['Equipment A', 'Description A', 'reusable', '12', 'Category 1'],
            ['Equipment B', 'Description B', 'disposable', '6', 'Category 2'],
        ];

        $file = $this->createExcelFile($data, 'equipment.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHas('success', 'Data berhasil diimport!');

        // Verify equipment were created
        $this->assertDatabaseHas('equipment', [
            'name' => 'Equipment A',
            'description' => 'Description A',
            'type' => 'reusable',
            'period' => 12,
        ]);

        $this->assertDatabaseHas('equipment', [
            'name' => 'Equipment B',
            'description' => 'Description B',
            'type' => 'disposable',
            'period' => 6,
        ]);

        // Verify category relationships
        $equipmentA = Equipment::where('name', 'Equipment A')->first();
        $equipmentB = Equipment::where('name', 'Equipment B')->first();

        $this->assertEquals($this->category1->id, $equipmentA->category_id);
        $this->assertEquals($this->category2->id, $equipmentB->category_id);

        // Verify codes were generated
        $this->assertNotNull($equipmentA->code);
        $this->assertNotNull($equipmentB->code);
        $this->assertStringStartsWith('EQP', $equipmentA->code);
        $this->assertStringStartsWith('EQP', $equipmentB->code);
    }

    public function test_import_validates_required_fields(): void
    {
        $data = [
            ['name', 'description', 'type', 'period', 'category_name'],
            ['', 'Description A', 'reusable', '12', 'Category 1'], // Missing name
            ['Equipment B', '', 'disposable', '6', 'Category 2'], // Missing description
        ];

        $file = $this->createExcelFile($data, 'equipment.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(2, $errors);
        $this->assertStringContainsString('Nama equipment wajib diisi', $errors[1]);
        $this->assertStringContainsString('Deskripsi equipment wajib diisi', $errors[2]);
    }

    public function test_import_validates_category_exists(): void
    {
        $data = [
            ['name', 'category', 'tkdn', 'type', 'period', 'price', 'description', 'location'],
            ['Equipment A', 'Non Existent Category', '50', 'reusable', '12', '100000', 'Description A', 'Location A'],
        ];

        $file = $this->createExcelFile($data, 'equipment.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Kategori "Non Existent Category" tidak ditemukan', $errors[0]);
    }

    public function test_import_validates_type_values(): void
    {
        $data = [
            ['name', 'description', 'type', 'period', 'category_name'],
            ['Equipment A', 'Description A', 'invalid-type', '12', 'Category 1'],
        ];

        $file = $this->createExcelFile($data, 'equipment.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Tipe equipment harus reusable atau disposable', $errors[1]);
    }

    public function test_import_validates_period_format(): void
    {
        $data = [
            ['name', 'description', 'type', 'period', 'category_name'],
            ['Equipment A', 'Description A', 'reusable', 'invalid-period', 'Category 1'],
        ];

        $file = $this->createExcelFile($data, 'equipment.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Periode harus berupa angka', $errors[1]);
    }

    public function test_import_validates_period_range_for_reusable(): void
    {
        $data = [
            ['name', 'description', 'type', 'period', 'category_name'],
            ['Equipment A', 'Description A', 'reusable', '25', 'Category 1'], // > 24 months
        ];

        $file = $this->createExcelFile($data, 'equipment.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Periode untuk equipment reusable maksimal 24 bulan', $errors[1]);
    }

    public function test_import_validates_period_range_for_disposable(): void
    {
        $data = [
            ['name', 'description', 'type', 'period', 'category_name'],
            ['Equipment A', 'Description A', 'disposable', '13', 'Category 1'], // > 12 months
        ];

        $file = $this->createExcelFile($data, 'equipment.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Periode untuk equipment disposable maksimal 12 bulan', $errors[1]);
    }

    public function test_import_validates_file_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', []);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHasErrors(['excel_file']);
    }

    public function test_import_validates_file_type(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($this->user)
            ->post('/master/equipment/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/equipment');
        $response->assertSessionHasErrors(['excel_file']);
    }

    private function createExcelFile(array $data, string $filename): UploadedFile
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1).($rowIndex + 1), $value);
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'test_excel_');
        $writer->save($tempFile);

        return new UploadedFile(
            $tempFile,
            $filename,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
    }
}
