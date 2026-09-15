# 📋 Panduan Pengujian UI/UX, Fitur & Data Sampel - Pesantrends

Dokumen ini berisi **Daftar Periksa Pengujian (*Testing Checklist*)** UI/UX dan seluruh fitur sistem **Pesantrends**, dilengkapi dengan **Data Sampel (*Sample Data*)** siap pakai untuk semua formulir yang ada di dalam aplikasi.

---

## 🔑 Akun & Kredensial Pengujian

| Peran Sistem | Email | Kata Sandi | Halaman Utama / URL |
| :--- | :--- | :--- | :--- |
| **Administrator Utama** | `admin@pesantrends.id` | `password` | Panel Admin (`/admin`) |
| **Administrator Sekolah** | `admin@gmail.com` | `admin123` | Panel Admin (`/admin`) |
| **Orang Tua / Wali Santri** | `user@pesantrends.id` | `password` | Dashboard Orang Tua (`/dashboard`) |
| **Orang Tua (Akun Demo)** | `bambang.parent@example.com` | `password` | Dashboard Orang Tua (`/dashboard`) |

---

## 🎨 Bagian 1: Pengujian UI/UX & Responsivitas (UI/UX Checklist)

### A. Tampilan Seluler (Mobile View & Bottom Navigation)
- [ ] **Mobile Bottom Navigation Bar**:
  - Buka situs dengan layar smartphone (atau Inspect Element Mode HP: max 768px).
  - Pastikan 5 icon menu utama melayang di bawah: **Beranda**, **Cari**, **Simpan**, **Pendaftaran**, **Akun**.
  - Pastikan navigasi bawah ini **otomatis tersembunyi** ketika dibuka di layar Komputer / Laptop.
- [ ] **Touch Target & Visual Spacing**:
  - Semua tombol (seperti "Ajukan Pendaftaran" & "Chat WhatsApp") memiliki ukuran minimal 44px x 44px dan mudah ditekan jari.
  - Form input tidak kepotong atau *overflow* secara horizontal di HP.

### B. Interaksi & Dynamic Design
- [ ] **Hover & Active States**:
  - Kartu sekolah (*School Cards*) memiliki efek perbesaran halus (*zoom/lift shadow*) saat kursor diarahkan (hover).
  - Modul tab & filter pencarian merespon klik secara instan tanpa glitch visual.
- [ ] **Format Aksesibilitas & Teks**:
  - Tipografi legible (kombinasi font modern Inter/Plus Jakarta Sans/Roboto).
  - Kontras warna teks memenuhi standar kenyamanan baca (dark mode & light mode).

---

## ⚡ Bagian 2: Daftar Periksa Pengujian Fitur (Feature Checklist)

### 🏡 1. Modul Pengunjung & Pencarian Sekolah (Public Flow)
- [ ] **Katalog & GPS Filter**:
  - Buka `/sekolah`. Klik **"Gunakan Lokasi Saya"** → Pastikan lokasi terdeteksi dan jarak sekolah dihitung (*misal: 2.5 km*).
  - Coba filter: Tipe Pesantren, Provinsi, Kota, dan Range SPP Bulanan.
- [ ] **Peta Interaktif (OpenStreetMap)**:
  - Klik **Tampilan Peta** di `/sekolah`.
  - Pastikan *pin marker* muncul di lokasi pesantren dan pop-up info sekolah dapat diklik.
- [ ] **Perbandingan Sekolah (Compare)**:
  - Tambahkan 2 atau 3 sekolah ke fitur komparasi dari katalog.
  - Buka `/bandingkan` → Pastikan tabel perbandingan menampilkan SPP, fasilitas, jenjang, dan akreditasi secara bersandingan.
- [ ] **Kalkulator Biaya Pesantren**:
  - Buka `/kalkulator`.
  - Masukkan estimasi SPP dan Uang Pangkal → Pastikan kalkulator menghitung akumulasi biaya tahunan dengan akurat.
- [ ] **Artikel & Berita**:
  - Buka `/artikel` dan detail artikel `/artikel/{slug}`.
  - Pastikan isi berita, gambar utama, serta tag kategori tampil dengan rapi.

---

