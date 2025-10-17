<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TkdnTabNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    public function test_tkdn_jasa_shows_form_3_1_by_default(): void
    {
        // Create a TKDN Jasa project first
        $project = Project::factory()->create([
            'project_type' => 'tkdn_jasa',
            'name' => 'Test TKDN Jasa Project',
        ]);

        // Create a TKDN Jasa service
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'service_name' => 'Test TKDN Jasa Service',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('service.show', $service));

        $response->assertStatus(200);

        // Check that Form 3.1 tab is active by default
        $response->assertSee('showForm(\'form-3-1\')', false);
        $response->assertSee('id="tab-3-1"', false);
        $response->assertSee('bg-blue-600', false); // Active tab styling
    }

    public function test_tkdn_barang_jasa_shows_form_4_1_by_default(): void
    {
        // Create a TKDN Barang & Jasa project first
        $project = Project::factory()->create([
            'project_type' => 'tkdn_barang_jasa',
            'name' => 'Test TKDN Barang & Jasa Project',
        ]);

        // Create a TKDN Barang & Jasa service
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'service_name' => 'Test TKDN Barang & Jasa Service',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('service.show', $service));

        $response->assertStatus(200);

        // Check that Form 4.1 tab is active by default
        $response->assertSee('showForm(\'form-4-1\')', false);
        $response->assertSee('id="tab-4-1"', false);
        $response->assertSee('bg-green-600', false); // Active tab styling for Form 4.x
    }

    public function test_tkdn_barang_jasa_has_all_form_tabs(): void
    {
        // Create a TKDN Barang & Jasa project first
        $project = Project::factory()->create([
            'project_type' => 'tkdn_barang_jasa',
            'name' => 'Test TKDN Barang & Jasa Project',
        ]);

        // Create a TKDN Barang & Jasa service
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'service_name' => 'Test TKDN Barang & Jasa Service',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('service.show', $service));

        $response->assertStatus(200);

        // Check that all Form 4.x tabs are present
        for ($i = 1; $i <= 7; $i++) {
            $response->assertSee("id=\"tab-4-{$i}\"", false);
            $response->assertSee("onclick=\"showForm('form-4-{$i}')\"", false);
            $response->assertSee("Form 4.{$i}", false);
        }
    }

    public function test_tkdn_barang_jasa_form_content_exists(): void
    {
        // Create a TKDN Barang & Jasa project first
        $project = Project::factory()->create([
            'project_type' => 'tkdn_barang_jasa',
            'name' => 'Test TKDN Barang & Jasa Project',
        ]);

        // Create a TKDN Barang & Jasa service
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'service_name' => 'Test TKDN Barang & Jasa Service',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('service.show', $service));

        $response->assertStatus(200);

        // Debug: Check if the response contains form-4-1
        $content = $response->getContent();
        $this->assertStringContainsString('id="form-4-1"', $content, 'Form 4.1 content should exist in response');

        // Check that all Form 4.x content divs exist
        for ($i = 1; $i <= 7; $i++) {
            $response->assertSee("id=\"form-4-{$i}\"", false);
            $response->assertSee('class="form-content', false);
        }
    }

    public function test_javascript_initialization_logic(): void
    {
        // Create a TKDN Barang & Jasa project first
        $project = Project::factory()->create([
            'project_type' => 'tkdn_barang_jasa',
            'name' => 'Test TKDN Barang & Jasa Project',
        ]);

        // Create a TKDN Barang & Jasa service
        $service = Service::factory()->create([
            'project_id' => $project->id,
            'service_name' => 'Test TKDN Barang & Jasa Service',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('service.show', $service));

        $response->assertStatus(200);

        // Check that the JavaScript initialization logic is correct
        $response->assertSee('const projectType = \'tkdn_barang_jasa\';', false);
        $response->assertSee('if (projectType === \'tkdn_barang_jasa\') {', false);
        $response->assertSee('showForm(\'form-4-1\');', false);
    }
}
