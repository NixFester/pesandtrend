<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Article;
use App\Models\Facility;
use App\Models\Program;
use App\Models\School;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesantrendsSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Fasilitas ----------
        $facilities = ['Masjid Utama', 'Asrama AC', 'Lab Komputer', 'Lapangan Olahraga',
            'Perpustakaan Digital', 'Klinik Kesehatan', 'Kantin Halal', 'Studio Multimedia',
            'Masjid Sekolah', 'Ruang Seni', 'Lab IPA', 'Lapangan Bermain', 'Kantin Sehat', 'Perpustakaan'];
        foreach ($facilities as $f) {
            Facility::firstOrCreate(['name' => $f]);
        }

        // ---------- Program ----------
        $programs = [
            ['name' => 'Tahfidz Al-Quran 30 Juz', 'slug' => 'tahfidz-30-juz', 'icon' => 'book-open', 'description' => 'Program tahfidz intensif dengan target hafalan 30 juz dan sanad bersambung.'],
            ['name' => 'STEM & Robotika Islam', 'slug' => 'stem-robotika', 'icon' => 'cpu', 'description' => 'Pembelajaran sains, teknologi, dan robotika dalam kerangka nilai-nilai Islam.'],
            ['name' => 'Bahasa Arab Intensif', 'slug' => 'bahasa-arab-intensif', 'icon' => 'languages', 'description' => 'Pendalaman bahasa Arab dengan muhadatsah harian dan kurikulum Al-Azhar.'],
            ['name' => 'Leadership & Dakwah', 'slug' => 'leadership-dakwah', 'icon' => 'flag', 'description' => 'Pembinaan karakter pemimpin dan keterampilan dakwah untuk generasi masa depan.'],
            ['name' => 'Quran Science Integration', 'slug' => 'quran-science', 'icon' => 'flask-conical', 'description' => 'Integrasi ayat-ayat kauniyah dengan pembelajaran sains modern.'],
            ['name' => 'Digital Literacy Islam', 'slug' => 'digital-literacy', 'icon' => 'monitor', 'description' => 'Literasi digital beretika untuk menyiapkan santri menghadapi era teknologi.'],
        ];
        foreach ($programs as $p) {
            Program::firstOrCreate(['slug' => $p['slug']], $p);
        }
        $extraPrograms = [
            ['name' => 'Tahfidz Quran', 'slug' => 'tahfidz-quran', 'icon' => 'book-open', 'description' => null],
            ['name' => 'Bahasa Arab', 'slug' => 'bahasa-arab', 'icon' => 'languages', 'description' => null],
            ['name' => 'Kitab Kuning', 'slug' => 'kitab-kuning', 'icon' => 'scroll', 'description' => null],
            ['name' => 'Bilingual', 'slug' => 'bilingual', 'icon' => 'globe', 'description' => null],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'icon' => 'dumbbell', 'description' => null],
            ['name' => 'Kepemimpinan', 'slug' => 'kepemimpinan', 'icon' => 'flag', 'description' => null],
            ['name' => 'Multimedia', 'slug' => 'multimedia', 'icon' => 'camera', 'description' => null],
        ];
        foreach ($extraPrograms as $p) {
            Program::firstOrCreate(['slug' => $p['slug']], $p);
        }

        // ---------- Sekolah ----------
        $schools = [
            [
                'name' => 'Pesantren Modern Darussalam',
                'slug' => 'pesantren-modern-darussalam',
                'type' => 'Pesantren Modern',
                'jenjang' => ['SMPIT', 'SMA Islam'],
                'city' => 'Bogor',
                'province' => 'Jawa Barat',
                'address' => 'Jl. K.H. Ahmad Dahlan No. 12, Bogor, Jawa Barat',
                'short_desc' => 'Pesantren modern dengan kurikulum nasional dan pendidikan agama komprehensif.',
                'description' => "Pesantren Modern Darussalam adalah lembaga pendidikan Islam terkemuka yang memadukan kurikulum nasional dengan pendidikan agama yang komprehensif. Berdiri sejak 1985, kami telah mencetak ribuan alumni yang berhasil di berbagai bidang.\n\nDengan sistem asrama modern, pembinaan tahfidz intensif, serta pendekatan pendidikan yang seimbang antara dunia dan akhirat, Darussalam menjadi pilihan utama orang tua Muslim yang menginginkan pendidikan terbaik bagi putra-putrinya.",
                'rating' => 4.9,
                'reviews_count' => 312,
                'students_count' => 1240,
                'teacher_ratio' => '1:12',
                'founded_year' => 1985,
                'is_boarding' => true,
                'registration_open' => true,
                'is_featured' => true,
                'accreditation' => 'A',
                'image' => 'images/schools/darussalam.jpg',
                'badge' => 'Pendaftaran Buka',
                'tags' => ['Tahfidz Quran', 'Pesantren Modern', 'Berasrama'],
                'alumni_stats' => [
                    'Lulus UN 100%' => '100%',
                    'Diterima PTN' => '85%',
                    'Hafiz 3 Juz' => '120+ alumni',
                    'Beasiswa Luar Negeri' => '23 alumni',
                ],
                'uang_pangkal' => 7500000,
                'spp_monthly' => 1500000,
                'asrama_monthly' => 500000,
                'seragam_fee' => 1250000,
                'ekskul_fee' => 900000,
                'study_tour_fee' => 1100000,
                'facilities' => ['Masjid Utama', 'Asrama AC', 'Lab Komputer', 'Lapangan Olahraga', 'Perpustakaan Digital', 'Klinik Kesehatan', 'Kantin Halal', 'Studio Multimedia'],
                'programs' => ['tahfidz-quran', 'bahasa-arab', 'kitab-kuning', 'stem-robotika', 'leadership-dakwah'],
                'achievements' => [
                    ['title' => 'Juara 1 Olimpiade Matematika Nasional', 'year' => 2024],
                    ['title' => 'Peringkat 3 Nasional Tahfidz Quran', 'year' => 2023],
                    ['title' => 'Sekolah Terbaik Jawa Barat', 'year' => 2022],
                ],
            ],
            [
                'name' => 'SDIT Al-Hikmah',
                'slug' => 'sdit-al-hikmah',
                'type' => 'Sekolah Islam Terpadu',
                'jenjang' => ['SDIT'],
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'address' => 'Jl. H. R. Rasuna Said No. 45, Jakarta Selatan, DKI Jakarta',
                'short_desc' => 'Sekolah dasar Islam terpadu dengan program full day dan tahsin intensif.',
                'description' => "SDIT Al-Hikmah adalah sekolah dasar Islam terpadu berbasis full day school yang berkomitmen mencetak generasi Qurani, cerdas, dan berkarakter. Dengan rasio guru dan siswa yang ideal, setiap anak mendapat perhatian penuh dalam proses belajar.\n\nKurikulum memadukan Kurikulum Merdeka dengan penguatan pendidikan agama, tahsin-tahfidz, serta pengembangan karakter sejak dini.",
                'rating' => 4.8,
                'reviews_count' => 256,
                'students_count' => 320,
                'teacher_ratio' => '1:10',
                'founded_year' => 2005,
                'is_boarding' => false,
                'registration_open' => true,
                'is_featured' => true,
                'accreditation' => 'A',
                'image' => 'images/schools/sdit-alhikmah.jpg',
                'badge' => 'Pendaftaran Buka',
                'tags' => ['Full Day', 'Tahfidz Quran', 'Sekolah Islam Terpadu'],
                'alumni_stats' => [
                    'Lanjut ke SMP Islam' => '96%',
                    'Hafiz 3 Juz' => '45+ alumni',
                    'Juara MTQ Kota' => '8 kali',
                ],
                'uang_pangkal' => 6000000,
                'spp_monthly' => 1200000,
                'asrama_monthly' => 0,
                'seragam_fee' => 950000,
                'ekskul_fee' => 700000,
                'study_tour_fee' => 850000,
                'facilities' => ['Masjid Sekolah', 'Ruang Seni', 'Lab IPA', 'Lapangan Bermain', 'Kantin Sehat', 'Perpustakaan'],
                'programs' => ['tahfidz-quran', 'digital-literacy', 'olahraga'],
                'achievements' => [
                    ['title' => 'Juara 1 Lomba Cipta Puisi Islami Tingkat Provinsi', 'year' => 2024],
                    ['title' => 'Sekolah Adiwiyata Mandiri', 'year' => 2023],
                ],
            ],
            [
                'name' => 'MA Unggulan Gontor Putri',
                'slug' => 'ma-unggulan-gontor-putri',
                'type' => 'Pesantren Modern',
                'jenjang' => ['MA'],
                'city' => 'Ponorogo',
                'province' => 'Jawa Timur',
                'address' => 'Jl. Raya Siman KM. 4, Ponorogo, Jawa Timur',
                'short_desc' => 'Madrasah Aliyah unggulan dengan bahasa Arab dan kitab kuning sebagai pilar utama.',
                'description' => "MA Unggulan Gontor Putri melanjutkan tradisi pendidikan pesantren dengan penekanan pada penguasaan bahasa Arab, Inggris, dan kitab kuning. Seluruh santri tinggal di asrama dengan pembinaan 24 jam.\n\nEkosistem keilmuan yang kondusif, tradisi berbahasa asing di lingkungan pesantren, serta kurikulum diniyah yang kuat menjadikan lulusannya siap melanjutkan studi ke universitas dalam dan luar negeri.",
                'rating' => 4.9,
                'reviews_count' => 428,
                'students_count' => 2100,
                'teacher_ratio' => '1:14',
                'founded_year' => 1976,
                'is_boarding' => true,
                'registration_open' => true,
                'is_featured' => true,
                'accreditation' => 'A',
                'image' => 'images/schools/gontor-putri.jpg',
                'badge' => 'Pendaftaran Buka',
                'tags' => ['Bahasa Arab', 'Kitab Kuning', 'Berasrama'],
                'alumni_stats' => [
                    'Diterima PTN & PT Luar Negeri' => '80%',
                    'Lulusan Tahfidz' => '300+ alumni',
                    'Beasiswa Luar Negeri' => '57 alumni',
                ],
                'uang_pangkal' => 9000000,
                'spp_monthly' => 1800000,
                'asrama_monthly' => 650000,
                'seragam_fee' => 1400000,
                'ekskul_fee' => 1000000,
                'study_tour_fee' => 1500000,
                'facilities' => ['Masjid Utama', 'Asrama AC', 'Lab Komputer', 'Perpustakaan Digital', 'Klinik Kesehatan', 'Kantin Halal', 'Lapangan Olahraga'],
                'programs' => ['bahasa-arab', 'kitab-kuning', 'leadership-dakwah', 'tahfidz-30-juz'],
                'achievements' => [
                    ['title' => 'Juara Umum Musabaqah Tilawatil Quran Nasional', 'year' => 2024],
                    ['title' => 'Sekolah Berbudaya Lingkungan', 'year' => 2023],
                    ['title' => 'Kampus Terbaik Tata Kelola', 'year' => 2022],
                ],
            ],
            [
                'name' => 'SMPIT Nurul Fikri',
                'slug' => 'smpit-nurul-fikri',
                'type' => 'Sekolah Islam Terpadu',
                'jenjang' => ['SMPIT'],
                'city' => 'Depok',
                'province' => 'Jawa Barat',
                'address' => 'Jl. Kelapa Dua Raya No. 88, Depok, Jawa Barat',
                'short_desc' => 'SMP Islam terpadu dengan penguatan STEM dan tahfidz pilihan.',
                'description' => "SMPIT Nurul Fikri menghadirkan pendidikan menengah Islam yang seimbang antara prestasi akademik dan penguatan iman. Program unggulan STEM & Robotika Islam menjadikan siswa terbiasa dengan teknologi tanpa kehilangan jati diri Muslim.\n\nLingkungan sekolah yang hijau dan teduh, didukung guru-guru profesional lulusan universitas ternama.",
                'rating' => 4.7,
                'reviews_count' => 198,
                'students_count' => 680,
                'teacher_ratio' => '1:13',
                'founded_year' => 2001,
                'is_boarding' => false,
                'registration_open' => true,
                'is_featured' => true,
                'accreditation' => 'A',
                'image' => 'images/schools/smpit-nurulfikri.jpg',
                'badge' => 'Pendaftaran Buka',
                'tags' => ['SMPIT Unggulan', 'STEM & Robotika', 'Full Day'],
                'alumni_stats' => [
                    'Diterima SMA Unggulan' => '92%',
                    'Juara Robotik Nasional' => '3 kali',
                ],
                'uang_pangkal' => 5500000,
                'spp_monthly' => 1600000,
                'asrama_monthly' => 0,
                'seragam_fee' => 1050000,
                'ekskul_fee' => 800000,
                'study_tour_fee' => 950000,
                'facilities' => ['Masjid Sekolah', 'Lab Komputer', 'Lab IPA', 'Lapangan Olahraga', 'Perpustakaan Digital', 'Kantin Halal', 'Ruang Seni'],
                'programs' => ['stem-robotika', 'tahfidz-quran', 'digital-literacy', 'bahasa-arab'],
                'achievements' => [
                    ['title' => 'Juara 2 Robotic Competition Asia', 'year' => 2024],
                    ['title' => 'SMP Islam Terbaik Tingkat Kota', 'year' => 2023],
                ],
            ],
            [
                'name' => 'Pesantren Darussalam',
                'slug' => 'pesantren-darussalam',
                'type' => 'Pesantren Salafi',
                'jenjang' => ['SMPIT', 'SMA Islam', 'MA'],
                'city' => 'Bogor',
                'province' => 'Jawa Barat',
                'address' => 'Jl. Raya Cibinong KM. 3, Bogor, Jawa Barat',
                'short_desc' => 'Pesantren salafi dengan sorogan kitab kuning dan tahfidz 30 juz.',
                'description' => "Pesantren Darussalam menghidupkan tradisi keilmuan salaf dengan metode sorogan, bandongan, dan musyawarah kitab kuning. Santri dibina langsung oleh asatidz bersanad dalam naungan kiai.\n\nProgram tahfidz 30 juz dengan target kelulusan sesuai kemampuan masing-masing santri, didukung suasana pesantren yang tenang dan jauh dari hiruk pikuk kota.",
                'rating' => 4.8,
                'reviews_count' => 175,
                'students_count' => 450,
                'teacher_ratio' => '1:9',
                'founded_year' => 1972,
                'is_boarding' => true,
                'registration_open' => false,
                'is_featured' => false,
                'accreditation' => 'A',
                'image' => 'images/schools/darussalam-2.png',
                'badge' => null,
                'tags' => ['Tahfidz Quran', 'Kitab Kuning', 'Pesantren Salafi'],
                'alumni_stats' => [
                    'Hafiz 30 Juz' => '60+ alumni',
                    'Melanjutkan ke Timur Tengah' => '35 alumni',
                ],
                'uang_pangkal' => 4000000,
                'spp_monthly' => 900000,
                'asrama_monthly' => 350000,
                'seragam_fee' => 750000,
                'ekskul_fee' => 450000,
                'study_tour_fee' => 600000,
                'facilities' => ['Masjid Utama', 'Perpustakaan', 'Klinik Kesehatan', 'Kantin Halal', 'Lapangan Olahraga'],
                'programs' => ['tahfidz-30-juz', 'kitab-kuning', 'bahasa-arab'],
                'achievements' => [
                    ['title' => 'Santri Terbaik Musabaqah Hifzil Quran Provinsi', 'year' => 2024],
                ],
            ],
            [
                'name' => 'SMPIT Al-Furqan Bandung',
                'slug' => 'smpit-al-furqan-bandung',
                'type' => 'Sekolah Islam Terpadu',
                'jenjang' => ['SMPIT'],
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'address' => 'Jl. Buah Batu No. 210, Bandung, Jawa Barat',
                'short_desc' => 'SMPIT dengan program bilingual dan Quran science integration.',
                'description' => "SMPIT Al-Furqan Bandung menggabungkan keunggulan akademik dengan kedalaman spiritual. Program bilingual dan Quran Science Integration menjadikan siswa mampu memahami sains melalui lensa wahyu.\n\nSekolah berada di lingkungan asri dengan konsep green school dan masjid sebagai pusat kegiatan.",
                'rating' => 4.6,
                'reviews_count' => 142,
                'students_count' => 200,
                'teacher_ratio' => '1:11',
                'founded_year' => 2010,
                'is_boarding' => false,
                'registration_open' => true,
                'is_featured' => false,
                'accreditation' => 'B',
                'image' => 'images/schools/alfurqan.png',
                'badge' => null,
                'tags' => ['Bilingual', 'Quran Science', 'SMPIT'],
                'alumni_stats' => [
                    'Diterima SMA Favorit' => '88%',
                ],
                'uang_pangkal' => 5000000,
                'spp_monthly' => 1350000,
                'asrama_monthly' => 0,
                'seragam_fee' => 900000,
                'ekskul_fee' => 650000,
                'study_tour_fee' => 800000,
                'facilities' => ['Masjid Sekolah', 'Lab Komputer', 'Lab IPA', 'Kantin Sehat', 'Lapangan Bermain'],
                'programs' => ['quran-science', 'bilingual', 'digital-literacy'],
                'achievements' => [
                    ['title' => 'Juara 1 Young Scientist Award Provinsi', 'year' => 2024],
                ],
            ],
            [
                'name' => 'SMA IT Al-Izzah',
                'slug' => 'sma-it-al-izzah',
                'type' => 'Sekolah Islam Terpadu',
                'jenjang' => ['SMA Islam'],
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'address' => 'Jl. Darmo Permai No. 55, Surabaya, Jawa Timur',
                'short_desc' => 'SMA Islam dengan program international class dan persiapan PTN.',
                'description' => "SMA IT Al-Izzah menawarkan international class dengan kurikulum Cambridge dipadukan kajian Islam. Penguatan bahasa Inggris dan Arab secara simultan membuka jalan ke universitas dunia.\n\nBimbingan intensif persiapan UTBK memastikan setiap siswa siap bersaing memasuki PTN impian.",
                'rating' => 4.7,
                'reviews_count' => 210,
                'students_count' => 180,
                'teacher_ratio' => '1:8',
                'founded_year' => 2008,
                'is_boarding' => true,
                'registration_open' => true,
                'is_featured' => false,
                'accreditation' => 'A',
                'image' => 'images/schools/al-izzah.png',
                'badge' => 'Pendaftaran Buka',
                'tags' => ['Internasional', 'Bilingual', 'SMA Islam'],
                'alumni_stats' => [
                    'Diterima PTN' => '90%',
                    'Beasiswa Luar Negeri' => '19 alumni',
                ],
                'uang_pangkal' => 8500000,
                'spp_monthly' => 1750000,
                'asrama_monthly' => 600000,
                'seragam_fee' => 1300000,
                'ekskul_fee' => 950000,
                'study_tour_fee' => 1800000,
                'facilities' => ['Masjid Sekolah', 'Asrama AC', 'Lab Komputer', 'Studio Multimedia', 'Perpustakaan Digital', 'Kantin Halal'],
                'programs' => ['bilingual', 'leadership-dakwah', 'digital-literacy', 'stem-robotika'],
                'achievements' => [
                    ['title' => 'Best Islamic School Award Jawa Timur', 'year' => 2024],
                ],
            ],
            [
                'name' => 'SDIT Insan Cendekia Amanah',
                'slug' => 'sdit-insan-cendekia-amanah',
                'type' => 'Sekolah Islam Terpadu',
                'jenjang' => ['SDIT'],
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'address' => 'Jl. Pondok Aren Raya No. 17, Tangerang Selatan, Banten',
                'short_desc' => 'SDIT kecil yang hangat dengan fokus tahsin dan karakter.',
                'description' => "SDIT Insan Cendekia Amanah mengusung konsep sekolah keluarga dengan jumlah siswa per kelas yang kecil. Setiap anak dibimbing tahsin hingga lancar membaca Al-Quran sebelum lulus.\n\nProgram karakter berbasis adab keseharian menjadikan anak-anak tumbuh santun dan mandiri.",
                'rating' => 4.8,
                'reviews_count' => 96,
                'students_count' => 90,
                'teacher_ratio' => '1:7',
                'founded_year' => 2015,
                'is_boarding' => false,
                'registration_open' => true,
                'is_featured' => false,
                'accreditation' => 'B',
                'image' => 'images/schools/insan-cendekia.jpg',
                'badge' => null,
                'tags' => ['Full Day', 'Tahsin', 'Keluarga'],
                'alumni_stats' => [
                    'Lancar Tahsin' => '100%',
                ],
                'uang_pangkal' => 4500000,
                'spp_monthly' => 1100000,
                'asrama_monthly' => 0,
                'seragam_fee' => 800000,
                'ekskul_fee' => 500000,
                'study_tour_fee' => 650000,
                'facilities' => ['Masjid Sekolah', 'Lapangan Bermain', 'Kantin Sehat', 'Perpustakaan'],
                'programs' => ['tahfidz-quran', 'olahraga'],
                'achievements' => [
                    ['title' => 'Sekolah Ramah Anak Kota Tangerang Selatan', 'year' => 2023],
                ],
            ],
        ];

        foreach ($schools as $data) {
            $fac = $data['facilities'];
            $progs = $data['programs'];
            $achs = $data['achievements'];
            unset($data['facilities'], $data['programs'], $data['achievements']);

            $school = School::firstOrCreate(['slug' => $data['slug']], $data);
            $school->facilities()->sync(Facility::whereIn('name', $fac)->pluck('id')->all());
            $school->programs()->sync(Program::whereIn('slug', $progs)->pluck('id')->all());
            foreach ($achs as $ach) {
                Achievement::firstOrCreate([
                    'school_id' => $school->id,
                    'title' => $ach['title'],
                ], $ach);
            }
        }

        // ---------- Artikel ----------
        $articles = [
            [
                'title' => '5 Tips Memilih Pesantren yang Tepat untuk Buah Hati Anda',
                'slug' => '5-tips-memilih-pesantren-yang-tepat',
                'category' => 'Panduan Orang Tua',
                'excerpt' => 'Memilih pesantren bukan sekadar mencari yang paling terkenal. Berikut lima kunci memastikan pesantren tersebut cocok untuk perkembangan spiritual dan akademik anak Anda.',
                'image' => 'images/articles/two-boys.png',
                'read_minutes' => 5,
                'views' => 12400,
                'published_at' => now()->subDays(2),
                'content' => "Memilih pesantren adalah keputusan besar yang akan membentuk masa depan buah hati Anda. Bukan sekadar soal popularitas atau fasilitas mewah, tetapi kesesuaian antara nilai, metode pendidikan, dan kebutuhan anak.\n\n## 1. Kenali Kebutuhan Anak Terlebih Dahulu\n\nSetiap anak unik. Ada yang cocok dengan lingkungan kompetitif, ada yang butuh pendekatan personal. Perhatikan kesiapan emosional anak untuk tinggal jauh dari orang tua, kemandirian dasar, dan motivasinya sendiri untuk menuntut ilmu.\n\n## 2. Pelajari Kurikulum yang Digunakan\n\nPerhatikan keseimbangan antara pelajaran diniyah (agama) dan umum. Pesantren modern umumnya memadukan Kurikulum Merdeka dengan pendalaman kitab. Pastikan porsinya sesuai dengan harapan keluarga.\n\n## 3. Cek Kredibilitas Pengasuh dan Asatidz\n\nKiai dan asatidz adalah jantung pesantren. Telusuri latar belakang keilmuan mereka, sanad yang dimiliki, dan bagaimana mereka membina santri dalam keseharian.\n\n## 4. Kunjungi Langsung Lingkungan Pesantren\n\nFoto dan video tidak pernah cukup. Datanglah, rasakan suasana, lihat interaksi santri dengan ustadz, kebersihan asrama, dan aktivitas ibadah harian.\n\n## 5. Sesuaikan dengan Kemampuan Finansial\n\nPilih yang transparan soal biaya. Gunakan fitur kalkulator biaya Pesantrends untuk memperkirakan total pengeluaran setahun agar tidak ada beban tak terduga.\n\nDengan lima langkah ini, semoga Allah memudahkan Anda menemukan rumah ilmu terbaik bagi anak.",
            ],
            [
                'title' => 'Perbedaan Pesantren Salafi, Modern, dan Terpadu: Mana yang Cocok?',
                'slug' => 'perbedaan-pesantren-salafi-modern-terpadu',
                'category' => 'Edukasi',
                'excerpt' => 'Tiga model pesantren ini memiliki pendekatan berbeda dalam memadukan pendidikan agama dan umum. Kenali perbedaannya agar tidak salah pilih.',
                'image' => 'images/articles/architecture.jpg',
                'read_minutes' => 7,
                'views' => 9800,
                'published_at' => now()->subDays(5),
                'content' => "Di Indonesia, orang tua sering dihadapkan pada tiga pilihan besar: pesantren salafi, pesantren modern, dan sekolah Islam terpadu. Ketiganya baik, tetapi memiliki karakter yang sangat berbeda.\n\n## Pesantren Salafi\n\nMenitikberatkan pada pemahaman kitab kuning dengan metode sorogan dan bandongan. Bahasa pengantar sehari-hari adalah bahasa Arab atau bahasa daerah. Cocok untuk keluarga yang menginginkan kedalaman keilmuan agama tradisional dan sanad yang jelas.\n\n## Pesantren Modern\n\nMemadukan kurikulum pesantren dengan sekolah formal (SMP/SMA/MA), fasilitas asrama modern, dan penguatan bahasa asing. Santri mendapat ijazah formal sekaligus pendalaman diniyah. Pilihan tepat bagi yang menginginkan keseimbangan dunia-akhirat.\n\n## Sekolah Islam Terpadu\n\nSekolah umum dengan penguatan nilai Islam, umumnya non-asrama (full day). Cocok untuk anak usia dini yang belum siap tinggal di asrama, atau keluarga yang ingin tetap terlibat penuh dalam pendidikan anak.\n\n## Jadi, Mana yang Cocok?\n\nKembalikan pada kebutuhan anak: kesiapan tinggal di asrama, target hafalan, rencana jenjang lanjut, dan tentu anggaran keluarga. Gunakan fitur perbandingan Pesantrends untuk melihat perbedaan biaya dan program hingga tiga sekolah sekaligus.",
            ],
            [
                'title' => 'Biaya Sekolah Islam Swasta 2026: Panduan Lengkap & Realistis',
                'slug' => 'biaya-sekolah-islam-swasta-2026',
                'category' => 'Keuangan',
                'excerpt' => 'Investasi pendidikan Islam terbaik tidak harus menguras kantong. Berikut panduan merencanakan biaya pendidikan anak di sekolah Islam swasta.',
                'image' => 'images/articles/girls-reading.png',
                'read_minutes' => 6,
                'views' => 15300,
                'published_at' => now()->subDays(9),
                'content' => "Banyak orang tua mengira pendidikan Islam berkualitas selalu mahal. Faktanya, dengan perencanaan yang tepat, biaya tersebut bisa dikelola tanpa mengorbankan kebutuhan keluarga lain.\n\n## Komponen Biaya yang Sering Terlupakan\n\nSelain SPP bulanan, ada uang pangkal, seragam dan perlengkapan, biaya kegiatan ekstrakurikuler, hingga study tour tahunan. Total biaya tahun pertama bisa 30-50% lebih besar dari perkiraan awal.\n\n## Kisaran Realistis 2026\n\n- SDIT di kota besar: Rp1,1 - Rp2,5 juta/bulan\n- SMPIT/SMA Islam: Rp1,3 - Rp3 juta/bulan\n- Pesantren modern (termasuk asrama): Rp2 - Rp4,5 juta/bulan\n\n## Strategi Mengelola Biaya\n\n1. **Mulai dana pendidikan sejak dini** — setidaknya 12 bulan sebelum pendaftaran.\n2. **Manfaatkan diskon pembayaran tahunan** — banyak sekolah memberi potongan 5-10%.\n3. **Cari program beasiswa** — sebagian besar pesantren terkemuka punya beasiswa hafiz dan prestasi.\n\nGunakan Kalkulator Biaya Pesantrends untuk menghitung total pengeluaran setahun, termasuk komponen yang jarang disebutkan di brosur.",
            ],
            [
                'title' => 'Kenali Tanda-Tanda Anak Siap Masuk Pesantren',
                'slug' => 'tanda-anak-siap-masuk-pesantren',
                'category' => 'Panduan Orang Tua',
                'excerpt' => 'Tidak semua anak siap untuk tinggal di pesantren. Berikut adalah indikator kesiapan emosional dan spiritual yang perlu diperhatikan.',
                'image' => 'images/articles/female-students.jpg',
                'read_minutes' => 4,
                'views' => 7600,
                'published_at' => now()->subDays(12),
                'content' => "Mengirim anak ke pesantren adalah ibadah sekaligus amanah besar. Namun memaksakan anak yang belum siap justru berisiko membuatnya jauh dari agama. Berikut tanda-tanda anak sudah siap:\n\n## Kesiapan Emosional\n\n- Anak bisa merawat dirinya sendiri: mandi, menjaga kebersihan, mengelola barang pribadi.\n- Terbiasa tidur terpisah dari orang tua.\n- Bisa mengungkapkan perasaannya kepada orang dewasa selain orang tua.\n\n## Kesiapan Spiritual\n\n- Menyukai kegiatan ibadah, tidak sekadar patuh.\n- Punya motivasi sendiri untuk belajar agama, misalnya ingin hafal surat favoritnya.\n\n## Kesiapan Sosial\n\n- Nyaman bermain dan bekerja sama dengan anak lain.\n- Pernah ikut kegiatan di luar rumah beberapa hari (kemah, menginap di rumah kerabat).\n\nJika sebagian besar tanda di atas sudah muncul, alhamdulillah, anak Anda siap. Jika belum, tidak ada salahnya menunda setahun sambil menanamkan kemandirian. Pesantren menunggu, kesiapan anak tidak bisa dipercepat.",
            ],
            [
                'title' => 'Bagaimana Mempersiapkan Anak untuk Ujian Masuk Sekolah Islam?',
                'slug' => 'persiapan-ujian-masuk-sekolah-islam',
                'category' => 'Tips & Trik',
                'excerpt' => 'Ujian masuk SDIT, SMPIT, dan MA unggulan semakin kompetitif. Panduan persiapan intensif 3 bulan yang terbukti efektif.',
                'image' => 'images/articles/bookstore.png',
                'read_minutes' => 8,
                'views' => 11200,
                'published_at' => now()->subDays(16),
                'content' => "Sekolah Islam unggulan kini menjadi incaran banyak keluarga. Rasio pendaftar yang mencapai 1:5 membuat persiapan matang menjadi keharusan.\n\n## Peta Ujian Masuk\n\nUmumnya terdiri dari tes akademik (literasi & numerasi), tes baca Al-Quran dan hafalan juz, wawancara anak dan orang tua, serta observasi perilaku.\n\n## Program Persiapan 3 Bulan\n\n**Bulan pertama — Fondasi:** rutinkan membaca Al-Quran dengan tajwid benar setiap ba'da maghrib, dan kerjakan latihan literasi-numerasi 30 menit per hari.\n\n**Bulan kedua — Pendalaman:** fokus pada hafalan surat pendek dan doa harian yang biasanya diujikan, tambah simulasi tes tertulis dua kali sepekan.\n\n**Bulan ketiga — Penyesuaian:** latihan wawancara dengan skenario umum, biasakan duduk tenang 60 menit, dan atur jam tidur agar sesuai jadwal ujian.\n\n## Kesalahan yang Harus Dihindari\n\nJangan menekan anak dengan target berlebihan. Kegagalan ujian bukan akhir dunia — banyak jalan menuju surga ilmu. Yang terpenting adalah anak tumbuh mencintai belajar, bukan takut padanya.",
            ],
            [
                'title' => 'Program Beasiswa di Sekolah Islam Terbaik Indonesia 2026',
                'slug' => 'program-beasiswa-sekolah-islam-2026',
                'category' => 'Keuangan',
                'excerpt' => 'Daftar beasiswa penuh dan parsial di pesantren dan sekolah Islam swasta terkemuka. Syarat pendaftaran dan tips lolos seleksi.',
                'image' => 'images/articles/aerial.png',
                'read_minutes' => 7,
                'views' => 8900,
                'published_at' => now()->subDays(21),
                'content' => "Keterbatasan finansial bukan penghalang mendapatkan pendidikan Islam terbaik. Berbagai pesantren dan sekolah Islam terkemuka membuka program beasiswa setiap tahun.\n\n## Jenis Beasiswa yang Umum Tersedia\n\n1. **Beasiswa hafiz** — penuh untuk penghafal 5-30 juz, umumnya di pesantren tahfidz.\n2. **Beasiswa prestasi** — akademik maupun non-akademik (olimpiade, MTQ, robotik).\n3. **Beasiswa dhuafa** — bagi keluarga prasejahtera dengan niat belajar yang kuat, biasanya bekerja sama dengan lembaga filantropi.\n\n## Sekolah dengan Program Beasiswa Aktif 2026\n\n- Pesantren Modern Darussalam: beasiswa hafiz 3 juz ke atas (hingga 100% SPP)\n- MA Unggulan Gontor Putri: beasiswa santri berprestasi (hingga 75%)\n- SMPIT Nurul Fikri: beasiswa robotik dan olimpiade (hingga 50%)\n\n## Tips Lolos Seleksi\n\n- Siapkan portofolio hafalan/prestasi dengan legalisir resmi.\n- Tulis motivasi belajar dengan jujur dan spesifik.\n- Daftar sejak dini — kuota beasiswa biasanya terisi sebelum gelombang kedua.\n\nSemoga setiap anak yang berniat menuntut ilmu dimudahkan jalannya.",
            ],
            [
                'title' => 'Pesantrends Luncurkan Fitur Perbandingan Real-Time Biaya Sekolah',
                'slug' => 'pesantrends-luncurkan-perbandingan-real-time',
                'category' => 'Berita',
                'excerpt' => 'Update terbaru Pesantrends.id memungkinkan orang tua membandingkan rincian biaya hingga 3 sekolah sekaligus dengan transparansi penuh.',
                'image' => 'images/articles/greenhouse.jpg',
                'read_minutes' => 3,
                'views' => 4500,
                'published_at' => now()->subDays(30),
                'content' => "Jakarta — Pesantrends.id resmi meluncurkan fitur perbandingan real-time biaya sekolah Islam. Kini orang tua dapat membandingkan rincian biaya hingga 3 sekolah sekaligus — dari uang pangkal, SPP bulanan, hingga biaya study tour — dalam satu tampilan.\n\n\"Fitur ini lahir dari keluhan banyak orang tua yang kaget oleh biaya-biaya tersembunyi setelah anak diterima. Kami ingin transparansi menjadi standar, bukan bonus,\" ujar founder Pesantrends.\n\nFitur perbandingan mencakup komponen biaya, fasilitas, program unggulan, hingga statistik alumni. Data diperbarui langsung dari pihak sekolah mitra yang telah diverifikasi.\n\nSelain itu, Kalkulator Biaya kini memperhitungkan skema pembayaran tahunan dan estimasi inflasi 5% per tahun agar perencanaan keuangan keluarga lebih akurat.",
            ],
        ];

        foreach ($articles as $a) {
            Article::firstOrCreate(['slug' => $a['slug']], $a);
        }

        // ---------- Testimoni ----------
        $testimonials = [
            ['name' => 'Ibu Sari R.', 'role' => 'Orang Tua Santri', 'quote' => 'Alhamdulillah! Berhasil menemukan pesantren yang cocok untuk anak saya dalam 2 hari. Informasinya sangat lengkap dan terpercaya.'],
            ['name' => 'Bpk. Ahmad F.', 'role' => 'Orang Tua Santri', 'quote' => 'Fitur perbandingan biaya sangat membantu perencanaan keuangan keluarga kami. Tidak ada biaya tersembunyi!'],
            ['name' => 'Ibu Dewi M.', 'role' => 'Orang Tua Santri', 'quote' => 'Sebelumnya bingung harus mulai dari mana, sekarang semua informasi ada di satu tempat. Sangat rekomendasikan!'],
        ];
        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(['name' => $t['name']], $t);
        }

        // ---------- Users & Admin ----------
        User::firstOrCreate(
            ['email' => 'admin@pesantrends.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@pesantrends.id'],
            [
                'name' => 'User Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
