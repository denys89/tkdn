<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Project;
use App\Services\ImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ImportService $importService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importService = new ImportService;
    }

    public function test_find_category_by_name_exact_match()
    {
        $category = Category::factory()->create(['name' => 'Building Material']);

        $result = $this->importService->findCategoryByName('Building Material');

        $this->assertNotNull($result);
        $this->assertEquals($category->id, $result->id);
    }

    public function test_find_category_by_name_partial_match()
    {
        $category = Category::factory()->create(['name' => 'Building Material']);

        $result = $this->importService->findCategoryByName('Building');

        $this->assertNotNull($result);
        $this->assertEquals($category->id, $result->id);
    }

    public function test_find_category_by_name_not_found()
    {
        $result = $this->importService->findCategoryByName('Non Existent Category');

        $this->assertNull($result);
    }

    public function test_find_project_by_name_exact_match()
    {
        $project = Project::factory()->create(['name' => 'Test Project']);

        $result = $this->importService->findProjectByName('Test Project');

        $this->assertNotNull($result);
        $this->assertEquals($project->id, $result->id);
    }

    public function test_find_project_by_name_partial_match()
    {
        $project = Project::factory()->create(['name' => 'Test Project']);

        $result = $this->importService->findProjectByName('Test');

        $this->assertNotNull($result);
        $this->assertEquals($project->id, $result->id);
    }

    public function test_find_project_by_name_not_found()
    {
        $result = $this->importService->findProjectByName('Non Existent Project');

        $this->assertNull($result);
    }

    public function test_validate_and_get_category_id_success()
    {
        $category = Category::factory()->create(['name' => 'Building Material']);

        // Clear cache to ensure fresh lookup
        $this->importService->clearCache();

        [$categoryId, $errors] = $this->importService->validateAndGetCategoryId('Building Material', 1);

        $this->assertNotNull($categoryId);
        $this->assertNotEmpty($categoryId);
        $this->assertEmpty($errors);

        // Verify the category exists in database
        $this->assertDatabaseHas('categories', [
            'id' => $categoryId,
            'name' => 'Building Material',
        ]);
    }

    public function test_validate_and_get_category_id_not_found()
    {
        [$categoryId, $errors] = $this->importService->validateAndGetCategoryId('Non Existent', 1);

        $this->assertNull($categoryId);
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('tidak ditemukan', $errors[0]);
    }

    public function test_validate_and_get_category_id_empty()
    {
        [$categoryId, $errors] = $this->importService->validateAndGetCategoryId('', 1);

        $this->assertNull($categoryId);
        $this->assertEmpty($errors);
    }

    public function test_validate_required_fields_success()
    {
        $row = ['Name', 'Category', 'Price'];
        $requiredIndices = [0 => 'Name', 2 => 'Price'];

        $errors = $this->importService->validateRequiredFields($row, $requiredIndices, 1);

        $this->assertEmpty($errors);
    }

    public function test_validate_required_fields_missing()
    {
        $row = ['Name', '', 'Price'];
        $requiredIndices = [0 => 'Name', 1 => 'Category', 2 => 'Price'];

        $errors = $this->importService->validateRequiredFields($row, $requiredIndices, 1);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Category', $errors[0]);
    }

    public function test_validate_numeric_range_success()
    {
        $errors = $this->importService->validateNumericRange(50, 'TKDN', 1, 0, 100);

        $this->assertEmpty($errors);
    }

    public function test_validate_numeric_range_out_of_range()
    {
        $errors = $this->importService->validateNumericRange(150, 'TKDN', 1, 0, 100);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('at most 100', $errors[0]);
    }

    public function test_validate_numeric_range_invalid_number()
    {
        $errors = $this->importService->validateNumericRange('invalid', 'TKDN', 1, 0, 100);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('must be a number', $errors[0]);
    }

    public function test_validate_in_array_success()
    {
        $errors = $this->importService->validateInArray('disposable', 'Equipment Type', 1, ['disposable', 'reusable']);

        $this->assertEmpty($errors);
    }

    public function test_validate_in_array_invalid_value()
    {
        $errors = $this->importService->validateInArray('invalid', 'Equipment Type', 1, ['disposable', 'reusable']);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('must be one of', $errors[0]);
    }

    public function test_validate_date_success()
    {
        $errors = $this->importService->validateDate('2024-01-01', 'Start Date', 1);

        $this->assertEmpty($errors);
    }

    public function test_validate_date_invalid_format()
    {
        $errors = $this->importService->validateDate('invalid-date', 'Start Date', 1);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Invalid Start Date format', $errors[0]);
    }

    public function test_validate_date_range_success()
    {
        $errors = $this->importService->validateDateRange('2024-01-01', '2024-01-31', 1);

        $this->assertEmpty($errors);
    }

    public function test_validate_date_range_invalid()
    {
        $errors = $this->importService->validateDateRange('2024-01-31', '2024-01-01', 1);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('End date must be after or equal to start date', $errors[0]);
    }

    public function test_clear_cache()
    {
        // Test that cache is cleared without errors
        $this->importService->clearCache();
        $this->assertTrue(true); // If no exception is thrown, test passes
    }

    public function test_validate_classification_tkdn_success()
    {
        $errors = $this->importService->validateClassificationTkdn('1.1', 1);

        $this->assertEmpty($errors);
    }

    public function test_validate_classification_tkdn_invalid_format()
    {
        $errors = $this->importService->validateClassificationTkdn('invalid', 1);

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Classification TKDN must be in format', $errors[0]);
    }

    public function test_validate_classification_tkdn_empty()
    {
        $errors = $this->importService->validateClassificationTkdn('', 1);

        $this->assertEmpty($errors);
    }

    public function test_validate_classification_tkdn_null()
    {
        $errors = $this->importService->validateClassificationTkdn(null, 1);

        $this->assertEmpty($errors);
    }

    public function test_validate_classification_tkdn_various_formats()
    {
        $validFormats = ['1.1', '2.3', '10.5', '99.99'];

        foreach ($validFormats as $format) {
            $errors = $this->importService->validateClassificationTkdn($format, 1);
            $this->assertEmpty($errors, "Format '{$format}' should be valid");
        }
    }

    public function test_validate_classification_tkdn_invalid_formats()
    {
        $invalidFormats = ['1', '1.1.1', 'abc', '1.a', 'a.1', '1.1a'];

        foreach ($invalidFormats as $format) {
            $errors = $this->importService->validateClassificationTkdn($format, 1);
            $this->assertCount(1, $errors, "Format '{$format}' should be invalid");
        }
    }

    public function test_log_import_progress()
    {
        // Test that logging doesn't throw errors
        $this->importService->logImportProgress('test', 5, 10, ['error1', 'error2']);
        $this->assertTrue(true); // If no exception is thrown, test passes
    }
}
