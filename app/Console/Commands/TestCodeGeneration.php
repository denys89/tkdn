<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\CodeGenerationServiceInterface;

class TestCodeGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:test {entity-type} {--count=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test code generation untuk entity type tertentu';

    /**
     * Execute the console command.
     */
    public function handle(CodeGenerationServiceInterface $codeService)
    {
        $entityType = $this->argument('entity-type');
        $count = $this->option('count');

        if (!in_array($entityType, $codeService->getAvailableEntityTypes())) {
            $this->error("Entity type '$entityType' tidak valid!");
            $this->info("Entity types yang tersedia: " . implode(', ', $codeService->getAvailableEntityTypes()));
            return 1;
        }

        $this->info("Testing code generation untuk $entityType...");
        $this->info("Akan generate $count code...\n");

        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $code = $codeService->generateCode($entityType);
            $codes[] = $code;
            $this->line("Code #" . ($i + 1) . ": $code");
        }

        $this->info("\n" . count($codes) . " code berhasil di-generate!");
        
        // Tampilkan info counter
        $counterInfo = $codeService->getCurrentCounterInfo($entityType);
        if ($counterInfo) {
            $this->info("\nInfo Counter:");
            $this->table(
                ['Property', 'Value'],
                [
                    ['Entity Type', $counterInfo['entity_type']],
                    ['Prefix', $counterInfo['prefix']],
                    ['Current Number', $counterInfo['current_number']],
                    ['Year', $counterInfo['year']],
                    ['Month', $counterInfo['month']],
                    ['Next Code', $counterInfo['next_code']]
                ]
            );
        }

        return 0;
    }
}
