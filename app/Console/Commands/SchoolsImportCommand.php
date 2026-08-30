<?php

namespace App\Console\Commands;

use App\Services\SchoolsImportService;
use Illuminate\Console\Command;

class SchoolsImportCommand extends Command
{
    protected $signature = 'schools:import {file : Path to CSV file}';

    protected $description = 'Import schools from a CSV file';

    public function handle(SchoolsImportService $service): int
    {
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $this->error("File [{$file}] not found.");

            return self::FAILURE;
        }

        $this->info('Importing schools...');
        $result = $service->importFromCsv($file);

        $this->info("Imported: {$result['imported']}, Skipped: {$result['skipped']}");

        if (! empty($result['errors'])) {
            $this->warn('Errors:');
            foreach ($result['errors'] as $line => $error) {
                $this->line("  Line {$line}: {$error}");
            }
        }

        return self::SUCCESS;
    }
}
