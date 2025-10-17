<?php

namespace App\Contracts;

interface CodeGenerationServiceInterface
{
    /**
     * Generate unique code for the given entity type
     */
    public function generateCode(string $entityType): string;

    /**
     * Get the next number for the given entity type
     */
    public function getNextNumber(string $entityType): int;

    /**
     * Reset counter for the given entity type
     */
    public function resetCounter(string $entityType): bool;

    /**
     * Get available entity types
     */
    public function getAvailableEntityTypes(): array;

    /**
     * Get current counter info for entity type
     */
    public function getCurrentCounterInfo(string $entityType): ?array;
}
