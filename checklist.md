# Panduan Pengujian Sistem Pesantrends (POV Pengguna & Administrator)

Dokumen ini berisi panduan dan daftar periksa pengujian (*User Acceptance Testing / UAT*) sistem **Pesantrends** yang ditulis dari sudut pandang **Orang Tua / Calon Santri** dan **Administrator Sekolah**, tanpa istilah teknis pemrogram (*non-developer*).

---

## 🔑 Akun Pengujian

| Peran (POV) | Email | Kata Sandi | Halaman Utama |
| :--- | :--- | :--- | :--- |
| **Administrator Utama** | `admin@pesantrends.id` | `password` | Dashboard Admin (`/admin`) |
| **Administrator Sekolah** | `admin@gmail.com` | `admin123` | Dashboard Admin (`/admin`) |
| **Orang Tua / Wali Santri** | `user@pesantrends.id` | `password` | Dashboard Wali Santri (`/dashboard`) |
| **Orang Tua (Akun Uji Coba)** | `bambang.parent@example.com` | `password` | Dashboard Wali Santri (`/dashboard`) |

---

## 👤 Bagian 1: Pengujian Sudut Pandang Orang Tua / Calon Santri

### A. Penjelajahan & Pencarian Sekolah (Flow 1)

- [ ] **1. Tampilan Ponsel & Navigasi Bawah (Mobile Bottom Nav)**
  - Buka situs dari HP atau ubah tampilan browser ke ukuran layar smartphone.
  - Pastikan menu navigasi bagian bawah tetap melayang di layar: **Beranda**, **Cari**, **Simpan**, **Pendaftaran Saya**, dan **Akun**.
  - Pastikan menu bawah ini otomatis tersembunyi jika membuka dari komputer/laptop.

- [ ] **2. Pencarian & Urutkan Sekolah Terdekat (GPS)**
  - Buka halaman katalog **Cari Sekolah**.
  - Klik tombol **"Gunakan Lokasi Saya"** → Izinkan akses lokasi browser.
  - Pastikan daftar sekolah otomatis terurut berdasarkan jarak terdekat dari posisi Anda, lengkap dengan label jarak (misal: *2.4 km dari lokasi Anda*).
  - Coba filter pencarian: Tipe Pesantren (Salaf/Modern), Provinsi, dan Rentang Biaya SPP.

- [ ] **3. Peta Interaktif Pesantren (OpenStreetMap)**
  - Klik tombol **Tampilan Peta** di halaman pencarian.
  - Pastikan peta interaktif muncul dengan penanda (*pin*) lokasi tiap pesantren.
  - Klik salah satu *pin* pesantren → Pastikan muncul pop-up informasi singkat (nama sekolah, kota, SPP, rating) beserta tombol menuju detail sekolah.

- [ ] **4. Halaman Detail Sekolah & Tombol Aksi Utama**
  - Buka salah satu halaman detail sekolah.
  - Pastikan terdapat **Dua Tombol Aksi Utama**:
    - 🟩 **Ajukan Pendaftaran** (Warna Hijau Utama) → Membuka formulir pendaftaran santri.
    - 💬 **Chat via WhatsApp** (Warna Garis) → Langsung membuka aplikasi WhatsApp untuk bertanya ke pihak sekolah.
  - Coba fitur **Simpan Sekolah** → Pastikan sekolah masuk ke daftar favorit Anda.

- [ ] **5. Kalkulator Perkiraan Biaya Sekolah**
  - Buka fitur **Kalkulator Biaya**.
  - Pilih komponen biaya yang ingin dihitung (uang pangkal, SPP bulanan, biaya asrama, seragam, kegiatan, dll.).
  - Pastikan sistem menampilkan rincian dan total perkiraan biaya dengan akurat.

---

### B. Formulir Pendaftaran Online (Flow 2)

- [ ] **1. Mengisi Formulir Pendaftaran 5-Langkah (`/orang-tua/daftar`)**
  - Klik tombol **Ajukan Pendaftaran** di sekolah pilihan Anda.
  - **Langkah 1 (Pilih Sekolah)**: Pastikan nama sekolah dan ringkasan SPP muncul.
  - **Langkah 2 (Biodata Siswa)**: Isi Nama Lengkap Siswa, NIK, Jenis Kelamin, Tempat & Tanggal Lahir, Alamat, dan Sekolah Asal.
  - **Langkah 3 (Biodata Orang Tua)**: Isi Nama Wali, Email, No. HP, dan No. WhatsApp.
  - **Langkah 4 (Upload Dokumen)**:
    - Unggah berkas pendukung: Kartu Keluarga (KK), Akta Kelahiran, Rapor/Ijazah, dan Pasfoto.
    - Isi catatan tambahan jika ada.
  - **Langkah 5 (Tinjau Rincian & Kirim)**:
    - Periksa rincian biaya pendaftaran (`Biaya Pendaftaran + Uang Pangkal + SPP Bulan Pertama`).
    - Klik tombol **Kirim & Bayar**.

