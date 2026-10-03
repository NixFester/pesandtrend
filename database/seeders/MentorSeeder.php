<?php

namespace Database\Seeders;

use App\Models\Mentor;
use App\Models\MentorImage;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    public function run(): void
    {
        $mentors = [
            [
                'name' => 'Ustadz Dr. H. Abdullah Faqih, M.Pd.I',
                'slug' => 'ustadz-abdullah-faqih',
                'tagline' => 'Ahli Tahfidz & Metodologi Pembelajaran Al-Quran',
                'description' => "Ustadz Abdullah Faqih adalah seorang ulama dan educator yang telah mengabdikan lebih dari 20 tahun dalam dunia pendidikan Islam. Lulusan Universitas Al-Azhar Kairo ini spesialis dalam metodologi pengajaran tahfidz Al-Quran yang efektif dan terukur.\n\nBeliau telah membantu ratusan santriage melalui program tahfidz intensif dengan pendekatan yang menekankan pemahaman maknanya, bukan sekadar hapalan. Metodenya yang khas menggabungkan teknik tradisional sorogan dengan evaluasi modern berbasis teknologi.",
                'expertise' => ['Tahfidz Quran', 'Metodologi Pendidikan Islam', 'SMP', 'SMA'],
                'price' => 250000,
                'whatsapp_number' => '6281234567801',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Ustadzah Dr. Nurul Hidayah, M.Si',
                'slug' => 'ustadzah-nurul-hidayah',
                'tagline' => 'Spesialis Bahasa Arab & Kitab Kuning',
                'description' => "Ustadzah Nurul Hidayah adalah alumni Universitas Islam Negeri Syarif Hidayatullah dan Magister Linguistik Arab dari Universitas Damascus. Dengan pengalaman mengajar lebih dari 15 tahun, beliau spesialis dalam pengajaran bahasa Arab praktis untuk komuniasi sehari-hari.\n\nPendekatannya yang interaktif dan kontekstual membuat siswa dapat terbiasa berbahasa Arab dalam waktu singkat. Banyak alumni bimbingannya yang kemudian melanjutkan studi ke Timur Tengah dan berhasil berbicara bahasa Arab dengan fasih.",
                'expertise' => ['Bahasa Arab', 'Kitab Kuning', 'SNBT', 'SMA'],
                'price' => 350000,
                'whatsapp_number' => '6281234567802',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Ustadz H. Muhammad Rizki, Lc.',
                'slug' => 'ustadz-muhammad-rizki',
                'tagline' => 'Expert Matematika & Persiapan UTBK',
                'description' => "Ustadz Muhammad Rizki memiliki latar belakang pendidikan Licence en Droit dari Universitas Al-Azhar dan Magister Pendidikan Matematika dari UPI Bandung. Beliau dikenal sebagai pengajar matematika yang mampu menjelaskan konsep kompleks dengan cara yang mudah dipahami.\n\nSelama 12 tahun mengajar, beliau telah membantu ratusan siswa meningkatkan nilai UTBK mereka secara signifikan. Pendekatannya berfokus pada pemahaman konsep dasar sebelum menuju soal-soal tingkat tinggi.",
                'expertise' => ['Matematika', 'UTBK', 'SNBT', 'SMA'],
                'price' => 500000,
                'whatsapp_number' => '6281234567803',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Ustadz Dr. Ahmad Zainuri, M.Sc',
                'slug' => 'ustadz-ahmad-zainuri',
                'tagline' => 'Spesialis Sains & STEM Islam',
                'description' => "Ustadz Ahmad Zainuri menggabungkan keahlian di bidang sains dan pendidikan Islam. Dengan gelar Master of Science dari University of Edinburgh dan doktor dari Universitas Indonesia, beliau telah mengembangkan kurikulum STEM Islam yang unik.\n\nBeliau mengajarkan sains sebagai bukti kebesaran Allah SWT, sehingga siswa tidak hanya memahami rumus tetapi juga mengagumi ciptaan Tuhan. Pendekatannya telah diterapkan di berbagai pesantren modern dengan hasil yang luar biasa.",
                'expertise' => ['Fisika', 'Kimia', 'STEM', 'SMP', 'SMA'],
                'price' => 350000,
                'whatsapp_number' => '6281234567804',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Ustadzah Hj. Fatimah Az-Zahra, S.Pd.I',
                'slug' => 'ustadzah-fatimah-az-zahra',
                'tagline' => 'Konsultan Pendidikan Anak & Tahsin Al-Quran',
                'description' => "Ustadzah Fatimah adalah konsultan pendidikan anak usia dini dan dasar yang telah berpengalaman lebih dari 18 tahun. Dengan pendekatan yang penuh kasih sayang dan berbasis riset, beliau membantu ribuan orang tua dalam membimbing anak-anak mereka.\n\nKonsultasinya mencakup metode tahsin Al-Quran untuk anak-anak, pengembangan karakter, dan penyesuaian kurikulum pembelajaran di rumah. Beliau juga aktif memberikan seminar parenting di berbagai kota.",
                'expertise' => ['Tahsin Quran', 'Bahasa Inggris', 'SD', 'SMP'],
                'price' => 200000,
                'whatsapp_number' => '6281234567805',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Ustadz H. Basyirun, M.A',
                'slug' => 'ustadz-basyirun',
                'tagline' => 'Ahli Fiqih & Ushul Fiqih',
                'description' => "Ustadz Basyirun adalah ulama yang menguasai ilmu fiqih dan ushul fiqih dengan baik. Dengan gelar Master of Arts dari Universitas Al-Azhar, beliau spesialis dalam memberikan konsultasi terkait hukum Islam sehari-hari dan persiapan ujian masuk lembaga pendidikan Islam.\n\nBeliau sering menjadi nara sumber dalam program pelatihan guru agama dan menjadi penguji dalam berbagai kompetisi fiqih nasional. Pendekatannya yang sistematis membantu siswa memahami hukum Islam secara komprehensif.",
                'expertise' => ['Kitab Kuning', 'Fiqih', 'Bahasa Arab', 'SMA', 'Pesantren'],
                'price' => 300000,
                'whatsapp_number' => '6281234567806',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Ustadzah Dr. Aisyah Rahman, S.H., M.H.',
                'slug' => 'ustadzah-aisyah-rahman',
                'tagline' => 'Spesialis Bahasa Inggris & Soft Skills Islami',
                'description' => "Ustadzah Aisyah menggabungkan latar belakang hukum Islam dan linguistik dalam pendekatannya mengajar bahasa Inggris. Dengan pengalaman tinggal dan mengajar di Inggris selama 5 tahun, beliau memahami nuansa budaya yang penting dalam penguasaan bahasa.\n\nBeliau spesialis membimbing siswa yang ingin meningkatkan kemampuan bahasa Inggris mereka untuk keperluan akademik maupun professional, dengan tetap berlandaskan nilai-nilai Islam.",
                'expertise' => ['Bahasa Inggris', 'Bilingual', 'SNBP', 'SMA'],
                'price' => 400000,
                'whatsapp_number' => '6281234567807',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Ustadz H. Samsul Arifin, Lc., M.E.Sy.',
                'slug' => 'ustadz-samsul-arifin',
                'tagline' => 'Ahli Tajwid & Qiraat Sab’ah',
                'description' => "Ustadz Samsul Arifin adalah salah satu maestro tajwid dan qiraat di Indonesia. Dengan sanad hadits dan qiraat yang bersambung hingga Nabi Muhammad SAW, beliau menjadi rujukan dalam hal membaca Al-Quran dengan tartil.\n\nProgram konsultasinya mencakup koreksi bacaan Al-Quran, pembelajaran tajwid teoritis dan praktis, serta latihan qiraat sab'ah untuk yang ingin mendalami ilmu bacaan Al-Quran secara的专业.",
                'expertise' => ['Tahfidz Quran', 'Tahsin', 'Bahasa Arab', 'Semua Jenjang'],
                'price' => 250000,
                'whatsapp_number' => '6281234567808',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Ustadz Dr. Yusuf Hamdani, M.Sc',
                'slug' => 'ustadz-yusuf-hamdani',
                'tagline' => 'Spesialis Biologi & Persiapan Kedokteran',
                'description' => "Ustadz Yusuf Hamdani memiliki passion dalam dunia kesehatan dan pendidikan. Dengan gelar Master of Science dari IPB University dan doktor dari Universitas Indonesia, beliau spesialis dalam mengajarkan biologi dengan pendekatan integratif.\n\nBanyak siswa bimbingannya yang berhasil masuk fakultas kedokteran dan kesehatan di PTN terkemuka. Beliau menekankan pemahaman mendalam tentang tubuh manusia sebagai amanah Allah SWT yang harus dijaga.",
                'expertise' => ['Biologi', 'UTBK', 'SNBT', 'SMA'],
                'price' => 500000,
                'whatsapp_number' => '6281234567809',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Ustadzah Laily Mahmudah, S.Psi., M.Psi',
                'slug' => 'ustadzah-laily-mahmudah',
                'tagline' => 'Psikolog Pendidikan & Konselor Remaja',
                'description' => "Ustadzah Laily adalah psikolog pendidikan yang memahami tantangan remaja Muslim di era modern. Dengan pengalaman klinis lebih dari 10 tahun, beliau membantu siswa mengatasi masalah belajar, motivasi, dan tekanan sosial.\n\nKonsultasinya mencakup tes kecerdasan dan minat bakat, penanganan stress ujian, pengembangan kebiasaan belajar efektif, dan bimbingan karir berdasarkan nilai-nilai Islam.",
                'expertise' => ['Tahsin Quran', 'Bahasa Inggris', 'SD', 'SMP'],
                'price' => 150000,
                'whatsapp_number' => '6281234567810',
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        $imageNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        foreach ($mentors as $index => $mentorData) {
            $mentor = Mentor::firstOrCreate(['slug' => $mentorData['slug']], $mentorData);

            // Create mentor image if not exists
            $imageNumber = $imageNumbers[$index];
            $imagePath = "images/ustad/{$imageNumber}.jpg";

            MentorImage::firstOrCreate(
                [
                    'mentor_id' => $mentor->id,
                    'image_path' => $imagePath,
                ],
                [
                    'type' => 'portfolio',
                    'caption' => "Foto {$mentor->name}",
                    'sort_order' => 0,
                ]
            );
        }
    }
}
