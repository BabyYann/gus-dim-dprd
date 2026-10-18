# LAPORAN AUDIT SISTEM: REGISTER TEMUAN LENGKAP (FINDINGS REGISTER)
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. REKAPITULASI SELURUH TEMUAN AUDIT

Berikut adalah daftar lengkap 32 temuan teknis yang diidentifikasi selama audit menyeluruh:

| No | ID Temuan | Judul Temuan | Tingkat Keparahan | Kategori | Komponen Terdampak |
| :---: | :--- | :--- | :---: | :--- | :--- |
| 1 | AUD-SEC-001 | Unauthenticated Web Installer di Web Root | **CRITICAL** | A01: Broken Access Control | `install.php` |
| 2 | AUD-SEC-002 | Universal Authentication Backdoor di Kode Produksi | **CRITICAL** | A04: Insecure Design | `api/auth.php:51-55` |
| 3 | AUD-SEC-003 | Public Unauthenticated PII Data Export | **CRITICAL** | A01: Broken Access Control | `routes/api.php:134`, `ExportController.php` |
| 4 | AUD-SEC-004 | Hardcoded Google Gemini API Key di Client & Config | **HIGH** | A02: Cryptographic Failures | `config/config.php`, `app.js`, `mobile/index.html` |
| 5 | AUD-SEC-005 | Hardcoded Maintenance Mode Bypass Backdoor | **HIGH** | A04: Insecure Design | `web.php:9,34`, `api.php:21`, `helpers.php:18` |
| 6 | AUD-SEC-006 | Unprotected Route Adapter Memicu 500 Fatal Error | **HIGH** | A01: Broken Access Control | `routes/api.php:60-72`, `AuthController.php:106` |
| 7 | AUD-SEC-007 | Injeksi Status Verifikasi Mandiri Saat Input Data | **HIGH** | A01: Broken Access Control | `api/pendukung.php:174-176` |
| 8 | AUD-SEC-008 | Ketiadaan Otorisasi Teritorial pada Verifikasi Laravel | **HIGH** | A01: Broken Access Control | `backend/.../VerifikasiController.php:76-114` |
| 9 | AUD-SEC-009 | Penonaktifan Validasi Sertifikat SSL pada OCR AI | **HIGH** | A02: Cryptographic Failures | `api/ocr.php:131-132` |
| 10 | AUD-SEC-010 | Stored Cross-Site Scripting (XSS) pada Klien Desktop | **HIGH** | A03: Injection | `assets/js/app.js:349, 1866, 2427` |
| 11 | AUD-SEC-011 | Ketiadaan Brute-Force Rate Limiting pada Login API | **HIGH** | A07: Auth Failures | `api/auth.php` |
| 12 | AUD-ARCH-001 | Desinkronisasi Kolom Password Antara Laravel dan PHP | **HIGH** | Arsitektur & Integritas | `users.password` vs `users.password_hash` |
| 13 | AUD-ARCH-002 | Ketiadaan Modul Aspirasi dan Reses di Backend Laravel | **HIGH** | Arsitektur & Paritas | `backend/routes/api.php`, `backend/app/Models` |
| 14 | AUD-BIZ-001 | Auto-Enroll Reses Melompati Status Verifikasi Faktual | **MEDIUM** | Alur Bisnis | `api/reses.php:265-286` |
| 15 | AUD-BIZ-002 | Cascade Delete Akun Operator Saat Menghapus Warga | **MEDIUM** | Alur Bisnis | `api/pendukung.php:636-638` |
| 16 | AUD-BIZ-003 | Fail-Open Territorial Guard pada Scope Penugasan Kosong | **MEDIUM** | Otorisasi & Logika Bisnis| `api/verifikasi.php:180-197` |
| 17 | AUD-DATA-001 | Ketiadaan UNIQUE Constraint pada NIK (Race Condition) | **MEDIUM** | Integritas Basis Data | `database/schema.sql:63`, Migrations |
| 18 | AUD-DATA-002 | Ketiadaan Foreign Key Constraints pada Tabel Inti | **MEDIUM** | Integritas Basis Data | `pendukung`, `audit_logs`, `reses_kehadiran` |
| 19 | AUD-DATA-003 | Race Condition & Truncation Risk pada Fallback JSON | **MEDIUM** | Integritas Basis Data | `config/database.php:171-174` |
| 20 | AUD-DATA-004 | Eksekusi DDL Runtime pada Setiap Permintaan HTTP | **MEDIUM** | Kinerja Basis Data | `config/database.php:35-117` |
| 21 | AUD-SEC-012 | Enumerasi Daftar Petugas dan Wilayah Tanpa Otentikasi | **MEDIUM** | A01: Broken Access Control | `api/users.php:20-26` |
| 22 | AUD-SEC-013 | Pengembalian Kata Sandi Baru dalam Plaintext JSON | **MEDIUM** | A02: Cryptographic Failures | `api/users.php:310, 395` |
| 23 | AUD-SEC-014 | Ketiadaan Otentikasi pada Endpoint AI OCR e-KTP | **MEDIUM** | A01: Broken Access Control | `api/ocr.php:4-245` |
| 24 | AUD-SEC-015 | Pembacaan Audit Logs Tanpa Otentikasi di PHP | **MEDIUM** | A09: Logging & Monitoring | `api/logs.php:4-28` |
| 25 | AUD-PERF-001 | Risiko Out-of-Memory (OOM) via Payload allRecords | **MEDIUM** | Performa & Skalabilitas | `api/dashboard.php:127`, `DashboardController` |
| 26 | AUD-DEP-001 | Penggunaan Izin Berkas Berbahaya 777 pada Deployment | **MEDIUM** | Konfigurasi Deployment | `cpanel_quick_deploy.sh:71` |
| 27 | AUD-DEP-002 | Eksposur Password Database di Process Table Server | **MEDIUM** | Konfigurasi Deployment | `cpanel_clean_database.sh:50` |
| 28 | AUD-DEP-003 | Bypass Pemeriksaan Integritas Platform Composer | **MEDIUM** | Keandalan Deployment | `cpanel_quick_deploy.sh:41-44` |
| 29 | AUD-DEP-004 | Penumpukan Berkas Binary Vendor di Git (vendor.zip) | **LOW** | Kebersihan Repositori | `backend/vendor.zip` (36 MB) |
| 30 | AUD-CODE-001 | Berkas Sisa Pengujian dan Skrip Mati di Root | **LOW** | Kualitas Kode | `test_js.js`, `update_ui.php`, `test.txt` |
| 31 | AUD-PWA-001 | Service Worker Root Mem-Bypass Cache Total | **LOW** | PWA & Offline Support | `sw.js:15-18` |
| 32 | AUD-TEST-001 | Ketiadaan Automated Test Suite pada Logika Bisnis | **INFO** | Quality Assurance | `backend/tests/` (Coverage 0.00%) |

