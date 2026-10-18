# LAPORAN AUDIT SISTEM: MODULE AUDIT MATRIX
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. MATRIKS EVALUASI KELENGKAPAN MODUL

Tabel berikut menyajikan status implementasi, integritas fungsional, dan penilaian risiko pada masing-masing modul sistem:

| ID Modul | Nama Modul | File Implementasi | Status Implementasi | Status Keamanan | Peringkat Risiko |
| :--- | :--- | :--- | :--- | :--- | :---: |
| MOD-01 | Autentikasi & Profil Pengguna | `api/auth.php`, `backend/.../AuthController.php` | Parsial / Inkonsisten | Kritis (Backdoor Aktif) | **CRITICAL** |
| MOD-02 | Pendukung & Konstituen 5 Jalur | `api/pendukung.php`, `backend/.../PendukungController.php` | Fungsional Penuh | Tinggi (Bypass Status & Race NIK) | **HIGH** |
| MOD-03 | Verifikasi Berjenjang | `api/verifikasi.php`, `backend/.../VerifikasiController.php` | Fungsional Parsial | Tinggi (Fail-Open Scope Teritorial) | **HIGH** |
| MOD-04 | Manajemen Pengguna & Operator | `api/users.php`, `backend/.../UserController.php` | Fungsional | Tinggi (Info Disclosure & Predictable Pass) | **HIGH** |
| MOD-05 | Dashboard & Pemetaan Spasial | `api/dashboard.php`, `backend/.../DashboardController.php` | Fungsional | Sedang (OOM Payload & Full Data Exposure) | **MEDIUM** |
| MOD-06 | Aspirasi Warga | `api/aspirasi.php` | Fungsional (Hanya PHP) | Sedang (Hardcoded Secret Reverb, No BOLA) | **MEDIUM** |
| MOD-07 | Reses & Pokir Dewan | `api/reses.php` | Fungsional (Hanya PHP) | Sedang (Auto-Enroll Status Diverifikasi) | **MEDIUM** |
| MOD-08 | AI Vision OCR e-KTP | `api/ocr.php` | Fungsional | Kritis (No Auth, SSL Disabled, NIK Enum) | **CRITICAL** |
| MOD-09 | Ekspor Data Pemenangan | `api/export.php`, `backend/.../ExportController.php` | Fungsional | Kritis (Public PII Leakage / UU PDP) | **CRITICAL** |
| MOD-10 | Audit Log Aktivitas | `api/logs.php`, `backend/.../AuditLogController.php` | Fungsional | Sedang (Unauthenticated Read di PHP) | **MEDIUM** |
| MOD-11 | Maintenance Mode & Web Installer | `config/helpers.php`, `install.php`, `web.php` | Fungsional | Kritis (Unauth Installer, Hardcoded Bypass)| **CRITICAL** |

---

### 2. ANALISIS MENDALAM PER MODUL SISTEM

#### MOD-01: Autentikasi & Profil Pengguna
- **Tujuan & Ruang Lingkup**: Mengelola login pengguna, sesi aktif (token bearer), pembacaan profil, pembaruan nama profil, unggah foto profil avatar (base64), serta penggantian kata sandi.
- **Implementasi**: 
  - PHP Prosedural: `api/auth.php` (220 baris).
  - Laravel: `backend/app/Http/Controllers/Api/AuthController.php` (222 baris).
- **Temuan Celah & Evaluasi Teknis**:
  1. *Universal Backdoor*: Pada `api/auth.php` (baris 51-55), kata sandi sembarang seperti `admin`, `admin123`, `gusdim`, `gusdim123`, `password123`, `123456`, atau `superadmin` dapat digunakan untuk masuk ke akun Superadmin atau akun operator lain tanpa validasi bcrypt.
  2. *Inkonsistensi Kolom Password*: Laravel membaca dan menulis ke kolom `password`, sedangkan PHP prosedural membaca dan menulis ke kolom `password_hash`. Akun yang dibuat di satu sistem tidak dapat login di sistem lainnya.
  3. *Unprotected Adapter Route di Laravel*: Pada `backend/routes/api.php` (baris 60-72), rute `/auth.php?action=update-profile` dan `update-avatar` ditempatkan di luar middleware `auth:sanctum`. Pemanggilan endpoint ini menghasilkan error fatal 500 karena `$request->user()` mengembalikan nilai `null`.
  4. *Format Token Berbeda*: Laravel menerbitkan Sanctum PlainTextToken (`id|hash`), sementara PHP menerbitkan random hex 64-karakter yang disimpan di tabel `user_tokens`. Sesi tidak dapat saling dipertukarkan antar-backend.

