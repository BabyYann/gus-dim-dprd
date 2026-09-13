# PANDUAN LENGKAP DEPLOYMENT LARAVEL KE SERVER CPANEL
## Sistem Informasi Manajemen Relawan & Data Pendukung DPRD "GUS DIM"
**Teknologi: Laravel 11/12 (Sanctum API) + MySQL + PWA Mobile**

---

## 🏗️ Struktur Proyek Laravel (`E:\Gus Dim\backend\`)

Proyek Laravel kini telah siap 100% di folder `E:\Gus Dim\backend\`:

```text
E:\Gus Dim\backend\
├── app\
│   ├── Http\Controllers\Api\
│   │   ├── AuthController.php        (Login, Cek Sesi, Profil, Avatar)
│   │   ├── DashboardController.php   (Statistik, Grafik, Titik Peta, Data Terbaru)
│   │   ├── PendukungController.php   (Multi-Jalur, Anti-Duplikasi NIK, Upload Foto)
│   │   ├── VerifikasiController.php  (Alur Approval Bertingkat Desa -> Kecamatan -> Final)
│   │   ├── UserController.php        (Manajemen Akun Tim Superadmin)
│   │   ├── AuditLogController.php    (Riwayat Aktivitas)
│   │   └── ExportController.php      (Download Rekap CSV Excel)
│   └── Models\
│       ├── User.php                  (Eloquent User & Sanctum API Tokens)
│       ├── Pendukung.php             (Eloquent Konstituen & Label Jalur)
│       ├── AuditLog.php              (Eloquent Catatan Audit)
│       └── WilayahReferensi.php      (Eloquent Master Desa & Kecamatan)
├── database\
│   ├── migrations\                   (Struktur Tabel Terstandar Laravel)
│   └── seeders\DatabaseSeeder.php    (Data Awal Akun & Wilayah Dapil)
├── routes\
│   └── api.php                       (24 Endpoint RESTful API)
├── public\                           (Pintu Masuk Web cPanel / Web Root)
│   ├── index.html                    (Aplikasi PWA Antarmuka Utama)
│   ├── assets\                       (CSS, JS, Icon, Gambar Gus Dim)
│   ├── sw.js                         (Service Worker PWA Offline Shell)
│   ├── index.php                     (Router Laravel)
│   └── .htaccess                     (Konfigurasi Apache URL Rewriting)
└── .env                              (Konfigurasi Database & Kunci Aplikasi)
```

---

## 🚀 Cara Menjalankan di Komputer Lokal (Development)

Untuk mencoba aplikasi di komputer Anda sebelum di-upload ke cPanel:
1. Buka PowerShell / Terminal di folder:
   ```bash
   cd "E:\Gus Dim\backend"
   ```
2. Jalankan perintah server bawaan Laravel:
   ```bash
   php artisan serve
   ```
3. Buka browser Anda dan kunjungi:
   ```text
   http://127.0.0.1:8000/
   ```
   Aplikasi langsung terbuka dengan antarmuka PWA dan siap digunakan!

---

## 🌐 Cara Deploy ke Hosting cPanel (Standar Profesional Paling Aman)

Di cPanel, standar keamanan terbaik untuk Laravel adalah **memisahkan folder inti framework dari folder publik (*public_html*)**. Caranya sangat mudah:

### Langkah 1: Upload File ke cPanel
1. Masuk ke **File Manager** cPanel hosting Anda.
2. Di luar folder `public_html` (pada root akun cPanel Anda, misalnya di `/home/username/`), buat folder baru bernama:
   `gusdim_core`
3. Upload seluruh isi folder `E:\Gus Dim\backend\` (kecuali folder `public`) ke dalam folder `gusdim_core` tersebut.
4. Masuk ke folder `public_html/`.
5. Upload seluruh isi dari folder `E:\Gus Dim\backend\public\` (yaitu: `index.html`, `index.php`, `sw.js`, `.htaccess`, dan folder `assets`) langsung ke dalam `public_html/`.

### Langkah 2: Sesuaikan Jalur di `public_html/index.php`
Buka file `index.php` di dalam `public_html/` lewat tombol **Edit** File Manager cPanel, sesuaikan 2 baris jalur agar mengarah ke folder `gusdim_core`:
```php
require __DIR__.'/../gusdim_core/vendor/autoload.php';

$app = require_once __DIR__.'/../gusdim_core/bootstrap/app.php';
```
*(Dengan cara ini, file `.env` dan kode inti Anda 100% aman dan tidak bisa diintip atau didownload oleh orang luar dari browser!)*

---

### Langkah 3: Setup Database MySQL di cPanel

1. **Buat Database & User**:
   * Di cPanel, buka **MySQL Database Wizard**.
   * Buat database baru (misal: `cpaneluser_gusdim`) dan user baru (misal: `cpaneluser_admin`).
   * Berikan centang **ALL PRIVILEGES**.
2. **Impor Tabel & Data Awal (Sangat Cepat)**:
   * Buka **phpMyAdmin** di cPanel.
   * Pilih database Anda di sebelah kiri.
   * Klik tab **Import** $\rightarrow$ pilih file:
     `E:\Gus Dim\database\laravel_schema_and_seeds.sql`
   * Klik tombol **Import** di bawah. Selesai! Semua tabel dan data awal langsung terisi.
3. **Atur File `.env`**:
   * Buka file `.env` di folder `gusdim_core/` lewat File Manager cPanel.
   * Isi bagian database sesuai data cPanel Anda:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=cpaneluser_gusdim
     DB_USERNAME=cpaneluser_admin
     DB_PASSWORD=password_database_anda
     ```
   * Simpan perubahan.

---

## 🔑 Akun Default untuk Login

| Role | Username | Password Default | Keterangan |
| :--- | :--- | :--- | :--- |
| **Superadmin** | `superadmin` | `admin123` | Akses penuh seluruh modul & kontrol user |
| **Koordinator Kecamatan** | `korcam_kraksaan` | `password123` | Validasi tingkat kecamatan & status Final |
| **Koordinator Desa** | `kordes_wetan` | `password123` | Verifikasi awal pendukung tingkat desa |
| **Admin Ranting** | `ranting_kraksaan` | `password123` | Input data warga & scan KTP di lapangan |

---

## 📱 Memasang Aplikasi di HP Relawan (PWA)

1. Buka browser Chrome (Android) atau Safari (iPhone).
2. Kunjungi alamat domain Anda: `https://domainanda.com`.
3. Klik menu titik tiga di browser $\rightarrow$ pilih **"Instal Aplikasi"** atau **"Tambahkan ke Layar Utama"**.
4. Ikon **GUS DIM** akan langsung muncul di beranda HP relawan, berfungsi cepat dan praktis untuk pendataan di lapangan!
