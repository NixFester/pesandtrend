<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

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
        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (! empty($data['is_published'])) {
            $data['published_at'] = now();
        }

        unset($data['is_published']);

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
        $title = "Artikel Sample {$timestamp}";
        $slug = Str::slug($title);

        $categories = ['Berita', 'Panduan', 'Tips', 'Profil', 'Edukasi'];
        $isPublished = true;

        $shortExcerpts = [
            'Panduan lengkap untuk memilih sekolah Islam yang tepat untuk anak Anda.',
            'Tips efektif membantu anak beradaptasi di lingkungan sekolah baru.',
            'Mengenal lebih dekat sistem pendidikan pesantren modern.',
            'Cara membangun kebiasaan belajar yang baik sejak dini.',
            'Memahami kurikulum terintegrasi di sekolah Islam.',
        ];

        $contents = [
            "# Pendahuluan\n\nMemilih sekolah yang tepat merupakan keputusan penting bagi setiap orang tua. Sekolah Islam menawarkan kombinasi unik antara pendidikan akademik dan pembentukan karakter Islami.\n\n## Mengapa Memilih Sekolah Islam?\n\nSekolah Islam memiliki beberapa keunggulan:\n\n- **Kurikulum Terintegrasi**: Memadukan kurikulum nasional dengan pendidikan agama\n- **Pembentukan Karakter**: Fokus pada akhlak dan nilai-nilai Islam\n- **Lingkungan yang Islami**: Suasana sekolah yang mendukung perkembangan spiritual\n\n## Tips Memilih Sekolah Islam\n\n1. **Cek Akreditasi**: Pastikan sekolah memiliki akreditasi yang baik\n2. **Kunjungi Langsung**: Lihat fasilitas dan lingkungan sekolah\n3. **Tanya Alumni**: Pengalaman alumni bisa jadi referensi berharga\n4. **Perhatikan Kurikulum**: Pastikan kurikulum sesuai dengan harapan Anda",

            "# Persiapan Menjelang Tahun Ajaran Baru\n\nTahun ajaran baru selalu membawa semangat dan tantangan baru. Berikut beberapa tips untuk mempersiapkan anak Anda.\n\n## Aspek Akademik\n\n- Siapkan alat tulis dan buku pelajaran\n- Buat jadwal belajar yang teratur\n- Cari tahu kurikulum yang akan diajarkan\n\n## Aspek Mental\n\n- Ajak anak berbicara tentang harapan mereka\n- Kunjungi sekolah sebelum hari pertama\n- Siapkan mental untuk beradaptasi dengan lingkungan baru\n\n## Aspek Perlengkapan\n\n- Beli seragam sekolah\n- Siapkan tas yang sesuai\n- Jangan lupa seragam olahraga",

            "# Sistem Boarding School di Pesantren Modern\n\nPesantren modern dengan sistem asrama (boarding school) menawarkan pengalaman pendidikan yang holistik.\n\n## Keunggulan Sistem Boarding\n\n**Pengawasan Penuh**\nSiswa berada di lingkungan yang terkontrol 24 jam, sehingga orang tua tidak perlu khawatir.\n\n**Pembinaan Karakter**\nRutinitas harian meliputi ibadah, belajar, dan kegiatan positif lainnya.\n\n**Kedisiplinan Tinggi**\nSistem yang terstruktur membantu membangun kedisiplinan siswa.\n\n## Pertimbangan Sebelum Memilih\n\n- Kemampuan finansial\n- Jarak dari rumah\n- Kondisi kesehatan anak\n- Kesiapan mental anak",

            "# Membangun Kebiasaan Belajar Sejak Dini\n\nKebiasaan belajar yang baik tidak terbentuk secara instan. Dibutuhkan proses yang konsisten dan dukungan dari orang tua serta lingkungan sekitar.\n\n## Langkah Awal\n\n- **Tetapkan Waktu Belajar**: Konsistensi waktu membantu anak membentuk rutinitas\n- **Sediakan Ruang Belajar**: Tempat khusus yang tenang dan nyaman untuk belajar\n- **Batasi Gangguan**: Jauhkan gadget dan televisi saat jam belajar\n\n## Teknik Belajar Efektif\n\n1. **Metode Pomodoro**: Belajar 25 menit, istirahat 5 menit\n2. **Mind Mapping**: Membuat peta konsep untuk memahami materi\n3. **Repetisi Terjadwal**: Mengulang materi secara berkala agar lebih melekat\n\n## Peran Orang Tua\n\nOrang tua berperan sebagai fasilitator, bukan pengawas. Berikan apresiasi atas usaha anak, bukan hanya hasil akhir.",

            "# Kurikulum Terintegrasi di Sekolah Islam\n\nKurikulum terintegrasi memadukan ilmu pengetahuan umum dengan nilai-nilai keislaman dalam satu kesatuan pembelajaran yang utuh.\n\n## Komponen Kurikulum\n\n**Pendidikan Umum**\nMatematika, IPA, Bahasa Indonesia, dan mata pelajaran nasional lainnya tetap diajarkan sesuai standar Kemendikbud.\n\n**Pendidikan Agama**\nTahfidz Al-Quran, Fiqih, Aqidah Akhlak, dan Bahasa Arab menjadi bagian integral dari jadwal harian.\n\n**Pengembangan Diri**\nEkstrakurikuler, kegiatan sosial, dan program kepemimpinan melengkapi pembentukan karakter siswa.\n\n## Keuntungan Kurikulum Terintegrasi\n\n- Siswa tidak merasa terbebani dengan dua kurikulum terpisah\n- Nilai-nilai Islam diterapkan dalam konteks pembelajaran sehari-hari\n- Lulusan memiliki kompetensi akademik dan spiritual yang seimbang",
        ];

        $key = array_rand($shortExcerpts);

        return [
            'title' => $title,
            'slug' => $slug,
            'category' => $categories[array_rand($categories)],
            'excerpt' => $shortExcerpts[$key],
            'content' => $contents[$key],
            'read_minutes' => rand(3, 10),
            'views' => 0,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now()->format('Y-m-d H:i:s') : null,
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
