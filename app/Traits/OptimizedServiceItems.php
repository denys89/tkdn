<?php

namespace App\Traits;

use App\Models\HppItem;
use App\Models\ServiceItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait OptimizedServiceItems
{
    /**
     * Get optimized service items with lazy loading and caching
     */
    public function getOptimizedServiceItems(?string $projectType = null): Collection
    {
        $cacheKey = "service_items_{$this->id}_{$projectType}";

        return Cache::remember($cacheKey, 3600, function () use ($projectType) {
            return $this->generateServiceItemsLazy($projectType);
        });
    }

    /**
     * Get service items for specific classification with caching
     */
    public function getServiceItemsByClassification(string $classification): Collection
    {
        $cacheKey = "service_items_{$this->id}_{$classification}";

        return Cache::remember($cacheKey, 3600, function () use ($classification) {
            // Cek apakah ada stored service items untuk classification ini
            $storedItems = $this->items()->where('tkdn_classification', $classification)->get();

            if ($storedItems->isNotEmpty()) {
                Log::info('Using stored service items', [
                    'service_id' => $this->id,
                    'classification' => $classification,
                    'count' => $storedItems->count(),
                ]);

                return $storedItems;
            }

            // Jika tidak ada stored items, generate dari HPP
            return $this->generateServiceItemsFromHpp($classification);
        });
    }

    /**
     * Generate service items lazy - hanya saat diperlukan
     */
    protected function generateServiceItemsLazy(?string $projectType = null): Collection
    {
        Log::info('Generating service items lazily', [
            'service_id' => $this->id,
            'project_type' => $projectType,
        ]);

        $projectType = $projectType ?? $this->project->project_type;

        // Ambil stored service items dulu
        $storedItems = $this->items;

        // Filter berdasarkan project type
        $filteredStoredItems = $storedItems->filter(function ($item) use ($projectType) {
            if ($projectType === 'tkdn_jasa') {
                return in_array($item->tkdn_classification, ['3.1', '3.2', '3.3', '3.4', '3.5']);
            } elseif ($projectType === 'tkdn_barang_jasa') {
                return in_array($item->tkdn_classification, ['4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7']);
            }

            return true;
        });

        // Jika ada stored items, gunakan itu
        if ($filteredStoredItems->isNotEmpty()) {
            Log::info('Using existing stored service items', [
                'service_id' => $this->id,
                'stored_count' => $filteredStoredItems->count(),
            ]);

            return $filteredStoredItems;
        }

        // Jika tidak ada, generate dari HPP items
        return $this->generateFromHppItems($projectType);
    }

    /**
     * Generate service items from HPP items for specific classification
     */
    protected function generateServiceItemsFromHpp(string $classification): Collection
    {
        $hppItems = $this->getHppItemsByClassification($classification);

        if ($hppItems->isEmpty()) {
            return collect();
        }

        $generatedItems = collect();

        foreach ($hppItems as $index => $hppItem) {
            $tkdnPercentage = $this->calculateTkdnPercentageForForm($classification);
            $wage = $hppItem->total_price ?? 0;
            $quantity = $hppItem->volume ?? 1;
            $duration = $hppItem->duration ?? 1;

            $totalCost = $wage * $quantity * $duration;
            $domesticCost = ($totalCost * $tkdnPercentage) / 100;
            $foreignCost = $totalCost - $domesticCost;

            // Create virtual service item (tidak disimpan ke database)
            $virtualItem = new ServiceItem([
                'service_id' => $this->id,
                'tkdn_classification' => $classification,
                'item_number' => $index + 1,
                'description' => $hppItem->description ?? 'Item '.($index + 1),
                'qualification' => $this->getQualificationFromHppItem($hppItem),
                'nationality' => 'WNI',
                'tkdn_percentage' => $tkdnPercentage,
                'quantity' => $quantity,
                'duration' => $duration,
                'duration_unit' => $hppItem->duration_unit ?? 'ls',
                'wage' => $wage,
                'domestic_cost' => $domesticCost,
                'foreign_cost' => $foreignCost,
                'total_cost' => $totalCost,
            ]);

            // Set flag bahwa ini virtual item
            $virtualItem->is_virtual = true;
            $virtualItem->hpp_item_id = $hppItem->id;

            $generatedItems->push($virtualItem);
        }

        return $generatedItems;
    }

    /**
     * Generate from all HPP items for project type
     */
    protected function generateFromHppItems(string $projectType): Collection
    {
        $classifications = $projectType === 'tkdn_jasa'
            ? ['3.1', '3.2', '3.3', '3.4', '3.5']
            : ['4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7'];

        $allItems = collect();

        foreach ($classifications as $classification) {
            $items = $this->generateServiceItemsFromHpp($classification);
            $allItems = $allItems->merge($items);
        }

        return $allItems;
    }

    /**
     * Get HPP items by classification
     */
    protected function getHppItemsByClassification(string $classification): Collection
    {
        return HppItem::whereHas('hpp', function ($query) {
            $query->where('project_id', $this->project_id);
        })
            ->whereHas('estimationItem', function ($query) use ($classification) {
                $query->where(function ($q) use ($classification) {
                    $q->whereHas('worker', function ($workerQuery) use ($classification) {
                        $workerQuery->where('classification_tkdn', $classification);
                    })->orWhereHas('material', function ($materialQuery) use ($classification) {
                        $materialQuery->where('classification_tkdn', $classification);
                    })->orWhereHas('equipment', function ($equipmentQuery) use ($classification) {
                        $equipmentQuery->where('classification_tkdn', $classification);
                    });
                });
            })
            ->with(['hpp', 'estimationItem'])
            ->get();
    }

    /**
     * Save service item permanently (selective storage)
     */
    public function storeServiceItemPermanently(array $itemData): ServiceItem
    {
        Log::info('Storing service item permanently', [
            'service_id' => $this->id,
            'classification' => $itemData['tkdn_classification'] ?? 'unknown',
        ]);

        // Clear cache for this classification
        $classification = $itemData['tkdn_classification'];
        Cache::forget("service_items_{$this->id}_{classification}");
        Cache::forget("service_items_{$this->id}_{$this->project->project_type}");

        return $this->items()->create($itemData);
    }

    /**
     * Update stored service item
     */
    public function updateServiceItemPermanently(ServiceItem $item, array $data): bool
    {
        Log::info('Updating stored service item', [
            'service_id' => $this->id,
            'item_id' => $item->id,
            'classification' => $item->tkdn_classification,
        ]);

        // Clear cache
        Cache::forget("service_items_{$this->id}_{$item->tkdn_classification}");
        Cache::forget("service_items_{$this->id}_{$this->project->project_type}");

        return $item->update($data);
    }

    /**
     * Clear all service items cache
     */
    public function clearServiceItemsCache(): void
    {
        $projectType = $this->project->project_type;
        $classifications = $projectType === 'tkdn_jasa'
            ? ['3.1', '3.2', '3.3', '3.4', '3.5']
            : ['4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7'];

        foreach ($classifications as $classification) {
            Cache::forget("service_items_{$this->id}_{$classification}");
        }

        Cache::forget("service_items_{$this->id}_{$projectType}");
    }

    /**
     * Helper methods yang mungkin diperlukan
     */
    protected function calculateTkdnPercentageForForm(string $formNumber): int
    {
        // Default TKDN percentage untuk setiap form
        $tkdnDefaults = [
            '3.1' => 100, '3.2' => 100, '3.3' => 100, '3.4' => 100, '3.5' => 100,
            '4.1' => 100, '4.2' => 100, '4.3' => 100, '4.4' => 100,
            '4.5' => 100, '4.6' => 100, '4.7' => 100,
        ];

        return $tkdnDefaults[$formNumber] ?? 100;
    }

    protected function getQualificationFromHppItem($hppItem): ?string
    {
        // Logic untuk menentukan qualification dari HPP item
        // Bisa diambil dari estimation item atau data lainnya
        return $hppItem->estimationItem->qualification ?? null;
    }
}
