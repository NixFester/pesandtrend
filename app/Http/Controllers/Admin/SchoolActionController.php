<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class SchoolActionController extends Controller
{
    public function togglePublish(School $school): RedirectResponse
    {
        Gate::authorize('update', $school);

        $school->update([
            'is_published' => ! $school->is_published,
        ]);

        return redirect()->back()->with('success', $school->is_published
            ? 'Sekolah berhasil dipublikasikan.'
            : 'Publikasi sekolah berhasil dibatalkan.');
    }

    public function destroy(School $school): RedirectResponse
    {
        Gate::authorize('delete', $school);

        $school->delete();

        return redirect()->route('filament.admin.resources.schools.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    public function export(): Response
    {
        Gate::authorize('viewAny', School::class);

        $schools = School::all();

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
