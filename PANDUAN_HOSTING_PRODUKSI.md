# Panduan Lengkap Deployment dan Hosting Sistem Gus Dim Dapil Kraksaan Raya
## Domain Resmi: https://gusdim.com (cPanel Shared Hosting)
Teknologi: Laravel 11 (RESTful API & Blade) + MySQL + Progressive Web App (PWA Mobile)

---

## 1. Berkas Rilis Produksi Siap Pakai (Folder dist/)

Seluruh paket rilis produksi terbaru untuk domain **gusdim.com** telah disiapkan di folder `dist/`:

1. `gusdim-cpanel-core.zip` (Ukuran: ~35 MB)
   Berisi seluruh berkas inti Laravel, pustaka vendor Composer teroptimasi, konfigurasi, controller, model, dan views. Berkas ini dirancang untuk diletakkan di direktori aman di luar `public_html` (folder `gusdim_core`).

2. `gusdim-cpanel-public.zip` (Ukuran: ~0.6 MB)
   Berisi berkas antarmuka publik siap pakai: antarmuka desktop, aplikasi mobile PWA lengkap, manifest, service worker, pustaka aset terkompilasi, serta skrip router cerdas yang otomatis mendeteksi direktori inti. Berkas ini diekstrak langsung di dalam folder `public_html`.

3. `gusdim-full-production.zip` (Ukuran: ~35 MB)
   Berisi keseluruhan proyek secara utuh sebagai cadangan arsip lengkap sistem.

4. `production_schema_and_seeds.sql`
   Skrip cadangan database MySQL lengkap yang memuat struktur tabel terindeks, data master referensi wilayah Dapil Kraksaan Raya (Kraksaan, Besuk, Gading, dan sekitarnya), data akun awal untuk 4 tingkatan peran, serta contoh data awal.

---

## 2. Panduan Langkah Demi Langkah: Deployment ke cPanel (gusdim.com)

Metode ini merupakan standar paling aman dan teruji untuk menjalankan Laravel pada cPanel hosting.

### Langkah 1: Pembuatan Database MySQL di cPanel
1. Masuk ke halaman dasbor cPanel hosting domain **gusdim.com** Anda.
2. Cari dan buka menu **MySQL Database Wizard**.
3. Buat nama database baru, misalnya: `cpaneluser_gusdim` (sesuaikan awalan username cPanel Anda). Catat nama lengkap database ini.
4. Buat pengguna database baru (misal: `cpaneluser_admin`) dan buat kata sandi yang kuat. Catat nama pengguna dan kata sandi ini.
5. Pada langkah berikutnya, beri tanda centang pada kotak **ALL PRIVILEGES** (Semua Hak Akses), lalu klik tombol **Next Step** / Simpan.

### Langkah 2: Impor Struktur dan Data Awal via phpMyAdmin
1. Kembali ke dasbor utama cPanel, lalu buka menu **phpMyAdmin**.
2. Pada panel sebelah kiri, klik nama database yang baru saja dibuat.
3. Klik tab menu **Import** pada bagian atas layar.
4. Klik tombol **Choose File** (Pilih Berkas) dan pilih berkas:
   `dist/production_schema_and_seeds.sql`
5. Gulir ke bagian bawah halaman dan klik tombol **Import**. Seluruh tabel, data wilayah, dan akun default akan otomatis terisi dan siap digunakan.

### Langkah 3: Unggah Berkas Inti Sistem (gusdim-cpanel-core.zip)
1. Buka menu **File Manager** di cPanel.
2. Pastikan Anda berada di direktori beranda (root) akun hosting Anda, yaitu satu tingkat di luar `public_html` (misalnya di `/home/cpaneluser/`).
3. Klik tombol **+ Folder** di sudut kiri atas, lalu buat folder baru bernama:
   `gusdim_core`
4. Masuk ke dalam folder `gusdim_core` tersebut, lalu klik tombol **Upload** di bilah menu atas.
5. Unggah berkas `dist/gusdim-cpanel-core.zip`.
6. Setelah proses unggah selesai (indikator hijau 100%), kembali ke File Manager, klik kanan pada berkas zip tersebut, lalu pilih opsi **Extract**. Pastikan direktori tujuan ekstraksi adalah folder `gusdim_core`.
7. Setelah selesai diekstrak, Anda dapat menghapus berkas zip untuk menghemat ruang penyimpanan.

### Langkah 4: Unggah Berkas Publik (gusdim-cpanel-public.zip)
1. Pada File Manager cPanel, masuk ke dalam folder:
   `public_html`
