<?php

namespace Tests\Feature;

use App\Services\SchoolsImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SchoolsImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_imports_schools_from_csv(): void
    {
        Storage::fake('local');

        $csvContent = implode("\n", [
            'name,slug,city,province,address,uang_pangkal,spp_monthly,accreditation',
            'Pesantren Test 1,pesantren-test-1,Bogor,Jawa Barat,Jl. Raya Bogor,5000000,500000,A',
            'Pesantren Test 2,pesantren-test-2,Bandung,Jawa Barat,Jl. Raya Bandung,6000000,600000,B',
        ]);

        $tempPath = storage_path('app/test_import.csv');
        file_put_contents($tempPath, $csvContent);

        $service = app(SchoolsImportService::class);
        $result = $service->importFromCsv($tempPath);

        $this->assertEquals(2, $result['imported']);
        $this->assertEquals(0, $result['skipped']);
        $this->assertDatabaseHas('schools', ['slug' => 'pesantren-test-1']);
        $this->assertDatabaseHas('schools', ['slug' => 'pesantren-test-2']);

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }
}
