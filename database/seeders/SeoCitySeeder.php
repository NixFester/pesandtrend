<?php

namespace Database\Seeders;

use App\Models\SeoCity;
use Illuminate\Database\Seeder;

class SeoCitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Jakarta',
                'slug' => 'jakarta',
                'sort_order' => 1,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Jakarta | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Jakarta. Pilihan sekolah berkualitas dengan fasilitas lengkap di ibu kota.',
            ],
            [
                'name' => 'Yogyakarta',
                'slug' => 'yogyakarta',
                'sort_order' => 2,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Yogyakarta | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Yogyakarta. Kota pelajar dengan pilihan pendidikan Islam berkualitas.',
            ],
            [
                'name' => 'Bandung',
                'slug' => 'bandung',
                'sort_order' => 3,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Bandung | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Bandung. Pilihan sekolah dengan lingkungan yang sejuk dan berkualitas.',
            ],
            [
                'name' => 'Surabaya',
                'slug' => 'surabaya',
                'sort_order' => 4,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Surabaya | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Surabaya. Kota terbesar di Jawa Timur dengan pilihan pendidikan Islam lengkap.',
            ],
            [
                'name' => 'Bogor',
                'slug' => 'bogor',
                'sort_order' => 5,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Bogor | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Bogor. Dekat Jakarta dengan lingkungan asri dan berkualitas.',
            ],
            [
                'name' => 'Depok',
                'slug' => 'depok',
                'sort_order' => 6,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Depok | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Depok. Pilihan sekolah berkualitas di kawasan metropolitan Jakarta.',
            ],
            [
                'name' => 'Tangerang',
                'slug' => 'tangerang',
                'sort_order' => 7,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Tangerang | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Tangerang. Pilihan pendidikan Islam berkualitas di Banten.',
            ],
            [
                'name' => 'Semarang',
                'slug' => 'semarang',
                'sort_order' => 8,
                'meta_title' => 'Sekolah Islam & Pesantren Terbaik di Semarang | Pesantrends',
                'meta_description' => 'Temukan sekolah Islam dan pesantren terbaik di Semarang. Kota Atlas dengan pilihan pendidikan Islam yang beragam.',
            ],
        ];

        foreach ($cities as $city) {
            SeoCity::firstOrCreate(
                ['slug' => $city['slug']],
                $city
            );
        }
    }
}