### 📝 2. Modul Pendaftaran Santri Baru (Parent Onboarding Flow)
- [ ] **Multi-Step Wizard (`/orang-tua/daftar`)**:
  - **Langkah 1 (Pilih Sekolah)**: Pilih sekolah dari dropdown / via query param `?school=1`.
  - **Langkah 2 (Biodata Santri)**: Upload foto & isi NIK, tanggal lahir, jenis kelamin.
  - **Langkah 3 (Data Wali)**: Otomatis terisi dari data akun login (bisa diedit).
  - **Langkah 4 (Unggah Berkas)**: Unggah dokumen KK, Akta, Rapor, & Foto (PDF/JPG/PNG max 5MB).
  - **Langkah 5 (Review & Bayar)**: Ringkasan data & rincian biaya pendaftaran + SPP bulan pertama.
- [ ] **Fitur Demo Quick-Fill**:
  - Tekan tombol **⚡ Mode Pengujian: Isi Ulang Data Contoh** untuk menguji pengisian instan.
- [ ] **Fitur Simpan Draft**:
  - Klik **Simpan Draft** di pertengahan langkah → Pastikan draft tersimpan dan dapat dilanjutkan nanti.
- [ ] **Pembayaran & Resi PDF**:
  - Klik **Bayar via Xendit** (atau gunakan **⚡ Simulasi Pembayaran Lunas (Instan)**).
  - Pastikan status pendaftaran berubah menjadi **TERBAYAR**.
  - Klik **Cetak Bukti Pembayaran** → File PDF Bukti Resmi A4 terunduh / terbuka rapi dengan stempel LUNAS.

---

### 🛡️ 3. Modul Administrator Sekolah (Admin Panel Flow `/admin`)
- [ ] **Dashboard Admin**:
  - Menampilkan widget statistik: Total Pendaftaran, Pendaftaran Lunas, Pendapatan, dan Jumlah Sekolah.
- [ ] **Kelola Sekolah (`SchoolResource`)**:
  - Tambah & edit data sekolah (Nama, Koordinat GPS Lat/Lng, SPP, Uang Pangkal, WhatsApp, Foto Galeri).
- [ ] **Impor Massal Sekolah**:
  - Unggah file CSV sekolah → Pratinjau data → Klik simpan untuk menambahkan data sekaligus.
- [ ] **Verifikasi Pendaftaran (`OnboardingApplicationResource`)**:
  - Buka pendaftaran masuk → Ubah status menjadi *Terverifikasi* atau *Ditolak* (dengan catatan alasan).
  - Tambahkan **Pembayaran Manual (Cash/Direct Transfer)** jika wali santri membayar langsung ke sekolah.
- [ ] **Kelola Artikel, Fasilitas, Program, & Testimoni**:
  - Menambah dan mengubah daftar Fasilitas unggulan, Program sekolah, dan Testimoni orang tua.

---

## 📦 Bagian 3: Data Sampel Pengujian (Sample Data for Forms)

Gunakan data sampel di bawah ini saat melakukan pengujian formulir di Pesantrends:

### 1. Form Pendaftaran Santri Baru (`/orang-tua/daftar`)

#### Step 1: Pilih Sekolah
- **Pilihan Sekolah**: `Pondok Pesantren Al-Munawwir` *(atau pilih sekolah pertama di list)*

#### Step 2: Biodata Santri
- **Nama Lengkap Santri**: `Ahmad Fathoni`
- **NIK (16 digit)**: `3273012804100005`
- **Jenis Kelamin**: `Laki-laki`
- **Tempat Lahir**: `Bandung`
- **Tanggal Lahir**: `2012-05-15`
- **Alamat Lengkap**: `Jl. Soekarno Hatta No. 45, RT 03/RW 08, Kel. Batununggal, Kec. Bandung Kidul, Kota Bandung, Jawa Barat 40266`
- **Sekolah Asal**: `SDIT Al-Azhar Bandung`
- **Target Jenjang**: `SMPIT / Tsanawiyah`

#### Step 3: Data Orang Tua / Wali
- **Nama Wali**: `Bambang Sudirman`
- **Email Wali**: `bambang.parent@example.com`
- **Nomor Telepon**: `081234567890`
- **Nomor WhatsApp**: `081234567890`

#### Step 4: Catatan & Dokumen
- **Catatan Tambahan**: `Mohon informasi terkait pendaftaran program akselerasi Tahfidz Al-Qur'an 30 Juz dan jadwal tes masuk.`
- **File KK / Akta / Rapor / Foto**: *(Gunakan file PDF / PNG contoh di komputer Anda, ukuran < 5MB)*

---

### 2. Form Registrasi Akun Baru (`/daftar`)
- **Nama Lengkap**: `Siti Nurhaliza`
- **Email**: `siti.nurhaliza@example.com`
- **Nomor HP / WA**: `085712345678`
- **Kata Sandi**: `Password123!`
- **Konfirmasi Kata Sandi**: `Password123!`

