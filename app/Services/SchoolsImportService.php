<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Collection;

class SchoolsImportService
{
    /**
     * Import schools from a CSV file path.
     *
     * @return array{imported: int, skipped: int, errors: array<int, string>}
     */
    public function importFromCsv(string $filePath): array
    {
        $rows = array_map('str_getcsv', file($filePath));
        $headers = array_shift($rows);
        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $lineNum = $index + 2;

            if (count($row) !== count($headers)) {
                $errors[$lineNum] = 'Column count mismatch';
                $skipped++;

                continue;
            }

            $data = array_combine($headers, $row);

            if (empty($data['name']) || empty($data['slug'])) {
                $errors[$lineNum] = 'Missing required field: name or slug';
                $skipped++;

                continue;
            }

            try {
                $schoolData = $this->mapRow($data);
                School::updateOrCreate(['slug' => $schoolData['slug']], $schoolData);
                $imported++;
            } catch (\Throwable $e) {
                $errors[$lineNum] = $e->getMessage();
                $skipped++;
            }
        }

        return compact('imported', 'skipped', 'errors');
    }

    /**
     * Preview the first N rows of a CSV without committing.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function preview(string $filePath, int $limit = 10): Collection
    {
        $rows = array_map('str_getcsv', file($filePath));
        $headers = array_shift($rows);
        $headers = array_map(fn ($h) => strtolower(trim($h)), $headers);

        return collect(array_slice($rows, 0, $limit))
            ->map(fn ($row) => array_combine($headers, $row));
    }

    /**
     * @param  array<string, string>  $data
     * @return array<string, mixed>
     */
    private function mapRow(array $data): array
    {
        return [
            'name' => trim($data['name']),
            'slug' => trim($data['slug']),
            'type' => $data['type'] ?? 'Pesantren Modern',
            'jenjang' => isset($data['jenjang']) ? explode('|', $data['jenjang']) : [],
            'city' => $data['city'] ?? '',
            'province' => $data['province'] ?? '',
            'address' => $data['address'] ?? '',
            'short_desc' => $data['short_desc'] ?? '',
            'description' => $data['description'] ?? '',
            'founded_year' => (int) ($data['founded_year'] ?? 2000),
            'is_boarding' => filter_var($data['is_boarding'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'registration_open' => filter_var($data['registration_open'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'accreditation' => $data['accreditation'] ?? 'B',
            'image' => $data['image'] ?? 'images/schools/default.jpg',
            'tags' => isset($data['tags']) ? explode('|', $data['tags']) : [],
            'uang_pangkal' => (int) ($data['uang_pangkal'] ?? 0),
            'spp_monthly' => (int) ($data['spp_monthly'] ?? 0),
            'asrama_monthly' => (int) ($data['asrama_monthly'] ?? 0),
            'seragam_fee' => (int) ($data['seragam_fee'] ?? 0),
            'ekskul_fee' => (int) ($data['ekskul_fee'] ?? 0),
            'study_tour_fee' => (int) ($data['study_tour_fee'] ?? 0),
            'latitude' => ! empty($data['latitude']) ? (float) $data['latitude'] : null,
            'longitude' => ! empty($data['longitude']) ? (float) $data['longitude'] : null,
            'whatsapp_e164' => $data['whatsapp'] ?? null,
        ];
    }
}
