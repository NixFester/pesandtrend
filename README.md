# Pesantrends — Laravel App

Aplikasi web Laravel yang dibangun dari file desain Figma **Pesantrends.fig** — platform pencarian, perbandingan, dan kalkulasi biaya sekolah Islam & pesantren di Indonesia.

## Fitur

| Halaman | Route | Deskripsi |
|---------|-------|-----------|
| Beranda | `/` | Hero + pencarian (kota/jenjang), statistik, sekolah unggulan, fitur biaya transparan, "Mengapa Pesantrends", program unggulan, testimoni, CTA, artikel, newsletter |
| Cari Sekolah | `/sekolah` | Listing + filter (keyword, kota, jenjang, tipe) + urutan (rating/murah/populer), pencarian populer, terakhir dilihat, paginasi |
| Detail Sekolah | `/sekolah/{slug}` | Profil lengkap: tentang, program, fasilitas, prestasi, rincian biaya, statistik alumni, simpan sekolah |
| Bandingkan | `/bandingkan` | Bandingkan hingga 3 sekolah side-by-side (biaya, rasio guru, fasilitas, program) |
| Kalkulator Biaya | `/kalkulator` | Hitung total biaya lengkap (pangkal, SPP, asrama, seragam, ekskul, study tour) + isi otomatis dari data sekolah |
| Artikel | `/artikel` | Blog: kategori, pencarian, terpopuler, detail artikel dengan format konten |
| Auth | `/masuk`, `/daftar` | Registrasi, login, logout (session-based) |
| Dashboard | `/dashboard` | Sekolah tersimpan, rekomendasi, terakhir dilihat, artikel populer |

## Teknologi

- **Laravel 13** (PHP 8.4) — Blade, Eloquent, SQLite
- **Tailwind CSS 4** + Vite (font Plus Jakarta Sans self-hosted via bunny fonts)
- Ikon Lucide (inline SVG component `<x-icon name="..."/>`)

## Design System (dari Figma)

- Font: **Plus Jakarta Sans** (400/500/600/700/800)
- Warna: hijau hutan `#12462A`, emas `#C9A227`, krem `#F1E8C9`, sage `#85AA96`
- Kartu rounded-2xl dengan shadow lembut, chip/badge, section label uppercase emas

## Menjalankan

```bash
cd pesantrends
php artisan serve --host=0.0.0.0 --port=3000
```

Database SQLite sudah termigrasi & ter-seed (8 sekolah, 7 artikel, 3 testimoni, 14 fasilitas, 13 program).

Untuk rebuild CSS/JS: `npm install && npm run build`

## Struktur Penting

```
app/Http/Controllers/     # Home, School, Compare, Calculator, Article, Newsletter, Auth, Dashboard
app/Models/               # School, Facility, Program, Achievement, Article, Testimonial, Subscriber, SavedSchool
database/migrations/      # Skema lengkap + pivot (school_facility, school_program, saved_schools)
database/seeders/         # PesantrendsSeeder — seluruh konten dari file Figma
resources/views/          # Layout + components (navbar, footer, school-card, article-card, icon)
public/images/            # 14 foto asli dari file Figma (sekolah, hero, artikel)
resources/css/app.css     # Design tokens Tailwind 4 (@theme)
```
