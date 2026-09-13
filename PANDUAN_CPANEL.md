# PANDUAN DEPLOYMENT KE SERVER CPANEL & DOMAIN KUSTOM
## Sistem Informasi Manajemen Relawan & Data Pendukung DPRD "GUS DIM"
**Dapil Kraksaan Raya (Kraksaan, Besuk, Gading - Probolinggo)**

---

## 📁 Struktur Direktori Proyek (`E:\Gus Dim\`)

Proyek ini telah dimodernisasi dan disiapkan dalam format standar yang **100% siap di-upload ke server cPanel**:

```text
E:\Gus Dim\
├── api\                      --> Backend REST API PHP 8
│   ├── auth.php              (Login, Sesi, Logout, Profil, Avatar)
│   ├── dashboard.php         (Statistik, Grafik, Titik Peta, Data Terbaru)
│   ├── pendukung.php         (Form Input DPC, DPRT, PIP, KIP, Relawan + Anti Duplikasi NIK)
│   ├── verifikasi.php        (Alur Verifikasi Bertingkat & Riwayat)
│   ├── users.php             (Manajemen Akun & Role Superadmin)
│   ├── logs.php              (Audit Trail Log Aktivitas)
│   └── export.php            (Ekspor Data Pendukung ke Excel / CSV)
├── assets\
│   ├── css\style.css         (Stylesheet responsif desktop & HP)
│   ├── js\app.js             (Logika antarmuka, Chart, Leaflet Map, OCR KTP)
│   ├── img\gus-dim.png       (Foto figur Gus Dim)
│   └── manifest.json         (PWA Manifest untuk install di HP)
├── config\
│   ├── config.php            (Konfigurasi Database MySQL & Upload)
│   ├── database.php          (Konektor PDO MySQL + Fallback Otomatis)
│   └── helpers.php           (Keamanan, Token, CORS, Helper Umur NIK)
├── database\
│   ├── schema.sql            (Struktur Tabel MySQL InnoDB)
│   └── seeds.sql             (Data Awal: Akun Default & Referensi Wilayah)
├── uploads\                  (Penyimpanan Foto Wajah & KTP - Dilindungi .htaccess)
│   ├── avatars\
│   ├── foto\
│   └── ktp\
├── legacy_gas_backup\        (Cadangan file prototipe Google Apps Script lama)
├── index.html                (Halaman Utama Aplikasi Web / PWA)
├── install.php               (Web Installer Otomatis Setup Database di Browser)
├── sw.js                     (Service Worker PWA Offline Shell)
├── .htaccess                 (Proteksi Keamanan, Gzip, & Cache cPanel)
└── PANDUAN_CPANEL.md         (Dokumen Panduan Ini)
```

---

## 🚀 Langkah 1: Upload File ke Server cPanel

1. **Kompres Folder Proyek**:
   * Masuk ke folder `E:\Gus Dim\`.
   * Pilih semua file dan folder (kecuali folder `legacy_gas_backup` jika tidak ingin diikutsertakan).
   * Klik kanan $\rightarrow$ **Compress to ZIP file** (beri nama misal `web-gusdim.zip`).
2. **Upload melalui cPanel File Manager**:
   * Login ke dashboard cPanel Anda (biasanya `https://domainanda.com:2083`).
   * Buka menu **File Manager**.
   * Masuk ke direktori root website Anda:
     * Jika domain utama: masuk ke folder `public_html/`.
     * Jika subdomain: masuk ke folder subdomain yang telah dibuat.
   * Klik tombol **Upload** di bagian atas, lalu pilih file `web-gusdim.zip`.
   * Setelah proses upload selesai (indikator warna hijau), kembali ke File Manager.
   * Klik kanan file `web-gusdim.zip` $\rightarrow$ pilih **Extract** $\rightarrow$ **Extract Files**.

---

## 🛠️ Langkah 2: Setup Database MySQL di cPanel

Anda dapat memilih salah satu dari dua cara berikut:

### OPSI A: Menggunakan Web Installer Otomatis (Sangat Direkomendasikan)
1. Buka browser dan akses URL installer website Anda:
   ```text
   https://domainanda.com/install.php
   ```
2. Anda cukup memasukkan:
   * **Host Database**: `localhost` (standar cPanel).
   * **Nama Database**: Nama database MySQL yang Anda buat di cPanel (misal: `usercpanel_gusdim`).
   * **Username Database**: Username MySQL cPanel (misal: `usercpanel_admin`).
   * **Password**: Password MySQL yang Anda buat.