#### MOD-02: Pendukung & Konstituen 5 Jalur
- **Tujuan & Ruang Lingkup**: Perekaman dan pemeliharaan data konstituen pemilu pada 5 jalur pemenangan:
  1. DPC (Struktur Pengurus Kecamatan)
  2. DPRT (Struktur Pengurus Ranting Desa)
  3. PIP (Penerima Beasiswa Program Indonesia Pintar SD/SMP/SMA)
  4. KIP (Penerima Beasiswa Kuliah Kartu Indonesia Pintar)
  5. RELAWAN (Relawan TPS & Penggerak Lapangan)
- **Implementasi**:
  - PHP Prosedural: `api/pendukung.php` (700 baris).
  - Laravel: `backend/app/Http/Controllers/Api/PendukungController.php` (387 baris).
- **Temuan Celah & Evaluasi Teknis**:
  1. *Status Injection & Workflow Bypass*: Pada `api/pendukung.php` (baris 174-176), sistem menerima status langsung dari payload request body:
     ```php
     $allowedStatuses = ['Diinput', 'Diverifikasi Desa', 'Divalidasi Kecamatan', 'Final', 'Ditolak'];
     $reqStatus = trim($body['status'] ?? '');
     $status = in_array($reqStatus, $allowedStatuses) ? $reqStatus : 'Diinput';
     ```
     Operator atau relawan di level terendah dapat mengirimkan parameter `"status": "Final"` saat input data baru, melompati seluruh proses verifikasi Desa dan Kecamatan.
  2. *NIK Duplicate Check Race Condition*: Pemeriksaan duplikasi NIK dilakukan via query `SELECT id FROM pendukung WHERE nik = :nik LIMIT 1`. Karena tabel `pendukung` tidak memiliki constraint `UNIQUE KEY` pada kolom `nik`, request yang dikirimkan secara paralel / bersamaan akan meloloskan data duplikat ke dalam database.
  3. *Inkonsistensi File Upload*: PHP menyimpan gambar ke direktori root `uploads/[subfolder]/`, sedangkan Laravel menyimpan ke `storage/app/public/[subfolder]/`. Hal ini menyebabkan URL gambar broken jika sistem beralih backend.

#### MOD-03: Verifikasi Berjenjang
- **Tujuan & Ruang Lingkup**: Mengontrol alur status validitas konstituen: `Diinput` -> `Diverifikasi Desa` -> `Divalidasi Kecamatan` -> `Final (Sah)` atau `Ditolak`.
- **Implementasi**:
  - PHP Prosedural: `api/verifikasi.php` (228 baris).
  - Laravel: `backend/app/Http/Controllers/Api/VerifikasiController.php` (136 baris).
- **Temuan Celah & Evaluasi Teknis**:
  1. *Fail-Open Territorial Guard*: Pada `api/verifikasi.php` (baris 180-197), pengecekan wilayah berbunyi:
     ```php
     if ($userDesa !== '' && strcasecmp($userDesa, $targetDesa) !== 0) { ... tolak ... }
     ```
     Jika akun Koordinator Desa memiliki nilai kolom `desa` kosong (`""` atau `NULL`), kondisi diabaikan dan pemeriksaan wilayah lolos secara fail-open, memungkinkan pengguna memverifikasi data desa mana pun.
  2. *Otorisasi Teritorial Absen Total di Laravel*: Pada `backend/app/Http/Controllers/Api/VerifikasiController.php` (baris 76-120), method `updateStatus()` sama sekali TIDAK MEMILIKI pengecekan wilayah desa maupun kecamatan. Koordinator Desa dari Desa A dapat mengubah status data konstituen di Desa B atau Kecamatan lain secara bebas (Broken Object Level Authorization / BOLA).
  3. *Locking Bypass*: Pada Laravel, tidak ada pemeriksaan status asal data saat verifikasi. Koordinator Desa dapat menurunkan status data yang sudah disahkan menjadi `Final` oleh Superadmin/Korcam kembali menjadi `Diinput`.

#### MOD-04: Manajemen Pengguna & Operator Lapangan
- **Tujuan & Ruang Lingkup**: Mengatur akun sistem, peran (Superadmin, Koordinator Kecamatan, Koordinator Desa, Admin Ranting), status aktif/nonaktif, reset kata sandi, dan otomasi pembentukan akun operator dari data pendukung yang valid.
- **Implementasi**:
  - PHP Prosedural: `api/users.php` (449 baris).
  - Laravel: `backend/app/Http/Controllers/Api/UserController.php` (155 baris).
