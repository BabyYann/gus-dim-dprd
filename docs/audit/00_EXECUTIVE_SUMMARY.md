# LAPORAN AUDIT SISTEM: EXECUTIVE SUMMARY
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. RINGKASAN EKSEKUTIF

Audit sistem menyeluruh telah dilaksanakan terhadap repositori Sistem Informasi Manajemen Pemenangan "Gus Dim" Dapil Kraksaan Raya (Kabupaten Probolinggo). Audit mencakup analisis arsitektur, keamanan aplikasi (mengacu pada OWASP Top 10 dan OWASP ASVS 4.0), integritas basis data, validasi alur bisnis pemilu/konstituen, perbandingan paritas frontend dan backend ganda (dual-stack), keandalan performa, serta kesiapan infrastruktur deployment.

Sistem dirancang untuk mendukung operasional pemenangan legislatif pada lima jalur konstituen (DPC, DPRT, PIP, KIP, dan Relawan), dilengkapi fitur pemindaian e-KTP berbasis kecerdasan buatan (Google Gemini Vision AI), verifikasi berjenjang teritorial, pencatatan aspirasi, serta penjadwalan reses dewan.

Hasil audit menemukan bahwa meskipun antarmuka pengguna (desktop dan mobile PWA) telah memiliki fitur fungsional yang kaya dan visual interaktif yang baik, sistem saat ini berada dalam kondisi RISIKO KEAMANAN TINGGI (HIGH RISK) dan belum layak untuk dioperasikan di lingkungan produksi publik. Terdapat sejumlah celah keamanan berkategori Kritis (Critical) dan Tinggi (High), termasuk keberadaan file instalasi web tanpa otentikasi, pintu belakang autentikasi (hardcoded backdoor), kunci API pihak ketiga yang terekspos di client-side, rute ekspor PII (Data Pribadi NIK) tanpa proteksi, serta divergensi arsitektur yang parah antara backend PHP prosedural dan backend Laravel 11.

---

### 2. INDEKS KESEHATAN SISTEM (SYSTEM HEALTH INDEX)

| Domain Penilaian | Skor (0 - 100) | Status | Ringkasan Kondisi |
| :--- | :---: | :---: | :--- |
| Keamanan & Otentikasi | 28 / 100 | Kritis | Terdapat backdoor login, unauthenticated installer, dan kebocoran API key |
| Arsitektur & Paritas Stack | 42 / 100 | Kurang | Arsitektur ganda (prosedural vs Laravel) menimbulkan drift dan inkonsistensi |
| Integritas Database | 55 / 100 | Cukup | Duplikasi nama kolom password, ketiadaan foreign key di beberapa tabel, fallback JSON rawan race condition |
| Alur Bisnis & Validasi | 62 / 100 | Cukup | Verifikasi berjenjang berjalan parsial, status draf dapat dilompati via manipulasi payload |
| Frontend & Pengalaman Pengguna | 78 / 100 | Baik | Antarmuka responsif dan PWA siap pakai, namun desktop rentan Stored XSS |
| Performa & Skalabilitas | 50 / 100 | Cukup | Query dashboard memuat seluruh record sekaligus (OOM risk pada data besar) |
| Kesiapan Deployment | 40 / 100 | Kurang | Skrip deployment mem-bypass platform check, izin 777, dan credential leakage di process table |
| Cakupan Pengujian Otomatis | 0 / 100 | Nihil | Tidak tersedia unit test, feature test, maupun integration test kustom |
| **SKOR KESELURUHAN (WEIGHTED)** | **44.5 / 100** | **TIDAK LAYAK PRODUKSI** | **Memerlukan Remediasi Prioritas Sebelum Rilis** |

---

### 3. STATISTIK TEMUAN AUDIT

Berdasarkan investigasi berbasis bukti pada seluruh berkas kode sumber dan konfigurasi, ditemukan total 32 temuan teknis yang diklasifikasikan ke dalam 5 tingkatan keparahan:

| Tingkat Keparahan (Severity) | Jumlah Temuan | Dampak Operasional / Bisnis |
| :--- | :---: | :--- |
| CRITICAL | 5 | Pengambilalihan kontrol server, pembajakan akun Superadmin, dan penghapusan database |
| HIGH | 8 | Kebocoran data pribadi (UU PDP), bypass mode pemeliharaan, kegagalan otentikasi antar-stack |
| MEDIUM | 11 | Kerentanan Stored XSS, race condition pada JSON store, inkonsistensi otorisasi teritorial |
| LOW | 5 | Repository bloat (arsip zip 36 MB), file diagnostik tertinggal, header SSL ganda |
| INFORMATIONAL | 3 | Dokumentasi cPanel, redundansi file skrip uji |
| **TOTAL TEMUAN** | **32** |  |

---

### 4. DAFTAR TEMUAN UTAMA PALING KRITIS

1. **AUD-SEC-001 [CRITICAL] - Unauthenticated Web Installer (install.php)**
   - Lokasi: `install.php` (baris 12-57)
   - Deskripsi: File instalasi web dapat diakses langsung oleh publik tanpa login. Penyerang dapat menginisialisasi ulang database, menghapus seluruh tabel konstituen, mengarahkan koneksi ke database eksternal penyerang, dan mereset password Superadmin menjadi `admin123`.