3. Klik tombol **"Mulai Instalasi Otomatis"**.
4. Skrip akan otomatis membuat tabel, mengisi data awal, dan memperbarui file `config/config.php`.
5. **PENTING**: Setelah sukses, hapus file `install.php` melalui File Manager cPanel demi keamanan.

---

### OPSI B: Setup Manual melalui cPanel Wizard & phpMyAdmin
1. **Buat Database & User**:
   * Di cPanel, klik menu **MySQL Database Wizard**.
   * Langkah 1: Beri nama database (contoh: `u123_gusdim`) $\rightarrow$ *Next Step*.
   * Langkah 2: Buat username dan password (contoh user: `u123_admin`) $\rightarrow$ *Create User*.
   * Langkah 3: Centang kotak **ALL PRIVILEGES** $\rightarrow$ *Make Changes*.
2. **Impor Skema & Data Awal**:
   * Di cPanel, buka menu **phpMyAdmin**.
   * Pilih database yang baru saja dibuat di bilah kiri.
   * Klik tab **Import** di bagian atas.
   * Klik *Choose File*, pilih file `database/schema.sql` dari komputer Anda $\rightarrow$ klik tombol **Import** di bawah.
   * Ulangi langkah di atas untuk file `database/seeds.sql`.
3. **Konfigurasi `config/config.php`**:
   * Di File Manager cPanel, masuk ke folder `config/`.
   * Klik kanan file `config.php` $\rightarrow$ pilih **Edit**.
   * Sesuaikan baris berikut:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'u123_gusdim');  // Nama database cPanel Anda
     define('DB_USER', 'u123_admin');   // User database cPanel Anda
     define('DB_PASS', 'PasswordAnda'); // Password database Anda
     ```
   * Klik **Save Changes**.

---

## 🔑 Akun Default untuk Login Pertama Kali

| Role | Username | Password Default | Keterangan |
| :--- | :--- | :--- | :--- |
| **Superadmin** | `superadmin` | `admin123` | Akses penuh ke seluruh fitur & pengaturan pengguna |
| **Koordinator Kecamatan** | `korcam_kraksaan` | `password123` | Validasi tingkat kecamatan & finalisasi data |
| **Koordinator Desa** | `kordes_wetan` | `password123` | Verifikasi awal pendukung tingkat desa |
| **Admin Ranting** | `ranting_kraksaan` | `password123` | Petugas input data di lapangan |

> ⚠️ **Saran Keamanan**: Segera ganti password akun Superadmin setelah Anda berhasil login melalui menu **Pengaturan Pengguna** atau **Profil Saya**.

---

## 📱 Cara Memasang Aplikasi di HP Relawan (PWA)

Aplikasi ini sudah dilengkapi teknologi **Progressive Web App (PWA)**:
1. Minta relawan lapangan membuka link domain Anda di peramban Google Chrome (Android) atau Safari (iPhone).
2. Di Android: Ketuk menu titik tiga di kanan atas peramban $\rightarrow$ pilih **"Instal Aplikasi"** atau **"Tambahkan ke Layar Utama"** (*Add to Home Screen*).
3. Di iPhone: Ketuk tombol *Share* (kotak dengan panah ke atas) $\rightarrow$ geser ke bawah $\rightarrow$ pilih **"Add to Home Screen"**.
4. Ikon aplikasi **GUS DIM** akan muncul di layar menu HP seperti aplikasi asli dari Google Play Store / App Store!

---

## 🔒 Tips Keamanan & Pengoptimalan Tambahan

1. **Aktifkan SSL / HTTPS Gratis**:
   * Di cPanel, cari menu **SSL/TLS Status**.
   * Centang domain Anda dan klik tombol **Run AutoSSL**.
   * Pastikan URL website Anda selalu diakses menggunakan `https://`.
2. **Ekspor Data Berkala**:
   * Di menu Dashboard atau Riwayat, klik tombol hijau **"Ekspor Excel"** untuk mengunduh cadangan data konstituen dalam format `.csv` yang langsung rapi dibuka di Microsoft Excel.
3. **Versi PHP Rekomendasi**:
   * Di cPanel, buka menu **Select PHP Version** atau **MultiPHP Manager**.
   * Pastikan menggunakan **PHP 8.1, 8.2, 8.3, atau 8.4** dengan ekstensi aktif: `pdo_mysql`, `gd`, `mbstring`, `fileinfo`, `json`, `curl`.
