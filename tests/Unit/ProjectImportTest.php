<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProjectImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();
    }

    public function test_can_download_project_import_template(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/master/project/download-template');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename="project_import_template.xlsx"');
    }

    public function test_can_import_projects_successfully(): void
    {
        // Create Excel file content matching controller expectations
        // Controller expects: Name(0), Status(1), Start Date(2), End Date(3), Description(4), Company(5), Location(6)
        $data = [
            ['Name', 'Status', 'Start Date', 'End Date', 'Description', 'Company', 'Location'],
            ['Project A', 'draft', '2024-01-01', '2024-12-31', 'Description A', 'Company A', 'Jakarta'],
            ['Project B', 'on_progress', '2024-02-01', '2024-11-30', 'Description B', 'Company B', 'Bandung'],
        ];

        $file = $this->createExcelFile($data, 'projects.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/project/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/project');

        // Check for import errors first
        if (session('import_errors')) {
            $this->fail('Import failed with errors: '.implode(', ', session('import_errors')));
        }

        // Should have success message matching what controller actually returns
        $this->assertTrue(session()->has('success'));
        $this->assertStringContainsString('Successfully imported', session('success'));

        // Verify projects were created with correct fields
        $this->assertDatabaseHas('projects', [
            'name' => 'Project A',
            'status' => 'draft',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'description' => 'Description A',
            'company' => 'Company A',
            'location' => 'Jakarta',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Project B',
            'status' => 'on_progress',
            'start_date' => '2024-02-01',
            'end_date' => '2024-11-30',
            'description' => 'Description B',
            'company' => 'Company B',
            'location' => 'Bandung',
        ]);
    }

    public function test_import_validates_required_fields(): void
    {
        $data = [
            ['Name', 'Status', 'Start Date', 'End Date', 'Description', 'Company', 'Location'],
            ['', 'draft', '2024-01-01', '2024-12-31', 'Description A', 'Company A', 'Jakarta'], // Missing name
            ['Project B', '', '2024-02-01', '2024-11-30', 'Description B', 'Company B', 'Bandung'], // Missing status
            ['Project C', 'draft', '', '2024-12-31', 'Description C', 'Company C', 'Surabaya'], // Missing start_date
            ['Project D', 'draft', '2024-01-01', '', 'Description D', 'Company D', 'Medan'], // Missing end_date
        ];

        $file = $this->createExcelFile($data, 'projects.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/project/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/project');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(4, $errors);
        $this->assertStringContainsString('Missing required fields', $errors[0]);
        $this->assertStringContainsString('Missing required fields', $errors[1]);
        $this->assertStringContainsString('Missing required fields', $errors[2]);
        $this->assertStringContainsString('Missing required fields', $errors[3]);
    }

    public function test_import_validates_status_values(): void
    {
        $data = [
            ['Name', 'Status', 'Start Date', 'End Date', 'Description', 'Company', 'Location'],
            ['Project A', 'invalid_status', '2024-01-01', '2024-12-31', 'Description A', 'Company A', 'Jakarta'],
            ['Project B', 'active', '2024-02-01', '2024-11-30', 'Description B', 'Company B', 'Bandung'],
        ];

        $file = $this->createExcelFile($data, 'projects.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/project/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/project');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(2, $errors);
        $this->assertStringContainsString('Status must be draft, on_progress, or completed', $errors[0]);
        $this->assertStringContainsString('Status must be draft, on_progress, or completed', $errors[1]);
    }

    public function test_import_validates_date_format(): void
    {
        $data = [
            ['Name', 'Status', 'Start Date', 'End Date', 'Description', 'Company', 'Location'],
            ['Project A', 'draft', 'invalid-date', '2024-12-31', 'Description A', 'Company A', 'Jakarta'],
            ['Project B', 'draft', '2024-01-01', 'invalid-date', 'Description B', 'Company B', 'Bandung'],
        ];

        $file = $this->createExcelFile($data, 'projects.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/project/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/project');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(2, $errors);
        $this->assertStringContainsString('Invalid date format', $errors[0]);
        $this->assertStringContainsString('Invalid date format', $errors[1]);
    }

    public function test_import_validates_date_range(): void
    {
        $data = [
            ['Name', 'Status', 'Start Date', 'End Date', 'Description', 'Company', 'Location'],
            ['Project A', 'draft', '2024-12-31', '2024-01-01', 'Description A', 'Company A', 'Jakarta'], // End date before start date
        ];

        $file = $this->createExcelFile($data, 'projects.xlsx');

        $response = $this->actingAs($this->user)
            ->post('/master/project/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/project');
        $response->assertSessionHas('import_errors');

        $errors = session('import_errors');
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('End date must be after or equal to start date', $errors[0]);
    }

    public function test_import_validates_file_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/master/project/import', []);

        $response->assertRedirect('/master/project');
        $response->assertSessionHasErrors(['excel_file']);
    }

    public function test_import_validates_file_type(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($this->user)
            ->post('/master/project/import', [
                'excel_file' => $file,
            ]);

        $response->assertRedirect('/master/project');
        $response->assertSessionHasErrors(['excel_file']);
    }

    private function createExcelFile(array $data, string $filename): UploadedFile
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Use fromArray method which is more reliable
        $sheet->fromArray($data, null, 'A1');

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