---

### 2. RINCIAN SPESIFIKASI TEMUAN KRITIS & TINGGI

#### AUD-SEC-001: Unauthenticated Web Installer di Web Root
- **Tingkat Keparahan**: CRITICAL (CVSS: 9.8)
- **Kategori**: OWASP A01:2021 — Broken Access Control | CWE-306
- **Komponen Terdampak**: `install.php` (baris 1-57)
- **Deskripsi**: File `install.php` dapat diakses langsung oleh siapa saja tanpa memerlukan kredensial login atau token instalasi sekali pakai. Script ini menerima parameter POST `db_host`, `db_name`, `db_user`, dan `db_pass`, lalu mengeksekusi `CREATE DATABASE`, menimpa skema tabel, dan menginjeksi akun superadmin default.
- **Skenario Risiko**: Penyerang mengirim permintaan HTTP POST ke `https://target.com/install.php` dengan host database miliknya sendiri atau mereset database berjalan menjadi kosong.
- **Rekomendasi Remediasi**:
  1. Hapus berkas `install.php` dari server produksi secara permanen.
  2. Jika dibutuhkan untuk deployment, terapkan pemeriksaan token env `INSTALL_TOKEN` atau kunci berkas otomatis (`install.lock`) yang menolak eksekusi ulang.
