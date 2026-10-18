# LAPORAN AUDIT SISTEM: REMEDIATION ROADMAP
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. STRATEGI & PRINSIP DASAR REMEDIASI

Pelaksanaan perbaikan teknis sistem informasi Gus Dim harus mengikuti metodologi pertahanan berlapis (Defense-in-Depth) yang terbagi ke dalam empat fase terstruktur. Pendekatan bertahap ini memprioritaskan penutupan celah keamanan dengan dampak tertinggi (eksploitasi remote tanpa otentikasi) sebelum melangkah ke perbaikan arsitektural dan optimasi jangka panjang.

```text
FASE 1: Hotfix Darurat (< 24 Jam)
  ├── Hapus / Amankan install.php
  ├── Hapus Universal Backdoor Login (api/auth.php:51-55)
  ├── Tutup Rute Ekspor Publik (/api/export-public)
  ├── Cabut & Ganti Gemini API Key (Pindahkan ke .env)
  └── Hapus Kunci Hardcoded Maintenance Mode (gusdim2025)
         │
         ▼
FASE 2: Penguatan Keamanan & Otorisasi (< 7 Hari)
  ├── Perbaiki Stored XSS pada assets/js/app.js via escapeHtml()
  ├── Tutup Injeksi Status Draf pada api/pendukung.php
  ├── Terapkan Otorisasi Teritorial Ketat di PHP & Laravel
  ├── Wajibkan Otentikasi pada OCR, Logs, dan Users List
  ├── Aktifkan Validasi SSL cURL pada api/ocr.php
  └── Perbaiki Skrip Deployment (Hapus chmod 777 & ekspos pass)
         │
         ▼
FASE 3: Unifikasi Dual-Stack & Integritas Data (< 14 Hari)
  ├── Unifikasi ke Satu Backend Utama (Single Source of Truth)
  ├── Standardisasi Kolom Password (users.password)
  ├── Tambahkan Database UNIQUE Index pada pendukung.nik
  ├── Hapus Eksekusi DDL Runtime di Database::init()
  └── Terapkan Paginasi Server-Side pada Dashboard & List
         │
         ▼
FASE 4: Pengujian, Kualitas Kode & Hardening Produksi (< 30 Hari)
  ├── Implementasikan Automated Test Suite (PHPUnit / Pest)
  ├── Bersihkan Berkas Binary (vendor.zip) & Berkas Sampah Root
  └── Konfigurasi Monitoring & Audit Trail Alerting
```

---

### 2. RENCANA AKSI PER FASE

#### FASE 1: HOTFIX KEAMANAN DARURAT (TARGET: SELESAI < 24 JAM)

1. **Remediasi AUD-SEC-001 (Web Installer Terbuka)**:
   - Tindakan: Hapus berkas `install.php` dari web root produksi. Buat berkas kosong `install.php` yang mengembalikan HTTP 404 jika diakses publik.
   - Kriteria Penerimaan: Permintaan HTTP ke `/install.php` menghasilkan status 404 Not Found.

2. **Remediasi AUD-SEC-002 (Universal Authentication Backdoor)**:
   - Tindakan: Buka berkas `api/auth.php`. Hapus baris 51-55 yang memuat daftar kata sandi hardcoded `['admin', 'admin123', 'gusdim', ...]`.
   - Kriteria Penerimaan: Login akun Superadmin dengan password salah menghasilkan respons JSON `Username atau password salah` (HTTP 200/401).

3. **Remediasi AUD-SEC-003 (Public Unauthenticated Data Export)**:
   - Tindakan: Buka `backend/routes/api.php`, hapus baris 134:
     `Route::get('/export-public', [ExportController::class, 'export']);`
   - Kriteria Penerimaan: Mengakses `/api/export-public` mengembalikan 404 Not Found.

4. **Remediasi AUD-SEC-004 (Eksposur Google Gemini API Key)**:
   - Tindakan:
     a. Lakukan rotasi (revoke and regenerate) Google Gemini API Key pada Google AI Studio.
     b. Simpan kunci baru HANYA di berkas `backend/.env` dan `config/config.php` (server-side).
     c. Hapus fungsi `callGeminiDirectCloud()` dari `assets/js/app.js` dan `mobile/index.html`. Arahkan seluruh proses OCR ke backend `api/ocr.php`.
   - Kriteria Penerimaan: Tidak ada string kunci API Google yang dapat ditemukan pada pencarian teks berkas JavaScript atau HTML publik.