2. **AUD-SEC-002 [CRITICAL] - Universal Authentication Backdoor (api/auth.php)**
   - Lokasi: `api/auth.php` (baris 51-55)
   - Deskripsi: Terdapat logika bypass password yang mengizinkan login ke akun pengguna mana pun (termasuk Superadmin) menggunakan kata sandi hardcoded seperti `admin`, `admin123`, `gusdim`, `gusdim123`, `password123`, `123456`, atau `superadmin` terlepas dari nilai hash yang tersimpan di database.

3. **AUD-SEC-003 [CRITICAL] - Public Unauthenticated PII Data Export (backend/routes/api.php)**
   - Lokasi: `backend/routes/api.php` (baris 134) dan `backend/app/Http/Controllers/Api/ExportController.php` (baris 12-68)
   - Deskripsi: Rute `/api/export-public` didaftarkan di luar middleware otentikasi Sanctum dan tidak melakukan validasi sesi. Siapa pun di internet dapat mengunduh seluruh data konstituen berupa file CSV yang memuat NIK 16 digit lengkap, Nama, Nomor HP, dan Alamat rumah warga tanpa hak akses.

4. **AUD-SEC-004 [HIGH] - Hardcoded API Secret di Frontend & Backend (config/config.php & assets/js/app.js)**
   - Lokasi: `config/config.php` (baris 71), `assets/js/app.js` (baris 6), dan `mobile/index.html` (baris 8504)
   - Deskripsi: Google Gemini API Key disimpan secara hardcoded dalam format base64 di file konfigurasi backend dan disematkan langsung di JavaScript sisi klien desktop dan mobile, memungkinkan kuota API disalahgunakan atau dicuri pihak luar.

5. **AUD-ARCH-001 [HIGH] - Dual-Stack Architectural Drift & Authentication Desynchronization**
   - Lokasi: `api/*.php` vs `backend/app/Http/Controllers/Api/*`
   - Deskripsi: Sistem memiliki dua backend terpisah (prosedural PHP vs Laravel 11). Tabel `users` pada Laravel menggunakan kolom `password`, sedangkan PHP prosedural menggunakan kolom `password_hash`. Pengguna yang didaftarkan melalui satu stack tidak dapat melakukan login pada stack lainnya. Modul Aspirasi dan Reses hanya ada di PHP prosedural dan tidak tersedia di Laravel.

6. **AUD-SEC-005 [HIGH] - Hardcoded Maintenance Mode Bypass Backdoor**
   - Lokasi: `backend/routes/web.php` (baris 9, 34), `backend/routes/api.php` (baris 21), dan `config/helpers.php` (baris 18)
   - Deskripsi: Nilai rahasia hardcoded `gusdim2025` digunakan sebagai kunci bypass maintenance mode. Siapa pun yang mengetahui string ini dapat mengakses sistem saat sistem sedang dalam perbaikan darurat via parameter URL, cookie, atau header HTTP.

---

### 5. DAMPAK RISIKO TERHADAP BISNIS & REPUTASI

- **Pelanggaran Regulasi Perlindungan Data Pribadi (UU No. 27/2022 - UU PDP)**: Kebocoran NIK 16 digit, nomor telepon, dan lokasi konstituen secara massal melalui rute ekspor tanpa otentikasi dapat menimbulkan sanksi administratif, denda hukum, dan gugatan hukum perdata.
- **Integritas Data Pemenangan Terancam**: Keberadaan backdoor login dan unauthenticated installer membuka peluang sabotase database pemenangan, pemalsuan data dukungan, dan penghapusan riwayat verifikasi oleh kompetitor politik.
- **Kredibilitas Calon Terganggu**: Apabila platform digital mengalami peretasan atau kebocoran data KTP warga saat masa kampanye, reputasi calon legislatif dan partai pengusung akan mengalami degradasi kepercayaan publik yang signifikan.

---

### 6. RANGKUMAN REKOMENDASI UTAMA

1. **Tindakan Darurat (< 24 Jam)**:
   - Hapus atau amankan file `install.php` dari web root produksi.
   - Hapus seluruh baris backdoor password pada `api/auth.php` (baris 51-55).
   - Lindungi atau hapus rute `/api/export-public` dan terapkan otentikasi serta role check ketat pada ekspor data.
   - Pindahkan Google Gemini API key ke environment variable di server dan hapus pemanggilan langsung dari file JavaScript klien.
   - Ganti secret bypass pemeliharaan dengan token kriptografi dinamis yang dikelola via database/admin session.

2. **Stabilisasi Arsitektur (< 7 Hari)**:
   - Tentukan satu backend tunggal (Single Source of Truth). Direkomendasikan migrasi penuh ke Laravel 11 dengan melengkapi modul Aspirasi dan Reses, atau menstandarkan PHP prosedural dan menonaktifkan backend Laravel.
   - Samakan skema kolom password pengguna (`password` vs `password_hash`) dan migrasikan data akun yang ada.
   - Terapkan fungsi pembersihan HTML (`escapeHtml`) secara konsisten pada seluruh template string di `assets/js/app.js` guna menutup celah Stored XSS.

3. **Penguatan Tata Kelola & Kesiapan Rilis (< 14 Hari)**:
   - Terapkan indexing unik pada NIK di level database untuk mencegah duplikasi data akibat race condition.
   - Perbaiki skrip deployment cPanel: hindari penggunaan flag `-p[PASSWORD]` pada perintah mysql terminal dan hapus izin world-writable 777.
   - Terapkan automated test suite dasar (PHPUnit / Pest) untuk memverifikasi alur otentikasi, otorisasi teritorial, dan integritas data.

---
Dokumen ini disusun sebagai bagian dari rangkaian 11 laporan audit teknis yang terdokumentasi lengkap pada direktori `docs/audit/`.
