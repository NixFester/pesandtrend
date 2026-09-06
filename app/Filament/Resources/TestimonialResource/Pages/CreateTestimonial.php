<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = TestimonialResource::class;

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
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
        $timestamp = now()->format('His');

        $names = [
            'Ibu Sari Rahayu',
            'Bapak Ahmad Wijaya',
            'Ibu Fatimah Zahra',
            'Bapak Hasanuddin',
            'Ibu Nurul Hidayah',
            'Bapak Ustaz Abdul Malik',
        ];

        $roles = [
            'Orang Tua Santri',
            'Wali Santri',
            'Ayah Siswa',
            'Ibu Siswa',
            'Alumni Santri',
        ];

        $quotes = [
            'Alhamdulillah, anak saya sangat nyaman belajar di sini. Guru-gurunya sabar dan ramah. Kurikulum yang diterapkan sangat baik untuk perkembangan anak.',

            'Saya sangat terkesan dengan metode pengajaran di pesantren ini. Anak saya tidak hanya belajar ilmu agama, tapi juga ilmu duniawi dengan seimbang.',

            'Pesantren ini memiliki fasilitas yang lengkap dan lingkungan yang sangat mendukung untuk belajar. Anak saya jadi lebih mandiri dan bertanggung jawab.',

            'Pelayanan admin sangat responsif. Semua pertanyaan saya dijawab dengan cepat dan informatif. Terima kasih atas kerjasamanya!',

            'Saya merekomendasikan pesantren ini kepada semua orang tua yang ingin anaknya mendapat pendidikan Islam yang komprehensif dan berkualitas.',

            'Metode tahfidz di sini sangat efektif. Anak saya sudah hafal 5 juz dalam waktu 2 tahun. Jazakallahu khairan atas dedikasi para ustadz.',
        ];

        $name = $names[array_rand($names)];
        $key = array_rand($quotes);

        return [
            'name' => $name,
            'role' => $roles[array_rand($roles)],
            'quote' => $quotes[$key],
            'rating' => rand(4, 5),
            'is_published' => true,
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