2. Jika ada berkas bawaan lama dari hosting (seperti `default.html`, `index.html` kosong, atau sejenisnya), hapus berkas tersebut.
3. Klik tombol **Upload** pada menu atas, lalu unggah berkas `dist/gusdim-cpanel-public.zip`.
4. Setelah proses unggah selesai, klik kanan pada berkas zip tersebut, lalu pilih **Extract** langsung ke dalam direktori `public_html`.
5. Berkas publik kini telah terpasang dengan rapi, termasuk `.htaccess`, `index.php`, `sw.js`, `manifest.json`, serta folder `assets`, `build`, dan `mobile`.

### Langkah 5: Penyesuaian Konfigurasi Lingkungan (.env)
1. Buka kembali folder `gusdim_core` pada File Manager cPanel.
2. Pastikan opsi **Show Hidden Files (dotfiles)** aktif di menu pengaturan (ikon gerigi di sudut kanan atas File Manager) agar berkas `.env` terlihat.
3. Klik kanan pada berkas `.env`, lalu pilih **Edit**.
4. Periksa dan sesuaikan baris konfigurasi berikut:
   - `APP_URL=https://gusdim.com`
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `DB_DATABASE=nama_database_yang_anda_buat_di_langkah_1`
   - `DB_USERNAME=nama_user_database_yang_anda_buat_di_langkah_1`
   - `DB_PASSWORD=password_database_yang_anda_buat_di_langkah_1`
5. Klik **Save Changes** (Simpan Perubahan).

### Langkah 6: Pemeriksaan Versi PHP dan Ekstensi
1. Di dasbor utama cPanel, buka menu **Select PHP Version** atau **MultiPHP Manager**.
2. Pastikan domain **gusdim.com** menggunakan versi PHP 8.2 atau PHP 8.3.
3. Pastikan ekstensi umum Laravel berikut aktif: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, dan `curl`.

### Langkah 7: Pengaktifan Sertifikat Keamanan SSL (Wajib untuk PWA)
1. Agar aplikasi mobile PWA dapat diinstal di ponsel relawan dan berjalan offline, domain wajib menggunakan koneksi aman HTTPS.
2. Di dasbor cPanel, buka menu **SSL/TLS Status** atau **Let's Encrypt SSL**.
3. Jalankan fitur **Run AutoSSL** atau pasang sertifikat gratis untuk domain `gusdim.com` dan `www.gusdim.com` hingga indikator berstatus gembok hijau aktif.

---

## 3. Informasi Akun Default untuk Masuk ke Sistem

Setelah sistem aktif pada domain `https://gusdim.com`, Anda dapat langsung melakukan pengujian masuk menggunakan akun berikut:

1. **Tingkat Superadmin (Akses Penuh Seluruh Modul)**
   - Username: `superadmin`
   - Password: `admin123`

2. **Tingkat Koordinator Kecamatan (Validasi Wilayah Kecamatan)**
   - Username: `korcam_kraksaan`
   - Password: `password123`

3. **Tingkat Koordinator Desa (Verifikasi Wilayah Desa)**
   - Username: `kordes_wetan`
   - Password: `password123`

4. **Tingkat Admin Ranting (Entri Pendukung & Aspirasi Lapangan)**
   - Username: `ranting_kraksaan`
   - Password: `password123`

Catatan: Demi keamanan, silakan ganti kata sandi bawaan ini setelah Anda berhasil masuk melalui menu Manajemen User.

---

## 4. Cara Memasang Aplikasi di Ponsel Relawan (Mobile PWA)

Aplikasi Gus Dim Mobile PWA dirancang agar dapat dipasang langsung tanpa melalui toko aplikasi:

### Pengguna Android (Google Chrome)
1. Buka aplikasi Google Chrome di ponsel.
2. Kunjungi alamat: `https://gusdim.com`.
3. Sistem secara otomatis mendeteksi perangkat ponsel dan menampilkan antarmuka Gus Dim Mobile.
4. Klik tombol menu titik tiga di sudut kanan atas Chrome, lalu pilih menu **Instal Aplikasi** atau **Tambahkan ke Layar Utama**.
5. Ikon Gus Dim akan muncul di beranda ponsel Anda dan siap digunakan seperti aplikasi natif.

### Pengguna iPhone / iPad (Apple Safari)
1. Buka peramban Safari di perangkat iOS.
2. Kunjungi alamat: `https://gusdim.com`.
3. Ketuk tombol **Bagikan** (ikon kotak dengan panah ke atas) di bilah navigasi bawah Safari.
4. Gulir ke bawah dan pilih opsi **Tambahkan ke Layar Utama** (Add to Home Screen).
5. Konfirmasi penambahan. Aplikasi Gus Dim kini terpasang rapi di layar utama ponsel.