- **Estimasi Upaya**: Rendah (< 30 menit).

---

#### AUD-SEC-002: Universal Authentication Backdoor di Kode Produksi
- **Tingkat Keparahan**: CRITICAL (CVSS: 9.8)
- **Kategori**: OWASP A04:2021 — Insecure Design | CWE-259
- **Komponen Terdampak**: `api/auth.php` (baris 51-55)
- **Deskripsi**: Kode otentikasi memuat pengecualian eksplisit yang mengizinkan login ke akun Superadmin dan akun pengguna lain menggunakan kumpulan kata sandi default hardcoded:
  ```php
  } elseif ($user['role'] === 'Superadmin' && in_array($password, ['admin', 'admin123', 'gusdim', 'gusdim123', 'password123', '123456', 'superadmin'])) {
      $isValidPass = true;
  } elseif (in_array($password, ['password123', 'admin123', '123456', 'admin'])) {
      $isValidPass = true;
  }
  ```
- **Skenario Risiko**: Siapa pun dapat login sebagai `superadmin` dengan memasukkan kata sandi `admin123` terlepas dari nilai hash sandi yang sah di database.
- **Rekomendasi Remediasi**:
  Hapus baris 51 hingga 55 sepenuhnya. Verifikasi kata sandi hanya boleh dilakukan melalui fungsi bawaan `password_verify($password, $hash)` atau `Hash::check()`.
- **Estimasi Upaya**: Rendah (< 15 menit).

---

#### AUD-SEC-003: Public Unauthenticated PII Data Export
- **Tingkat Keparahan**: CRITICAL (CVSS: 9.1)
- **Kategori**: OWASP A01:2021 — Broken Access Control | CWE-306 | Pelanggaran UU PDP
- **Komponen Terdampak**: `backend/routes/api.php` (baris 134) dan `backend/app/Http/Controllers/Api/ExportController.php` (baris 12-68)
- **Deskripsi**: Rute `/api/export-public` didaftarkan di luar grup middleware `auth:sanctum`. Method `export()` pada `ExportController` tidak memeriksa sesi atau token request, langsung mengekstrak seluruh data tabel `pendukung` ke berkas CSV.
- **Skenario Risiko**: Penyerang membuka URL `https://target.com/api/export-public` dan langsung mengunduh ribuan data identitas warga (NIK 16 digit, Nomor HP, Nama Lengkap, dan Alamat Domisili).
- **Rekomendasi Remediasi**:
  Hapus rute `/export-public`. Pindahkan rute ekspor hanya ke dalam grup `auth:sanctum` dan tambahkan validasi wewenang peran (hanya Superadmin atau Koordinator Kecamatan dengan pembatasan filter wilayah).
- **Estimasi Upaya**: Rendah (< 30 menit).

---

#### AUD-SEC-004: Hardcoded Google Gemini API Key di Client & Config
- **Tingkat Keparahan**: HIGH (CVSS: 7.5)
- **Kategori**: OWASP A02:2021 — Cryptographic Failures | CWE-798
- **Komponen Terdampak**: `config/config.php` (baris 71), `assets/js/app.js` (baris 6), `mobile/index.html` (baris 8504)
- **Deskripsi**: Google Gemini API Key dikodekan secara statis menggunakan base64 `QVEuQWI4...` pada skrip JavaScript yang diunduh langsung oleh browser pengguna.
- **Skenario Risiko**: Pihak luar mengekstrak API key dari kode sumber JavaScript melalui browser inspect element dan menggunakannya untuk proyek pribadi atau menghabiskan kuota API organisasi.
- **Rekomendasi Remediasi**:
  Hapus fungsi pemanggilan langsung Gemini API dari sisi klien (`callGeminiDirectCloud`). Seluruh request pemindaian e-KTP wajib melalui proxy backend (`api/ocr.php`), dengan API Key tersimpan aman pada berkas `.env` server.
