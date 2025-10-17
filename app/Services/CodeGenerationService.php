<?php

namespace App\Services;

use App\Contracts\CodeGenerationServiceInterface;
use App\Models\Counter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CodeGenerationService implements CodeGenerationServiceInterface
{
    /**
     * Prefix mapping untuk setiap entity type
     */
    private const ENTITY_PREFIXES = [
        'worker' => 'WK',
        'material' => 'MT',
        'equipment' => 'EQ'
    ];

    /**
     * Generate unique code untuk entity type tertentu
     */
    public function generateCode(string $entityType): string
    {
        try {
            $prefix = $this->getPrefix($entityType);
            
            return DB::transaction(function () use ($entityType, $prefix) {
                return Counter::generateCode($entityType, $prefix);
            });
        } catch (\Exception $e) {
            Log::error('Error generating code for entity type: ' . $entityType, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Gagal generate code untuk ' . $entityType);
        }
    }

    /**
     * Get next number untuk entity type tertentu
     */
    public function getNextNumber(string $entityType): int
    {
        try {
            return DB::transaction(function () use ($entityType) {
                return Counter::getNextNumber($entityType);
            });
        } catch (\Exception $e) {
            Log::error('Error getting next number for entity type: ' . $entityType, [
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Gagal mendapatkan next number untuk ' . $entityType);
        }
    }

    /**
     * Reset counter untuk entity type tertentu
     */
    public function resetCounter(string $entityType): bool
    {
        try {
            return DB::transaction(function () use ($entityType) {
                $currentYear = now()->year;
                $currentMonth = now()->month;
                
                Counter::where('entity_type', $entityType)
                    ->where('year', $currentYear)
                    ->where('month', $currentMonth)
                    ->delete();
                
                return true;
            });
        } catch (\Exception $e) {
            Log::error('Error resetting counter for entity type: ' . $entityType, [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get prefix untuk entity type
     */
    private function getPrefix(string $entityType): string
    {
        return self::ENTITY_PREFIXES[$entityType] ?? 'XX';
    }

    /**
     * Get available entity types
     */
    public function getAvailableEntityTypes(): array
    {
        return array_keys(self::ENTITY_PREFIXES);
    }

    /**
     * Get current counter info untuk entity type
     */
    public function getCurrentCounterInfo(string $entityType): ?array
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        $counter = Counter::where('entity_type', $entityType)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();
        
        if (!$counter) {
            return null;
        }
        
        return [
            'entity_type' => $counter->entity_type,
            'prefix' => $counter->prefix,
            'current_number' => $counter->current_number,
            'year' => $counter->year,
            'month' => $counter->month,
            'next_code' => $this->generateCode($entityType)
        ];
    }
}
