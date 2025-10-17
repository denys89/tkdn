<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'prefix',
        'current_number',
        'year',
        'month'
    ];

    protected $casts = [
        'current_number' => 'integer',
        'year' => 'integer',
        'month' => 'integer'
    ];

    /**
     * Get the next number for the given entity type
     */
    public static function getNextNumber(string $entityType, string $prefix = null): int
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $counter = static::where('entity_type', $entityType)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->lockForUpdate()
            ->first();

        if (!$counter) {
            $counter = static::create([
                'entity_type' => $entityType,
                'prefix' => $prefix,
                'current_number' => 0,
                'year' => $currentYear,
                'month' => $currentMonth
            ]);
        }

        $counter->increment('current_number');
        
        return $counter->current_number;
    }

    /**
     * Generate formatted code
     */
    public static function generateCode(string $entityType, string $prefix = null): string
    {
        $number = static::getNextNumber($entityType, $prefix);
        $year = now()->format('y');
        $month = now()->format('m');
        
        return sprintf('%s%s%s%04d', $prefix, $year, $month, $number);
    }
}