5. **Remediasi AUD-SEC-005 (Bypass Mode Pemeliharaan)**:
   - Tindakan: Hapus nilai hardcoded `gusdim2025` pada `backend/routes/web.php`, `backend/routes/api.php`, dan `config/helpers.php`. Gunakan token dinamis yang disimpan di `.env` (misal `MAINTENANCE_BYPASS_TOKEN`).
   - Kriteria Penerimaan: Menyertakan query `?bypass=gusdim2025` tidak lagi membuka proteksi maintenance mode.

---

#### FASE 2: PENGUATAN KEAMANAN & OTORISASI (TARGET: SELESAI < 7 HARI)

1. **Remediasi AUD-SEC-010 (Stored XSS Desktop)**:
   - Tindakan: Tambahkan fungsi utilitas `escapeHtml(text)` ke dalam `assets/js/app.js`. Bungkus seluruh variabel teks (nama warga, alamat, catatan, judul aspirasi) dengan `escapeHtml()` sebelum dimasukkan ke dalam template string `innerHTML`.
   - Kriteria Penerimaan: Memasukkan string `<script>alert(1)</script>` pada nama pendukung dirender secara aman sebagai teks literal `&lt;script&gt;...` tanpa eksekusi skrip di browser.

2. **Remediasi AUD-SEC-007 (Injeksi Status Draf Pendukung)**:
   - Tindakan: Pada `api/pendukung.php` (baris 174-176), kunci status penyimpanan data baru selalu bernilai `'Diinput'` untuk pengguna non-Superadmin:
     ```php
     $status = ($user['role'] === 'Superadmin') ? (in_array($reqStatus, $allowedStatuses) ? $reqStatus : 'Diinput') : 'Diinput';
     ```
   - Kriteria Penerimaan: Mengirim request POST dengan `"status": "Final"` oleh operator biasa tetap menghasilkan record dengan status `Diinput`.

3. **Remediasi AUD-SEC-008 & AUD-BIZ-003 (Otorisasi Teritorial Ketat)**:
   - Tindakan:
     a. Pada `backend/app/Http/Controllers/Api/VerifikasiController.php`, tambahkan territorial guard yang memeriksa `$user->desa` dan `$user->kecamatan` terhadap data pendukung yang akan diubah.
     b. Pada `api/verifikasi.php`, ubah logika pemeriksaan agar gagal secara aman (fail-closed): jika peran adalah Koordinator Desa namun nilai `$user['desa']` kosong, tolak seluruh akses verifikasi.
   - Kriteria Penerimaan: Koordinator Desa Besuk Kidul ditolak (HTTP 403) saat mencoba mengubah status pendukung di Desa Semampir.

4. **Remediasi AUD-SEC-009 (Validasi Sertifikat SSL OCR)**:
   - Tindakan: Pada `api/ocr.php` (baris 131-132), ubah nilai menjadi:
     ```php
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
     ```
   - Kriteria Penerimaan: Koneksi cURL memvalidasi sertifikat TLS server Google secara ketat.

5. **Remediasi AUD-DEP-001 & AUD-DEP-002 (Hardening Skrip Deployment)**:
   - Tindakan:
     a. Pada `cpanel_quick_deploy.sh`, hapus izin fallback `chmod -R 777`. Gunakan `chmod 755` untuk direktori dan `chmod 644` untuk berkas.
     b. Pada `cpanel_clean_database.sh`, gunakan file konfigurasi sementara MySQL (`~/.my.cnf`) atau pipe aman untuk menghindari penampakan password pada `ps aux`.
   - Kriteria Penerimaan: Perintah `ps aux` di server saat proses deployment berjalan tidak memperlihatkan password database.

---

#### FASE 3: UNIFIKASI DUAL-STACK & INTEGRITAS BASIS DATA (TARGET: SELESAI < 14 HARI)

