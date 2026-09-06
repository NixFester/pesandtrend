<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use App\Models\Facility;
use App\Models\Program;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        if ($this->isDevMode()) {
            $this->form->fill($this->getSampleData());
        } else {
            $this->form->fill();
        }

        $this->callHook('afterFill');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle new programs
        if (! empty($data['new_programs'])) {
            $data['programs'] = array_merge($data['programs'] ?? [], $data['new_programs']);
            unset($data['new_programs']);
        }

        // Handle new facilities
        if (! empty($data['new_facilities'])) {
            $data['facilities'] = array_merge($data['facilities'] ?? [], $data['new_facilities']);
            unset($data['new_facilities']);
        }

        return $data;
    }

    protected function isDevMode(): bool
    {
        return app()->environment('local', 'development')
            || config('app.debug', false);
    }

    protected function getSampleData(): array
    {
        // Generate unique slug based on timestamp
        $timestamp = now()->format('His');
        $name = "Pesantren Sample {$timestamp}";
        $slug = Str::slug($name);

        // Get random facilities and programs
        $facilityIds = Facility::inRandomOrder()->limit(6)->pluck('id')->toArray();
        $programIds = Program::inRandomOrder()->limit(3)->pluck('id')->toArray();

        $cities = [
            ['city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta'],
            ['city' => 'Bandung', 'province' => 'Jawa Barat'],
            ['city' => 'Surabaya', 'province' => 'Jawa Timur'],
            ['city' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            ['city' => 'Bogor', 'province' => 'Jawa Barat'],
            ['city' => 'Semarang', 'province' => 'Jawa Tengah'],
            ['city' => 'Malang', 'province' => 'Jawa Timur'],
            ['city' => 'Depok', 'province' => 'Jawa Barat'],
        ];

        $location = $cities[array_rand($cities)];

        $types = [
            'Pesantren Modern',
            'Pesantren Salaf',
            'SDIT',
            'SMPIT',
            'SMAIT',
        ];

        $jenjangOptions = [
            ['RA', 'TK'],
            ['SD', 'SMP'],
            ['SMP', 'SMA'],
            ['RA', 'TK', 'SD', 'SMP', 'SMA'],
            ['SMP', 'SMA', 'MA'],
        ];

        $shortDescs = [
            'Pesantren modern dengan kurikulum nasional dan pendidikan agama komprehensif.',
            'Sekolah Islam terpadu dengan program full day dan tahsin intensif.',
            'Madrasah Aliyah unggulan dengan bahasa Arab dan kitab kuning.',
            'SMP Islam terpadu dengan penguatan STEM dan tahfidz pilihan.',
            'Pesantren salafi dengan sorogan kitab kuning dan tahfidz 30 juz.',
        ];

        $descriptions = [
            "Pesantren ini adalah lembaga pendidikan Islam terkemuka yang memadukan kurikulum nasional dengan pendidikan agama yang komprehensif. Berdiri sejak 2010, kami telah mencetak ribuan alumni yang berhasil di berbagai bidang.\n\nDengan sistem asrama modern, pembinaan tahfidz intensif, serta pendekatan pendidikan yang seimbang antara dunia dan akhirat.",

            "Sekolah Islam terpadu berbasis full day school yang berkomitmen mencetak generasi Qurani, cerdas, dan berkarakter. Dengan rasio guru dan siswa yang ideal, setiap anak mendapat perhatian penuh dalam proses belajar.\n\nKurikulum memadukan Kurikulum Merdeka dengan penguatan pendidikan agama.",

            "Menghadirkan pendidikan menengah Islam yang seimbang antara prestasi akademik dan penguatan iman. Program unggulan STEM menjadikan siswa terbiasa dengan teknologi tanpa kehilangan jati diri Muslim.\n\nLingkungan sekolah yang hijau dan teduh, didukung guru-guru profesional.",

            "Menghidupkan tradisi keilmuan salaf dengan metode sorogan, bandongan, dan musyawarah kitab kuning. Santri dibina langsung oleh asatidz bersanad dalam naungan kiai.\n\nProgram tahfidz dengan target kelulusan sesuai kemampuan masing-masing.",

            "Menggabungkan keunggulan akademik dengan kedalaman spiritual. Program bilingual dan sains terintegrasi menjadikan siswa mampu memahami sains melalui lensa wahyu.\n\nSekolah berada di lingkungan asri dengan konsep green school.",

            "Menawarkan international class dengan kurikulum Cambridge dipadukan kajian Islam. Penguatan bahasa Inggris dan Arab secara simultan membuka jalan ke universitas dunia.\n\nBimbingan intensif persiapan UTBK memastikan setiap siswa siap bersaing.",
        ];

        $tags = [
            ['Tahfidz Quran', 'Pesantren Modern', 'Berasrama'],
            ['Full Day', 'Tahfidz Quran', 'Sekolah Islam Terpadu'],
            ['Bahasa Arab', 'Kitab Kuning', 'Berasrama'],
            ['SMPIT Unggulan', 'STEM & Robotika', 'Full Day'],
            ['Tahfidz Quran', 'Kitab Kuning', 'Pesantren Salafi'],
            ['Bilingual', 'Quran Science', 'SMPIT'],
        ];

        $key = array_rand($shortDescs);

        return [
            'name' => $name,
            'slug' => $slug,
            'type' => $types[array_rand($types)],
            'jenjang' => $jenjangOptions[array_rand($jenjangOptions)],
            'city' => $location['city'],
            'province' => $location['province'],
            'address' => "Jl. Raya {$location['city']} No. {$timestamp}, {$location['city']}, {$location['province']}",
            'short_desc' => $shortDescs[$key],
            'description' => $descriptions[$key],
            'tags' => $tags[$key],
            'badge' => 'Sample Data',
            'accreditation' => 'A',
            'image' => null,
            'latitude' => $this->randomCoordinate()['lat'],
            'longitude' => $this->randomCoordinate()['lng'],
            'whatsapp_e164' => '6281234567890',
            'whatsapp_label' => 'Admin PPDB Sample',
            'students_count' => rand(100, 2000),
            'teacher_ratio' => '1:'.rand(8, 18),
            'founded_year' => rand(1970, 2020),
            'is_boarding' => (bool) rand(0, 1),
            'registration_open' => true,
            'admission_status' => 'open',
            'is_verified' => false,
            'is_featured' => false,
            'is_published' => false, // Keep unpublished for dev
            'uang_pangkal' => rand(3000000, 10000000),
            'spp_monthly' => rand(800000, 2500000),
            'asrama_monthly' => rand(0, 1) ? rand(300000, 800000) : 0,
            'seragam_fee' => rand(500000, 2000000),
            'ekskul_fee' => rand(300000, 1200000),
            'study_tour_fee' => rand(500000, 3000000),
            'facilities' => $facilityIds,
            'programs' => $programIds,
        ];
    }

    protected function randomCoordinate(): array
    {
        // Indonesia bounds (roughly)
        $lat = -6.5 + (mt_rand(0, 1000) / 1000) * 8; // -6.5 to 1.5
        $lng = 95 + (mt_rand(0, 1000) / 1000) * 30; // 95 to 125

        return [
            'lat' => round($lat, 6),
            'lng' => round($lng, 6),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // If dev mode, redirect to edit page after creation
        if ($this->isDevMode()) {
            return $this->getResource()::getUrl('edit', ['record' => $this->record]);
        }

        return parent::getRedirectUrl();
    }
}