- **Estimasi Upaya**: Sedang (2 - 4 jam).

---

#### AUD-SEC-005: Hardcoded Maintenance Mode Bypass Backdoor
- **Tingkat Keparahan**: HIGH (CVSS: 7.3)
- **Kategori**: OWASP A04:2021 — Insecure Design | CWE-798
- **Komponen Terdampak**: `backend/routes/web.php` (baris 9, 34), `backend/routes/api.php` (baris 21), `config/helpers.php` (baris 18)
- **Deskripsi**: Nilai string `gusdim2025` digunakan secara statis sebagai kunci untuk melewati pemeriksaan mode pemeliharaan (maintenance mode).
- **Skenario Risiko**: Pihak luar yang membaca repositori publik atau menebak string ini dapat mengakses sistem saat admin sedang melakukan migrasi data darurat atau perbaikan sistem.
- **Rekomendasi Remediasi**:
  Gunakan mekanisme bypass bawaan Laravel (`php artisan down --secret="random-token"`) atau periksa session login Superadmin secara dinamis.
- **Estimasi Upaya**: Rendah (1 - 2 jam).

---

#### AUD-ARCH-001: Desinkronisasi Kolom Password Antara Laravel dan PHP
- **Tingkat Keparahan**: HIGH (CVSS: 7.4)
- **Kategori**: Arsitektur & Integritas Basis Data | CWE-1068
- **Komponen Terdampak**: Tabel `users`, `api/auth.php`, `backend/app/Models/User.php`
- **Deskripsi**: Sistem Laravel menggunakan kolom `password`, sedangkan PHP prosedural menggunakan kolom `password_hash`. Penambahan kolom dinamis di `Database::ensureTables()` tidak menyinkronkan data antar-kolom saat terjadi update profil atau reset kata sandi.
- **Skenario Risiko**: Pengguna yang memperbarui kata sandinya di satu antarmuka akan gagal login saat mengakses antarmuka yang dilayani backend lainnya.
- **Rekomendasi Remediasi**:
  Standardisasi nama kolom menjadi `password`. Perbarui query di `api/auth.php` dan `api/users.php` agar membaca dan menulis ke kolom `password`.
- **Estimasi Upaya**: Sedang (2 - 3 jam).

---

#### AUD-SEC-010: Stored Cross-Site Scripting (XSS) pada Klien Desktop
- **Tingkat Keparahan**: HIGH (CVSS: 7.6)
- **Kategori**: OWASP A03:2021 — Injection | CWE-79
- **Komponen Terdampak**: `assets/js/app.js` (baris 349-356, 1866, 2427)
- **Deskripsi**: Data konstituen, aspirasi warga, dan log aktivitas dimasukkan ke dalam elemen DOM menggunakan penugasan `innerHTML` tanpa proses escaping karakter HTML khusus (`<`, `>`, `&`, `"`).
- **Skenario Risiko**: Relawan memasukkan payload skrip pada nama atau alamat pendukung. Saat Superadmin membuka halaman tabel, skrip mengeksekusi payload jahat dan mencuri token sesi dari `localStorage`.
- **Rekomendasi Remediasi**:
  Terapkan fungsi `escapeHtml()` pada seluruh string dinamis sebelum dikonkat ke `innerHTML`, atau gunakan properti `textContent` untuk mengisi nilai teks.
- **Estimasi Upaya**: Sedang (3 - 5 jam).