---

### 3. Form Login Akun (`/masuk`)
- **Email**: `user@pesantrends.id` *(Orang Tua)* atau `admin@pesantrends.id` *(Admin)*
- **Kata Sandi**: `password`

---

### 4. Form Edit Profil & Password (`/profil`)
- **Nama Lengkap**: `Bambang Sudirman, M.Pd.`
- **Email**: `bambang.parent@example.com`
- **Nomor Telepon**: `081234567890`
- **Nomor WhatsApp**: `081234567890`
- **Password Lama**: `password`
- **Password Baru**: `NewPassword2026!`
- **Konfirmasi Password Baru**: `NewPassword2026!`

---

### 5. Form Admin: Tambah Sekolah Baru (`/admin/schools/create`)
- **Nama Sekolah**: `Pondok Pesantren Daarut Tauhiid Bandung`
- **Tipe Sekolah**: `Pesantren Modern`
- **Kota/Kabupaten**: `Bandung`
- **Provinsi**: `Jawa Barat`
- **Alamat Lengkap**: `Jl. Gegerkalong Girang No. 38, Isola, Kec. Sukasari, Kota Bandung, Jawa Barat 40154`
- **Latitude**: `-6.8587120`
- **Longitude**: `107.5928340`
- **Nomor WhatsApp Resmi**: `081122334455`
- **Status Pendaftaran**: `Buka (Registration Open)`
- **Komponen Biaya**:
  - **Biaya Pendaftaran**: `Rps 250,000`
  - **Uang Pangkal / Masuk**: `Rp 15,000,000`
  - **SPP Bulanan**: `Rp 1,500,000`
  - **Biaya Asrama**: `Rp 800,000`
  - **Biaya Seragam**: `Rp 1,200,000`
  - **Biaya Kegiatan / Tahun**: `Rp 1,000,000`
- **Deskripsi Singkat**: `Pesantren modern berbasis manajemen tauhiid dan kewirausahaan di kota Bandung.`
- **Deskripsi Lengkap**: `Pondok Pesantren Daarut Tauhiid didirikan dengan komitmen membentuk karakter santri yang BAKU (Baik dan Kuat). Mengintegrasikan kurikulum nasional dengan pendidikan keislaman yang komprehensif.`

---

### 6. Form Admin: Tambah Artikel (`/admin/articles/create`)
- **Judul Artikel**: `Tips Memilih Pesantren Modern Berbasis Tahfidz untuk Anak`
- **Slug**: `tips-memilih-pesantren-modern-berbasis-tahfidz-untuk-anak`
- **Kategori**: `Edukasi & Tips`
- **Ringkasan (Excerpt)**: `Panduan lengkap bagi orang tua dalam menentukan sekolah Islam terpadu yang memiliki program tahfidz berkualitas.`
- **Isi Artikel**: `Memilih pesantren untuk buah hati merupakan keputusan besar. Ada beberapa indikator penting seperti rasio ustadz dan santri, kurikulum syariah, serta kenyamanan fasilitas asrama...`
- **Status**: `Published`

---

### 7. Form Admin: Tambah Fasilitas (`/admin/facilities/create`)
- **Nama Fasilitas**: `Asrama AC & Kamar Mandi Dalam`
- **Ikon**: `heroicon-o-home-modern`
- **Deskripsi**: `Asrama nyaman yang dilengkapi dengan pendingin ruangan, kasur perorangan, dan kamar mandi bersih.`

---

### 8. Form Admin: Tambah Program Unggulan (`/admin/programs/create`)
- **Nama Program**: `Tahfidz 30 Juz & Bahasa Arab Internasional`
- **Deskripsi**: `Program percepatan hafalan Al-Qur'an 30 juz selama 3 tahun disertai penguasaan percakapan bahasa Arab aktif.`

---

### 9. Form Admin: Tambah Testimoni (`/admin/testimonials/create`)
- **Nama Orang Tua**: `Dr. Hendra Wijaya`
- **Peran / Status**: `Wali Santri Kelas VIII`
- **Rating**: `5 Bintang`
- **Isi Ulasan**: `Sangat puas dengan sistem pembinaan di pesantren ini. Perkembangan hafalan dan kemandirian anak kami sangat pesat.`
- **Status Moduasi**: `Disetujui (Approved)`

---

### 10. Form Newsletter Footer
- **Email**: `orangtua.peduli@gmail.com`
