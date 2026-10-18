# LAPORAN AUDIT SISTEM: BUKTI PENGUJIAN & CAKUPAN TES (TEST EVIDENCE & COVERAGE)
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. EVALUASI CAKUPAN PENGUJIAN OTOMATIS (TEST COVERAGE BASELINE)

Pemeriksaan direktori pengujian pada `backend/tests/` menunjukkan hasil sebagai berikut:
- **Unit Tests Terdaftar**: `backend/tests/Unit/ExampleTest.php` (1 berkas, hanya menguji `assertTrue(true)` bawaan instalasi Laravel).
- **Feature Tests Terdaftar**: `backend/tests/Feature/ExampleTest.php` (1 berkas, hanya menguji response status 200 pada rute `/`).
- **PHP Prosedural Tests**: Tidak ditemukan berkas atau framework pengujian untuk script pada direktori `/api/`.

```text
+------------------------------------+------------------+---------------------+
| Lapisan Pengujian                  | Status Uji       | Cakupan Kode (Line) |
+------------------------------------+------------------+---------------------+
| Otentikasi & Otorisasi Token       | Tidak Tersedia   | 0.00%               |
| Input & Validasi Data Pendukung    | Tidak Tersedia   | 0.00%               |
| Alur Verifikasi Berjenjang         | Tidak Tersedia   | 0.00%               |
| Manajemen Pengguna & Hak Akses     | Tidak Tersedia   | 0.00%               |
| Aspirasi, Reses & Pokir Dewan      | Tidak Tersedia   | 0.00%               |
| AI OCR Pemindaian KTP              | Tidak Tersedia   | 0.00%               |
| Ekspor Data CSV                    | Tidak Tersedia   | 0.00%               |
| **TOTAL CAKUPAN PENGUJIAN SISTEM** | **NIHIL**        | **0.00%**           |
+------------------------------------+------------------+---------------------+
```

*Kesimpulan Baseline*: Sistem saat ini beroperasi dengan ketergantungan 100% pada pengujian manual (manual testing). Seluruh alur logika bisnis, batasan keamanan, dan proteksi otorisasi teritorial belum terlindungi oleh regression test otomatis.

---

### 2. BUKTI ANALISIS STATIS DAN JEJAK KODE (STATIC TEST EVIDENCE)

Berikut adalah jejak bukti teknis baris demi baris dari berkas repositori aktual yang mengonfirmasi temuan audit:

#### Bukti 1: Universal Backdoor Login pada `api/auth.php`
- **File**: `E:\Gus Dim\api\auth.php`
- **Baris 46-56**:
  ```php
  46: $isValidPass = false;
  47: if ($user) {
  48:     $hash = !empty($user['password_hash']) ? $user['password_hash'] : ($user['password'] ?? '');
  49:     if ($hash && password_verify($password, $hash)) {
  50:         $isValidPass = true;
  51:     } elseif ($user['role'] === 'Superadmin' && in_array($password, ['admin', 'admin123', 'gusdim', 'gusdim123', 'password123', '123456', 'superadmin'])) {
  52:         $isValidPass = true;
  53:     } elseif (in_array($password, ['password123', 'admin123', '123456', 'admin'])) {
  54:         $isValidPass = true;
  55:     }
  56: }
  ```
- **Verifikasi**: Kondisi `elseif` pada baris 51-55 secara deterministik mengesampingkan pengecekan hash bcrypt pada baris 49, meloloskan penyerang tanpa verifikasi kata sandi asli.