- [ ] **2. Mode Fitur Isi Otomatis (Demo Test Mode)**
  - Pada formulir pendaftaran, gunakan tombol **⚡ Mode Pengujian: Isi Ulang Data Contoh** untuk mengisi seluruh data tes secara instan tanpa mengetik manual.

---

### C. Pembayaran & Cetak Bukti Resmi

- [ ] **1. Pembayaran Online Gateway (Xendit)**
  - Setelah pendaftaran dikirim, Anda akan diarahkan ke halaman **Pembayaran Pendaftaran**.
  - Klik tombol **Bayar via Xendit Gateway** → Pastikan halaman checkout resmi pembayaran online Xendit terbuka.

- [ ] **2. Mode Simulasi Pembayaran Instan (Pengujian)**
  - Di halaman pembayaran, klik tombol **⚡ Simulasi Pembayaran Lunas (Instan)**.
  - Pastikan status pendaftaran Anda seketika berubah menjadi **TERBAYAR / LUNAS**.

- [ ] **3. Cetak Bukti Pembayaran Resmi (PDF / A4)**
  - Pada halaman detail pendaftaran yang sudah lunas, klik tombol **Cetak Bukti Pembayaran**.
  - Pastikan dokumen Bukti Pembayaran A4 terbuka rapi dengan rincian biaya lengkap, data siswa, dan stempel resmi **TERBAYAR**.

- [ ] **4. Pantau Status di Dashboard Orang Tua**
  - Buka halaman **Dashboard** atau **Pendaftaran Saya**.
  - Pastikan seluruh riwayat pendaftaran Anda tampil lengkap dengan status terbarunya (*Diajukan*, *Terverifikasi*, *Terbayar*).

---

## 🛡️ Bagian 2: Pengujian Sudut Pandang Administrator Sekolah

### A. Kelola Data Katalog & Konten Admin (`/admin`)

- [ ] **1. Masuk ke Dashboard Admin**
  - Buka alamat `/admin` dan masuk menggunakan akun administrator (`admin@pesantrends.id`) dan password ('password').
  - Pastikan halaman Dashboard Admin menampilkan ringkasan statistik (Total Pendaftaran, Pendaftaran Lunas, Pendapatan Bulan Ini).

- [ ] **2. Kelola Data Sekolah (Katalog Pesantren)**
  - Masuk ke menu **Sekolah**.
  - Tambah atau ubah data sekolah: Nama, Koordinat Peta GPS (Latitude & Longitude), Nomor WhatsApp Resmi, 6 Komponen Biaya Sekolah, Galeri Foto, dan Status Pendaftaran (Dibuka/Ditutup).

- [ ] **3. Impor Data Sekolah Massal (CSV)**
  - Masuk ke menu **Impor Sekolah**.
  - Unggah file data sekolah berformat CSV → Tinjau pratinjau data → Klik simpan untuk memasukkan banyak sekolah sekaligus.

- [ ] **4. Kelola Berita, Fasilitas, & Testimoni**
  - Masuk ke menu **Artikel**: Buat & publikasikan artikel berita/edukasi pesantren.
  - Masuk ke menu **Fasilitas & Program**: Kelola daftar fasilitas dan program unggulan pesantren.
  - Masuk ke menu **Testimoni**: Menyetujui atau menambahkan ulasan orang tua santri.

---

### B. Verifikasi Pendaftaran & Pembayaran Santri Baru

- [ ] **1. Memeriksa Pendaftaran Masuk**
  - Masuk ke menu **Pendaftaran** di Panel Admin.
  - Filter daftar pendaftaran berdasarkan status (*Diajukan*, *Menunggu Pembayaran*, *Terbayar*).
  - Buka salah satu pendaftaran → Periksa biodata santri dan pratinjau berkas dokumen (KK/Akta) yang diunggah.

- [ ] **2. Akses Verifikasi & Penolakan Berkas**
  - Klik tab **Verifikasi**:
    - Pilih **Setujui / Verifikasi** untuk melanjutkan pendaftaran ke tahap pembayaran.
    - Pilih **Tolak Pendaftaran** dan tuliskan alasan penolakan jika berkas tidak sesuai.

- [ ] **3. Catat Pembayaran Manual (Tunai / Transfer Direct)**
  - Jika orang tua santri membayar tunai langsung di kantor sekolah, klik tombol **Catat Pembayaran Manual**.
  - Sistem akan otomatis menerbitkan resi lunas tanpa melalui payment gateway online.

- [ ] **4. Cetak Bukti Pembayaran dari Admin**
  - Admin dapat mencetak ulang Bukti Pembayaran Resmi kapan saja untuk arsip fisik sekolah atau diserahkan kembali kepada orang tua santri.