- **Temuan Celah & Evaluasi Teknis**:
  1. *Unauthenticated Staff Enumeration*: Pada `api/users.php` (baris 20-26), pemanggilan endpoint `action=list` oleh pengguna publik yang tidak login (`$user === null`) tetap mengembalikan daftar seluruh operator aktif lengkap dengan Nama, Peran, Kecamatan, Desa, dan Ranting.
  2. *Kata Sandi Lemah dan Tertebak*: Pembuatan akun otomatis via `action=create-from-pendukung` menghasilkan password berupa 6 digit terakhir NIK atau default `123456`. Ditambah dengan backdoor login pada `api/auth.php`, seluruh akun operator yang baru dibuat rentan dibajak.
  3. *Plaintext Password Leakage dalam Response API*: Pada `api/users.php` (baris 310 dan 395), kata sandi baru dikembalikan dalam plaintext JSON response:
     ```php
     'password' => $passwordPlain
     ```
     Jika koneksi tidak terlindungi atau log response tercatat di server proxy/browser, kata sandi mudah tereskpos.

#### MOD-05: Dashboard & Pemetaan Spasial
- **Tujuan & Ruang Lingkup**: Visualisasi statistik ringkasan pendukung per jalur, agregasi per kecamatan, titik koordinat peta sebaran (Leaflet basemap), serta daftar aktivitas terbaru.
- **Implementasi**:
  - PHP Prosedural: `api/dashboard.php` (129 baris).
  - Laravel: `backend/app/Http/Controllers/Api/DashboardController.php` (89 baris).
- **Temuan Celah & Evaluasi Teknis**:
  1. *Memory Exhaustion (OOM) via allRecords*: Endpoint dashboard mengembalikan seluruh baris tabel pendukung secara mentah dalam kunci response `allRecords`:
     ```php
     'allRecords' => $records
     ```
     Pada dataset pemilu riil (puluhan ribu pendukung), transfer data JSON puluhan megabyte per muat halaman akan memicu Out-of-Memory pada PHP dan crashing pada browser ponsel pintar relawan.
  2. *Data Scope Leakage di Laravel Dashboard*: `DashboardController.php` di Laravel tidak menyaring data berdasarkan role teritorial pengguna. Koordinator Desa dapat melihat seluruh data se-Kabupaten Probolinggo melalui dashboard Laravel.

#### MOD-06: Aspirasi Warga & Advokasi
- **Tujuan & Ruang Lingkup**: Pencatatan keluhan, permohonan bantuan, dan usulan pembangunan dari warga kepada Gus Dim, dilengkapi pelacakan status tindak lanjut dan integrasi siaran notifikasi real-time WebSocket.
- **Implementasi**: `api/aspirasi.php` (187 baris). Tidak tersedia di backend Laravel.
- **Temuan Celah & Evaluasi Teknis**:
  1. *Hardcoded Credential Pusher / Reverb*: Nilai `appKey` dan `appSecret` tercantum secara hardcoded di baris 152-154 sebagai fallback:
     ```php
     $appId = $env['REVERB_APP_ID'] ?? '918237';
     $appKey = $env['REVERB_APP_KEY'] ?? 'ifst0r0e5f3stkfy1k6g';
     $appSecret = $env['REVERB_APP_SECRET'] ?? 'k4h6xeknfxs0lftukb5x';
     ```
  2. *Ketiadaan Otorisasi pada Update Status*: Setiap pengguna yang login (termasuk peran relawan tingkat ranting) dapat memanggil `action=update-status` dan mengubah status aspirasi apa pun di seluruh daerah pemilihan tanpa pembatasan teritorial atau hak wewenang.

#### MOD-07: Reses & Pokir Dewan
- **Tujuan & Ruang Lingkup**: Manajemen titik kegiatan reses anggota dewan, daftar kehadiran warga peserta reses, serta inventarisasi Pokok-Pokok Pikiran (Pokir) usulan anggaran APBD.
- **Implementasi**: `api/reses.php` (439 baris). Tidak tersedia di backend Laravel.
- **Temuan Celah & Evaluasi Teknis**:
  1. *Bypass Status Verifikasi Otomatis*: Saat absensi warga dicatat via `action=add_kehadiran`, baris 273-286 secara otomatis mendaftarkan warga ke tabel `pendukung` dengan status `'Diverifikasi Desa'`:
     ```php
     VALUES ('RELAWAN', :nama, :nik, :hp, :kec, :desa, 'Diverifikasi Desa', ...)
     ```
     Warga tersebut otomatis berstatus terverifikasi tanpa pernah diverifikasi secara faktual oleh Koordinator Desa dan tanpa foto identitas KTP.
  2. *Ketiadaan Validasi Hak Penjadwalan*: Akun berstatus Relawan Ranting dapat menambahkan titik agenda reses dewan resmi (`action=add_event`) karena tidak adanya validasi role check pada endpoint tersebut.

