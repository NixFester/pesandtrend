<?php

namespace App\Filament\Pages;

use App\Models\School;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Http\Response;

class ExportSchools extends Page
{
    protected static ?string $routePath = 'export';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'Export Sekolah';

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.pages.export-schools';

    public function downloadCsv(): ?Response
    {
        $schools = School::all();

        if ($schools->isEmpty()) {
            Notification::make()
                ->title('Tidak ada data sekolah')
                ->warning()
                ->send();

            return null;
        }

        $filename = 'sekolah_export_'.date('Y-m-d_His').'.csv';

        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, [
            'Nama', 'Slug', 'Tipe', 'Kota', 'Provinsi', 'Alamat',
            'SPP Bulanan', 'Asrama', 'Uang Pangkal', 'Akreditasi',
            'Jumlah Siswa', 'Tahun Berdiri', 'Berasrama',
            'Publikasi', 'Verifikasi', 'Unggulan',
        ]);

        foreach ($schools as $school) {
            fputcsv($handle, [
                $school->name,
                $school->slug,
                $school->type,
                $school->city,
                $school->province,
                $school->address,
                $school->spp_monthly,
                $school->asrama_monthly,
                $school->uang_pangkal,
                $school->accreditation,
                $school->students_count,
                $school->founded_year,
                $school->is_boarding ? 'Ya' : 'Tidak',
                $school->is_published ? 'Ya' : 'Tidak',
                $school->is_verified ? 'Ya' : 'Tidak',
                $school->is_featured ? 'Ya' : 'Tidak',
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