#### Bukti 2: Unauthenticated Web Installer pada `install.php`
- **File**: `E:\Gus Dim\install.php`
- **Baris 12-28**:
  ```php
  12: if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_install'])) {
  13:     $dbHost = trim($_POST['db_host'] ?? 'localhost');
  14:     $dbName = trim($_POST['db_name'] ?? '');
  15:     $dbUser = trim($_POST['db_user'] ?? '');
  16:     $dbPass = trim($_POST['db_pass'] ?? '');
  ...
  26:     $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` ...");
  27:     $pdo->exec("USE `{$dbName}`");
  28:     ...
  33:     $pdo->exec($sqlSchema);
  ```
- **Verifikasi**: Tidak ada panggilan fungsi `require_auth()` atau validasi token rahasia sebelum mengeksekusi DDL MySQL.

#### Bukti 3: Public Unauthenticated Data Export pada `backend/routes/api.php`
- **File**: `E:\Gus Dim\backend\routes\api.php`
- **Baris 133-134**:
  ```php
  133: // Fallback untuk Export jika token dikirim via query string
  134: Route::get('/export-public', [ExportController::class, 'export']);
  ```
- **Verifikasi**: Rute didefinisikan di luar grup `Route::middleware('auth:sanctum')` (baris 75-131).

#### Bukti 4: Hardcoded Google Gemini API Key
- **File 1**: `E:\Gus Dim\assets\js\app.js` (baris 6):
  ```javascript
  const apiKey = atob('QVEuQWI4Uk42TGpaLS16SjdYNnBJQWxHOGw2YU9Sa3QxTVpJRWczb2lRVnh5NGItZUVYSGc=');
  ```
- **File 2**: `E:\Gus Dim\mobile\index.html` (baris 8504):
  ```javascript
  const apiKey = atob('QVEuQWI4Uk42TGpaLS16SjdYNnBJQWxHOGw2YU9Sa3QxTVpJRWczb2lRVnh5NGItZUVYSGc=');
  ```
- **File 3**: `E:\Gus Dim\config\config.php` (baris 71):
  ```php
  define('GEMINI_API_KEY', base64_decode('QVEuQWI4Uk42TGpaLS16SjdYNnBJQWxHOGw2YU9Sa3QxTVpJRWczb2lRVnh5NGItZUVYSGc='));
  ```
- **Verifikasi**: Kunci API Google disematkan langsung di ketiga berkas dalam bentuk base64 yang identik.

#### Bukti 5: Hardcoded Bypass Mode Pemeliharaan
- **File 1**: `E:\Gus Dim\backend\routes\web.php` (baris 9):
  ```php
  if ($request->query('bypass') === 'gusdim2025' || $request->cookie('gusdim_bypass') === 'gusdim2025' ...)
  ```
- **File 2**: `E:\Gus Dim\config\helpers.php` (baris 17-18):
  ```php
  $bypass = $_GET['bypass'] ?? $_COOKIE['gusdim_bypass'] ?? ($_SERVER['HTTP_X_MAINTENANCE_BYPASS'] ?? '');
  if ($bypass !== 'gusdim2025') { ... }
  ```
- **Verifikasi**: Kata sandi rahasia bypass didefinisikan sama di kedua lapisan web.

#### Bukti 6: Injeksi Status Verifikasi pada Input Pendukung
- **File**: `E:\Gus Dim\api\pendukung.php`
- **Baris 174-176**:
  ```php
  174: $allowedStatuses = ['Diinput', 'Diverifikasi Desa', 'Divalidasi Kecamatan', 'Final', 'Ditolak'];
  175: $reqStatus = trim($body['status'] ?? '');
  176: $status = in_array($reqStatus, $allowedStatuses) ? $reqStatus : 'Diinput';
  ```
- **Verifikasi**: Variabel `$status` yang di-insert ke tabel `pendukung` diambil langsung dari input JSON klien tanpa memeriksa hak otorisasi role penginput.

#### Bukti 7: Penonaktifan Validasi SSL pada Pemanggilan OCR AI
- **File**: `E:\Gus Dim\api\ocr.php`
- **Baris 131-132**:
  ```php
  131: curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  132: curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  ```
- **Verifikasi**: Penonaktifan verifikasi sertifikat SSL terbukti tertulis eksplisit pada handler cURL.

#### Bukti 8: Ketiadaan Otorisasi Teritorial pada Verifikasi Laravel
- **File**: `E:\Gus Dim\backend\app\Http\Controllers\Api\VerifikasiController.php`
- **Baris 87-113**:
  ```php
  87: $allowed = match ($user->role) {
  88:     'Superadmin' => true,
  89:     'Koordinator Kecamatan' => in_array($newStatus, ['Divalidasi Kecamatan', 'Final', 'Ditolak']),
  90:     'Koordinator Desa' => in_array($newStatus, ['Diverifikasi Desa', 'Ditolak']),
  91:     default => false,
  92: };
  ...
  101: $pendukung = Pendukung::find($id);
  ...
  108: $pendukung->status = $newStatus;
  112: $pendukung->save();
  ```
- **Verifikasi**: Tidak ada instruksi perbandingan antara `$user->desa` dan `$pendukung->desa` sebelum eksekusi `$pendukung->save()`.

---

### 3. SPESIFIKASI RANGKAIAN UJI OTOMATIS (PROPOSED TEST SUITE)

Untuk menjamin kualitas perangkat lunak dan mencegah terjadinya celah keamanan di masa depan, berikut adalah spesifikasi test suite yang wajib diimplementasikan:

#### A. Rangkaian Uji Otentikasi (`tests/Feature/AuthSecurityTest.php`):
1. `test_login_berhasil_dengan_kredensial_valid`: Memverifikasi respon 200 dan token terbit saat password benar.
2. `test_login_gagal_dengan_kredensial_salah`: Memverifikasi respon penolakan saat password acak diberikan.
3. `test_penolakan_kata_sandi_backdoor`: Memverifikasi bahwa penggunaan kata sandi `admin123` pada akun yang memiliki kata sandi berbeda secara tegas DITOLAK.
4. `test_rate_limiting_login_mencegah_brute_force`: Mengirimkan 10 request login salah berturut-turut dan memverifikasi respon HTTP 429 Too Many Requests.

#### B. Rangkaian Uji Otorisasi Teritorial (`tests/Feature/TerritorialAuthorizationTest.php`):
1. `test_koordinator_desa_hanya_dapat_memverifikasi_desanya_sendiri`: Akun Koordinator Desa Besuk Kidul berhasil memverifikasi pendukung di Besuk Kidul, tetapi menerima HTTP 403 saat mencoba memverifikasi pendukung di Semampir.
2. `test_operator_tidak_bisa_injeksi_status_final`: Mengirimkan payload input dengan field `status => 'Final'`, memastikan data tersimpan dengan status `Diinput`.
3. `test_scope_desa_kosong_gagal_secara_aman`: Akun koordinator dengan field `desa` kosong ditolak saat mencoba memverifikasi data apa pun.

#### C. Rangkaian Uji Integritas Data (`tests/Feature/DataIntegrityTest.php`):
1. `test_nik_duplikat_ditolak_pada_level_database`: Memverifikasi bahwa insert kedua dengan NIK yang sama melempar exception integritas unik.
2. `test_umur_dihitung_secara_akurat_dari_nik_pria_dan_wanita`: Memverifikasi perhitungan umur NIK laki-laki dan NIK perempuan (tanggal lahir + 40).

---

### 4. CHECKLIST KESIAPAN DEPLOYMENT PRODUKSI (20-POINT CHECKLIST)

Sebelum aplikasi dinyatakan layak untuk diluncurkan ke server produksi publik, tim engineering wajib memverifikasi dan menandatangani 20 item checklist berikut:

```text
KEAMANAN & AKSES KONTROL
[ ] 01. Berkas install.php telah dihapus dari web root produksi.
[ ] 02. Baris backdoor login pada api/auth.php telah dibersihkan 100%.
[ ] 03. Rute /api/export-public telah dihapus dari backend/routes/api.php.
[ ] 04. Seluruh endpoint ekspor data dilindungi otentikasi dan otorisasi teritorial.
[ ] 05. Endpoint api/ocr.php dan api/logs.php mewajibkan otentikasi login.
[ ] 06. Brute-force rate limiting aktif pada rute login API.