1. **Remediasi AUD-ARCH-001 (Standardisasi Kolom Password)**:
   - Tindakan:
     a. Standardisasi kolom password pengguna pada skema MySQL menjadi satu kolom tunggal: `password`.
     b. Buat migrasi SQL untuk menyalin data hash dari `password_hash` ke `password` bagi record yang belum tersinkronisasi.
     c. Perbarui query di `api/auth.php` dan `api/users.php` agar secara eksklusif menggunakan kolom `password`.
   - Kriteria Penerimaan: Pengguna yang mendaftar via Laravel dapat langsung login di antarmuka PHP prosedural dan sebaliknya.

2. **Remediasi AUD-DATA-001 (Unique Constraint NIK)**:
   - Tindakan: Eksekusi skrip pembersihan duplikasi data yang ada, kemudian terapkan indeks unik:
     ```sql
     ALTER TABLE `pendukung` ADD UNIQUE INDEX `uk_pendukung_nik` (`nik`);
     ```
   - Kriteria Penerimaan: Eksekusi dua query insert dengan NIK yang sama secara paralel menghasilkan error database `1062 Duplicate entry`.

3. **Remediasi AUD-DATA-004 (Penghapusan DDL Runtime)**:
   - Tindakan: Hapus pemanggilan `self::ensureTables()` dari `Database::init()` di `config/database.php`. Pindahkan seluruh definisi struktur tabel ke skrip migrasi resmi.
   - Kriteria Penerimaan: Permintaan API tidak lagi menjalankan query `SHOW COLUMNS` atau `CREATE TABLE` pada setiap siklus request.

4. **Remediasi AUD-PERF-001 (Paginasi Server-Side Dashboard & Pendukung)**:
   - Tindakan:
     a. Hapus properti `allRecords` dari response JSON `api/dashboard.php`. Dashboard hanya perlu mengembalikan data agregasi statistik dan titik koordinat untuk peta.
     b. Terapkan paginasi parameter `limit` dan `page` (default 25 record per halaman) pada `api/pendukung.php?action=list`.
   - Kriteria Penerimaan: Ukuran response payload dashboard berkurang dari estimasi 15 MB menjadi di bawah 150 KB.

---

#### FASE 4: TESTING, QA & HARDENING PRODUKSI (TARGET: SELESAI < 30 HARI)

1. **Remediasi AUD-TEST-001 (Automated Test Suite)**:
   - Tindakan: Tulis rangkaian pengujian otomatis menggunakan PHPUnit / Pest:
     - `AuthTest`: Menguji login valid, login invalid, penolakan password backdoor.
     - `PendukungTest`: Menguji validasi form, pencegahan duplikasi NIK, dan pemaksaan status Diinput.
     - `VerifikasiTest`: Menguji batasan wewenang teritorial Kordes dan Korcam.
   - Kriteria Penerimaan: Perintah `php artisan test` mengeksekusi minimal 25 skenario pengujian dengan status PASS 100%.

2. **Remediasi AUD-DEP-004 & AUD-CODE-001 (Pembersihan Repositori)**:
   - Tindakan:
     a. Hapus `backend/vendor.zip` dari riwayat Git (tambahkan ke `.gitignore`).
     b. Hapus berkas pengujian sisa: `test_js.js`, `test.txt`, `update_ui.php`, `b64_mod.txt`.
   - Kriteria Penerimaan: Ukuran checkout repositori bersih berkurang lebih dari 35 MB.

---

### 3. JADWAL & ESTIMASI ALOKASI WAKTU TEKNIS

| Fase | Durasi Kalender | Estimasi Man-Hours | Penanggung Jawab | Prioritas |
| :--- | :---: | :---: | :--- | :---: |
| **Fase 1: Hotfix Kritis** | Hari ke-1 (24 Jam) | 4 - 6 Jam | Senior Security Engineer / Lead Dev | P0 (Emergency) |
| **Fase 2: Keamanan & Otorisasi** | Hari ke-2 s/d Hari ke-7 | 16 - 24 Jam | Full-Stack Developer | P1 (Urgent) |
| **Fase 3: Unifikasi & Database** | Hari ke-8 s/d Hari ke-14 | 20 - 30 Jam | Database Architect / Backend Lead | P2 (High) |
| **Fase 4: Testing & Hardening** | Hari ke-15 s/d Hari ke-30 | 20 - 28 Jam | QA Engineer / DevOps | P3 (Medium) |
| **TOTAL KESELURUHAN** | **30 Hari Kalender** | **60 - 88 Jam** | Tim Engineering Terpadu |  |
