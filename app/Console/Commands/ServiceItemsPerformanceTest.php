<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ServiceItemsPerformanceTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:performance-test {--service= : Service ID to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test performance between old and optimized Service Items loading';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceId = $this->option('service');
        
        if (!$serviceId) {
            $service = Service::with('project')->first();
            if (!$service) {
                $this->error('No services found in database');
                return 1;
            }
        } else {
            $service = Service::with('project')->find($serviceId);
            if (!$service) {
                $this->error("Service with ID {$serviceId} not found");
                return 1;
            }
        }

        $this->info("Testing performance for Service: {$service->service_name}");
        $this->info("Project Type: {$service->project->project_type}");
        $this->newLine();

        // Clear cache first
        Cache::flush();
        $service->clearServiceItemsCache();

        // Test 1: Old method (direct database query)
        $this->info('🔄 Testing OLD method (direct database query)...');
        $oldTime = $this->measureOldMethod($service);
        
        // Clear cache again
        Cache::flush();
        $service->clearServiceItemsCache();

        // Test 2: Optimized method (lazy loading + cache)
        $this->info('🚀 Testing OPTIMIZED method (lazy loading + cache)...');
        $optimizedTime = $this->measureOptimizedMethod($service);

        // Test 3: Cache hit performance
        $this->info('⚡ Testing CACHE HIT performance...');
        $cacheHitTime = $this->measureCacheHit($service);

        $this->displayResults($oldTime, $optimizedTime, $cacheHitTime);

        return 0;
    }

    protected function measureOldMethod(Service $service): array
    {
        $startTime = microtime(true);
        $startQueries = DB::getQueryLog();
        DB::enableQueryLog();

        // Simulate old method
        $service->load(['items']);
        $projectType = $service->project->project_type;
        
        $filteredItems = $service->items->filter(function ($item) use ($projectType) {
            if ($projectType === 'tkdn_jasa') {
                return in_array($item->tkdn_classification, ['3.1', '3.2', '3.3', '3.4', '3.5']);
            } elseif ($projectType === 'tkdn_barang_jasa') {
                return in_array($item->tkdn_classification, ['4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7']);
            }
            return true;
        });

        $groupedItems = $filteredItems->groupBy('tkdn_classification');

        $endTime = microtime(true);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        return [
            'time' => ($endTime - $startTime) * 1000, // Convert to milliseconds
            'queries' => count($queries),
            'items_count' => $filteredItems->count(),
            'memory' => memory_get_peak_usage(true)
        ];
    }

    protected function measureOptimizedMethod(Service $service): array
    {
        $startTime = microtime(true);
        DB::enableQueryLog();

        // Use optimized method
        $optimizedItems = $service->getOptimizedServiceItems($service->project->project_type);
        $groupedItems = $optimizedItems->groupBy('tkdn_classification');

        $endTime = microtime(true);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        return [
            'time' => ($endTime - $startTime) * 1000,
            'queries' => count($queries),
            'items_count' => $optimizedItems->count(),
            'memory' => memory_get_peak_usage(true)
        ];
    }

    protected function measureCacheHit(Service $service): array
    {
        $startTime = microtime(true);
        DB::enableQueryLog();

        // Second call should hit cache
        $cachedItems = $service->getOptimizedServiceItems($service->project->project_type);
        $groupedItems = $cachedItems->groupBy('tkdn_classification');

        $endTime = microtime(true);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        return [
            'time' => ($endTime - $startTime) * 1000,
            'queries' => count($queries),
            'items_count' => $cachedItems->count(),
            'memory' => memory_get_peak_usage(true)
        ];
    }

    protected function displayResults(array $oldTime, array $optimizedTime, array $cacheHitTime): void
    {
        $this->newLine();
        $this->info('📊 PERFORMANCE RESULTS:');
        $this->newLine();

        // Create table
        $headers = ['Metric', 'Old Method', 'Optimized', 'Cache Hit', 'Improvement'];
        $rows = [];

        // Time comparison
        $timeImprovement = $oldTime['time'] > 0 ? 
            round((($oldTime['time'] - $optimizedTime['time']) / $oldTime['time']) * 100, 1) : 0;
        $cacheImprovement = $oldTime['time'] > 0 ? 
            round((($oldTime['time'] - $cacheHitTime['time']) / $oldTime['time']) * 100, 1) : 0;

        $rows[] = [
            'Time (ms)',
            number_format($oldTime['time'], 2),
            number_format($optimizedTime['time'], 2),
            number_format($cacheHitTime['time'], 2),
            $timeImprovement . '%'
        ];

        // Queries comparison
        $queriesImprovement = $oldTime['queries'] > 0 ? 
            round((($oldTime['queries'] - $optimizedTime['queries']) / $oldTime['queries']) * 100, 1) : 0;

        $rows[] = [
            'DB Queries',
            $oldTime['queries'],
            $optimizedTime['queries'],
            $cacheHitTime['queries'],
            $queriesImprovement . '%'
        ];

        // Items count
        $rows[] = [
            'Items Count',
            $oldTime['items_count'],
            $optimizedTime['items_count'],
            $cacheHitTime['items_count'],
            'Same'
        ];

        // Memory usage
        $memoryImprovement = $oldTime['memory'] > 0 ? 
            round((($oldTime['memory'] - $optimizedTime['memory']) / $oldTime['memory']) * 100, 1) : 0;

        $rows[] = [
            'Memory (MB)',
            number_format($oldTime['memory'] / 1024 / 1024, 2),
            number_format($optimizedTime['memory'] / 1024 / 1024, 2),
            number_format($cacheHitTime['memory'] / 1024 / 1024, 2),
            $memoryImprovement . '%'
        ];

        $this->table($headers, $rows);

        $this->newLine();
        $this->info('🎯 SUMMARY:');
        $this->line("• Optimized method is {$timeImprovement}% faster");
        $this->line("• Cache hit is {$cacheImprovement}% faster than original");
        $this->line("• Database queries reduced by {$queriesImprovement}%");
        $this->line("• Memory usage optimized by {$memoryImprovement}%");

        if ($timeImprovement > 0) {
            $this->info('✅ Performance improvement achieved!');
        } else {
            $this->warn('⚠️  No significant performance improvement detected');
        }
    }
}