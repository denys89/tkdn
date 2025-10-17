<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MaterialImportTest extends TestCase
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

    public function test_can_download_material_import_template(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/master/material/download-template');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename="material_import_template.xlsx"');
    }

    public function test_can_import_materials_successfully(): void
    {
        // Create Excel file content
        $data = [
            ['name', 'description', 'unit', 'price', 'stock', 'category_name'],
            ['Material A', 'Description A', 'kg', '10000', '100', 'Category 1'],
            ['Material B', 'Description B', 'pcs', '5000', '50', 'Category 2'],
        ];

        $file = $this->createExcelFile($data, 'materials.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/material/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/material');
        $response->assertSessionHas('success', 'Data berhasil diimport!');

        // Verify materials were created
        $this->assertDatabaseHas('material', [
            'name' => 'Material A',
            'description' => 'Description A',
            'unit' => 'kg',
            'price' => 10000,
            'stock' => 100,
        ]);

        $this->assertDatabaseHas('material', [
            'name' => 'Material B',
            'description' => 'Description B',
            'unit' => 'pcs',
            'price' => 5000,
            'stock' => 50,
        ]);

        // Verify category relationships
        $materialA = Material::where('name', 'Material A')->first();
        $materialB = Material::where('name', 'Material B')->first();

        $this->assertEquals($this->category1->id, $materialA->category_id);
        $this->assertEquals($this->category2->id, $materialB->category_id);

        // Verify codes were generated
        $this->assertNotNull($materialA->code);
        $this->assertNotNull($materialB->code);
        $this->assertStringStartsWith('MAT', $materialA->code);
        $this->assertStringStartsWith('MAT', $materialB->code);
    }

    public function test_import_validates_required_fields(): void
    {
        $data = [
            ['name', 'description', 'unit', 'price', 'stock', 'category_name'],
            ['', 'Description A', 'kg', '10000', '100', 'Category 1'], // Missing name
            ['Material B', '', 'pcs', '5000', '50', 'Category 2'], // Missing description
        ];

        $file = $this->createExcelFile($data, 'materials.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/material/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/material');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(2, $errors);
        $this->assertStringContainsString('Nama material wajib diisi', $errors[1]);
        $this->assertStringContainsString('Deskripsi material wajib diisi', $errors[2]);
    }

    public function test_import_validates_category_exists(): void
    {
        $data = [
            ['name', 'category', 'brand', 'specification', 'tkdn', 'price', 'unit', 'link', 'price_inflasi', 'description', 'location'],
            ['Material A', 'Non Existent Category', 'Brand A', 'Spec A', '50', '100000', 'unit', 'http://example.com', '110000', 'Description A', 'Location A'],
        ];

        $file = $this->createExcelFile($data, 'materials.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/material/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/material');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Kategori "Non Existent Category" tidak ditemukan', $errors[0]);
    }

    public function test_import_validates_price_format(): void
    {
        $data = [
            ['name', 'description', 'unit', 'price', 'stock', 'category_name'],
            ['Material A', 'Description A', 'kg', 'invalid-price', '100', 'Category 1'],
        ];

        $file = $this->createExcelFile($data, 'materials.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/material/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/material');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Harga harus berupa angka', $errors[1]);
    }

    public function test_import_validates_stock_format(): void
    {
        $data = [
            ['name', 'description', 'unit', 'price', 'stock', 'category_name'],
            ['Material A', 'Description A', 'kg', '10000', 'invalid-stock', 'Category 1'],
        ];

        $file = $this->createExcelFile($data, 'materials.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/material/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/material');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Stok harus berupa angka', $errors[1]);
    }

    public function test_import_validates_file_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/master/material/import', []);

        $response->assertRedirect('/master/material');
        $response->assertSessionHasErrors(['excel_file']);
    }

    public function test_import_validates_file_type(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($this->user)
            ->post('/master/material/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/material');
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