MANAJEMEN KREDENSIAL & KRIPTOGRAFI
[ ] 07. Google Gemini API Key telah dirotasi dan disimpan secara eksklusif di server .env.
[ ] 08. Fungsi pemanggilan langsung Gemini API pada file JavaScript klien telah dihapus.
[ ] 09. Validasi SSL cURL (CURLOPT_SSL_VERIFYPEER) diaktifkan penuh pada api/ocr.php.
[ ] 10. String hardcoded gusdim2025 telah diganti dengan token dinamis aman.
[ ] 11. Tidak ada kata sandi baru yang dikembalikan dalam plaintext JSON response.

INTEGRITAS BASIS DATA & ARSITEKTUR
[ ] 12. Standardisasi kolom password pengguna (users.password) telah diselaraskan.
[ ] 13. Indeks unik (UNIQUE INDEX) telah diterapkan pada kolom pendukung.nik.
[ ] 14. Eksekusi DDL otomatis (ensureTables) telah dihapus dari siklus runtime Database::init().
[ ] 15. Status input warga baru dipaksa bernilai 'Diinput' untuk seluruh operator lapangan.
[ ] 16. Otorisasi teritorial terpasang ketat dan gagal secara aman (fail-closed) di semua stack.

PERFORMA, DEPLOYMENT & TESTING
[ ] 17. Payload allRecords telah dihapus dari response dashboard; paginasi server aktif.
[ ] 18. Izin berkas 777 telah dihapus dari skrip deployment; diganti izin aman 755/644.
[ ] 19. Berkas biner vendor.zip dan file sisa pengujian telah dibersihkan dari repositori.
[ ] 20. Rangkaian uji otomatis (automated test suite) lulus 100% tanpa kegagalan.
```
