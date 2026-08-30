<?php

namespace App\Imports;

use App\Services\SchoolsImportService;

/**
 * CSV import adapter.
 *
 * Since maatwebsite/excel is incompatible with PHP 8.5,
 * this class delegates to SchoolsImportService which uses native str_getcsv.
 */
class SchoolsImport
{
    public function __construct(
        private SchoolsImportService $service,
    ) {}

    /**
     * @return array{imported: int, skipped: int, errors: array<int, string>}
     */
    public function import(string $filePath): array
    {
        return $this->service->importFromCsv($filePath);
    }
}
