<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\School;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::limit(5)->get();

        if ($schools->isEmpty()) {
            $this->command->warn('No schools found. Run SchoolSeeder first or create schools manually.');

            return;
        }

        $campaigns = [
            [
                'title' => 'Renovasi Asrama Putri Pondok Pesantern Al-Munawwir',
                'slug' => 'renovasi-asrama-putri-al-munawwir',
                'category' => 'asrama',
                'target_amount' => 50000000,
                'description' => "Pondok Pesantern Al-Munawwir membutuhkan bantuan untuk renovasi asrama putri yang sudah berusia lebih dari 30 tahun. Atap sudah bocor dan perlu diganti.\n\nDonasi Anda akan digunakan untuk:\n- Penggantian atap asrama\n- Pengecatan dinding\n- Perbaikan kamar mandi bersama\n- Penambahan ventilasi udara",
                'is_featured' => true,
                'image' => 'images/masjid/renov1.jpg',
            ],
            [
                'title' => 'Beasiswa Santri Berprestasi TPQ Imam Syafi\'i',
                'slug' => 'beasiswa-santri-berprestasi-imam-syafii',
                'category' => 'scholarship',
                'target_amount' => 25000000,
                'description' => "Bantu anak-anak berprestasi di TPQ Imam Syafi'i untuk terus belajar mengaji dan menghafal Al-Quran.\n\nBeasiswa ini akan digunakan untuk:\n- Bantuan biaya SPP bulanan\n- Buku-buku bacaan Islam\n- Perlengkapan menghafal Al-Quran",
                'is_featured' => true,
                'image' => 'images/masjid/renov2.jpg',
            ],
            [
                'title' => 'Renovasi Masjid Jami\' Al-Ikhlas',
                'slug' => 'renovasi-masjid-jami-al-ikhlas',
                'category' => 'mosque',
                'target_amount' => 100000000,
                'description' => "Masjid Jami' Al-Ikhlas yang digunakan oleh ratusan warga sekitar dan siswa memerlukan renovasi total.\n\nDana akan digunakan untuk:\n- Perbaikan struktur masjid\n- Sistem sound system\n- Pencahayaan LED\n- AC untuk ruang madrasah",
                'is_featured' => true,
                'image' => 'images/masjid/renov3.jpg',
            ],
            [
                'title' => 'Peralatan Laboratorium Komputer',
                'slug' => 'peralatan-lab-komputer',
                'category' => 'equipment',
                'target_amount' => 30000000,
                'description' => "SDIT Nurul Islam membutuhkan tambahan komputer untuk laboratorium agar siswa bisa belajar teknologi informasi.\n\nDonasi untuk:\n- 10 unit komputer baru\n- Printer laser\n- Software pendidikan",
                'is_featured' => false,
                'image' => 'images/masjid/renov1.jpg',
            ],
            [
                'title' => 'Bantuan Medis untuk Santri Yatim',
                'slug' => 'bantuan-medis-santri-yatim',
                'category' => 'medical',
                'target_amount' => 15000000,
                'description' => "Yayasan X memiliki puluhan santri yatim yang membutuhkan bantuan biaya kesehatan.\n\nDana akan digunakan untuk:\n- Check kesehatan rutin\n- Biaya obat-obatan\n- Penanganan darurat",
                'is_featured' => false,
                'image' => 'images/masjid/renov2.jpg',
            ],
            [
                'title' => 'Peningkatan Fasilitas Perpustakaan',
                'slug' => 'peningkatan-fasilitas-perpustakaan',
                'category' => 'facilities',
                'target_amount' => 20000000,
                'description' => "Perpustakaan MTS Yaa Bunayya membutuhkan rak buku baru dan koleksi buku Islam.\n\nDonasi untuk:\n- 5 rak buku baru\n- 200 buku Islam\n- Meja baca siswa",
                'is_featured' => false,
                'image' => 'images/masjid/renov3.jpg',
            ],
        ];

        foreach ($campaigns as $index => $data) {
            Campaign::create([
                'school_id' => $schools[$index % $schools->count()]->id,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'category' => $data['category'],
                'target_amount' => $data['target_amount'],
                'current_amount' => 0,
                'description' => $data['description'],
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'status' => 'active',
                'is_featured' => $data['is_featured'],
                'image' => $data['image'],
            ]);
        }

        $this->command->info('Created '.count($campaigns).' sample campaigns.');
    }
}