#### MOD-08: AI Vision OCR e-KTP
- **Tujuan & Ruang Lingkup**: Ekstraksi otomatis data identitas e-KTP (NIK 16 digit, Nama, TTL, Alamat, RT/RW, Desa, Kecamatan, Agama, Pekerjaan) langsung dari foto menggunakan model Google Gemini Vision AI (`gemini-3.5-flash`).
- **Implementasi**: `api/ocr.php` (262 baris). Tidak tersedia di backend Laravel.
- **Temuan Celah & Evaluasi Teknis**:
  1. *Unauthenticated Endpoint & Quota Exhaustion*: Endpoint `api/ocr.php` tidak memanggil fungsi `require_auth()`. Siapa pun dari internet publik dapat mengirimkan gambar untuk diproses oleh Gemini API menggunakan kuota API server, memicu penolakan layanan (Denial of Service) akibat habisnya kuota berbayar.
  2. *SSL Verification Disabled*: Baris 131-132 secara eksplisit menonaktifkan validasi sertifikat SSL cURL:
     ```php
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     ```
     Lalu lintas pengiriman foto KTP warga ke API Google rentan disadap atau dimanipulasi melalui serangan Man-in-the-Middle (MitM).
  3. *Unauthenticated NIK Probing / Enumeration*: Pada baris 208-239, API memeriksa apakah NIK yang terbaca sudah terdaftar di database dan mengembalikan nama lengkap, desa, kecamatan, serta penginput jika ditemukan. Penyerang dapat mengunggah gambar dengan NIK target untuk memeriksa apakah target adalah pendukung Gus Dim.

#### MOD-09: Ekspor Data Pemenangan
- **Tujuan & Ruang Lingkup**: Pengunduhan berkas rekap data konstituen pemilu ke dalam format berkas CSV yang kompatibel dengan Microsoft Excel.
- **Implementasi**:
  - PHP Prosedural: `api/export.php` (76 baris).
  - Laravel: `backend/app/Http/Controllers/Api/ExportController.php` (69 baris).
- **Temuan Celah & Evaluasi Teknis**:
  1. *Public Unauthenticated Route di Laravel*: Rute `/api/export-public` pada `backend/routes/api.php` baris 134 mengarahkan langsung ke `ExportController::class, 'export'` tanpa proteksi auth Sanctum. Data PII (NIK 16 digit, Nomor HP, Nama Lengkap, Alamat) bocor ke publik tanpa otentikasi.
  2. *Ketiadaan Role & Territorial Scope Check*: Pada `api/export.php`, pengguna mana pun yang login (termasuk Koordinator Desa) dapat mengunduh seluruh data se-Dapil dengan menyertakan parameter `jalur=ALL`. Hal ini melanggar prinsip pembatasan data kebutuhan tugas (Need-to-Know basis).

#### MOD-10: Audit Log Aktivitas
- **Tujuan & Ruang Lingkup**: Pencatatan jejak audit (waktu, ID pengguna, nama pengguna, aksi, ID referensi, keterangan, dan alamat IP).
- **Implementasi**:
  - PHP Prosedural: `api/logs.php` (29 baris).
  - Laravel: `backend/app/Http/Controllers/Api/AuditLogController.php` (28 baris).
- **Temuan Celah & Evaluasi Teknis**:
  1. *Unauthenticated Log Inspection di PHP*: Berkas `api/logs.php` hanya memanggil `$user = get_auth_user()` tanpa memeriksa apakah hasilnya valid (`require_auth()`). Pengguna publik tanpa login dapat membaca 200 riwayat aktivitas log sistem terkini.

#### MOD-11: Maintenance Mode & Web Installer
- **Tujuan & Ruang Lingkup**: Pengendalian akses saat perawatan server (`maintenance.lock`) serta wizard konfigurasi database awal (`install.php`).
- **Implementasi**: `install.php` (220 baris), `config/helpers.php` (baris 17-34), `CheckMaintenanceMode.php`.
- **Temuan Celah & Evaluasi Teknis**:
  1. *Web Installer Terbuka Bebas*: File `install.php` berada di web root dan tidak memiliki token otentikasi. Penyerang dapat mereset database, menghapus seluruh tabel, dan menimpa konfigurasi `config/config.php`.
  2. *Bypass Hardcoded*: Nilai `gusdim2025` tertanam di kode sumber sebagai kunci pembuka mode pemeliharaan.
