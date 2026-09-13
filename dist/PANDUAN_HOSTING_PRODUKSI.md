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

3. `production_clean_schema.sql` (dan `production_schema_and_seeds.sql`)
   Skrip database MySQL produksi bersih:
   - Hanya memiliki 1 akun Superadmin (`superadmin`).
   - Menyimpan master referensi wilayah Dapil Kraksaan Raya (Kraksaan, Besuk, Gading, dsb) agar pilihan wilayah dan titik peta berfungsi otomatis.
   - Tabel pendukung konstituen, aspirasi, dan log riwayat dalam kondisi 100% bersih (0 data) siap untuk input data asli di lapangan.

4. `cpanel_quick_deploy.sh`
   Skrip otomatisasi untuk deployment kilat via terminal server.

---

## 2. Cara Paling Cepat & Direkomendasikan: Menggunakan Terminal Server & Git

Karena Anda memiliki akses ke Terminal server cPanel, proses instalasi dan pembaruan sistem dapat dilakukan jauh lebih cepat tanpa perlu mengunggah berkas zip secara manual satu per satu.

### Langkah 1: Klon Repositori di Terminal cPanel
1. Buka fitur **Terminal** pada dasbor cPanel Anda.
2. Pastikan Anda berada di direktori beranda utama (misal `/home/cpaneluser/`).
3. Jalankan perintah klon dari repositori resmi GitHub:
   ```bash
   git clone https://github.com/BabyYann/gus-dim-dprd.git gusdim_project
   ```

### Langkah 2: Jalankan Skrip Otomatisasi Deployment
1. Masuk ke direktori hasil klon:
   ```bash
   cd gusdim_project
   ```
2. Jalankan skrip pembantu otomatis:
   ```bash
   bash cpanel_quick_deploy.sh
   ```
   Skrip ini secara otomatis akan:
   - Menyiapkan berkas konfigurasi produksi `.env`.
   - Mengoptimasi autoloader Composer.
   - Membuat tautan penyimpanan `storage:link`.
   - Mengompilasi cache konfigurasi, rute, dan tampilan Laravel.
   - Menyinkronkan seluruh aset antarmuka publik langsung ke folder `public_html`.

### Langkah 3: Setup Database MySQL di cPanel
1. Pada menu dasbor cPanel, buka **MySQL Database Wizard**.
2. Buat database baru (misal: `cpaneluser_gusdim`) dan pengguna database baru, beri centang **ALL PRIVILEGES**.
3. Buka **phpMyAdmin**, klik database tersebut, klik tab **Import**, lalu pilih berkas:
   `gusdim_project/dist/production_clean_schema.sql`
   (atau impor langsung melalui terminal: `mysql -u nama_user -p nama_db < dist/production_clean_schema.sql`).

### Langkah 4: Sesuaikan Berkas .env
1. Buka berkas `.env` pada folder `gusdim_project/backend/`:
   ```bash
   nano backend/.env
   ```
2. Masukkan nama database, pengguna, dan kata sandi database yang telah Anda buat pada baris `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
3. Simpan berkas (tekan tombol Ctrl + O lalu Enter, kemudian Ctrl + X).
4. Jalankan penyegaran cache:
   ```bash
   cd backend && php artisan config:cache
   ```

### Langkah 5: Aktifkan HTTPS / SSL di cPanel
1. Buka menu **SSL/TLS Status** di dasbor cPanel.
2. Klik tombol **Run AutoSSL** hingga domain `gusdim.com` berstatus gembok hijau aktif.

---

## 3. Cara Melakukan Pembaruan di Masa Depan (Hanya 1 Baris Perintah)

Jika di kemudian hari ada fitur baru atau perbaikan kode yang didorong ke repositori GitHub, Anda tidak perlu mengulang proses instalasi. Cukup buka Terminal cPanel dan jalankan:

```bash
cd ~/gusdim_project && git pull origin main && bash cpanel_quick_deploy.sh
```

Seluruh kode terbaru dan aset antarmuka akan terbarukan secara otomatis dalam hitungan detik.

---

## 4. Akun Tunggal Superadmin untuk Peluncuran Perdana

Database produksi telah dipersiapkan dengan 1 akun administrator utama untuk mengendalikan seluruh sistem:

- **Role**: Superadmin Pusat (Akses Penuh Seluruh Modul & Manajemen Akun)
- **Username**: `superadmin`
- **Password**: `admin123`

Setelah masuk pertama kali di `https://gusdim.com`, Anda dapat:
1. Mengubah kata sandi akun superadmin melalui menu Profil.
2. Menambahkan akun baru untuk Koordinator Kecamatan (Korcam), Koordinator Desa (Kordes), dan Admin Ranting sesuai nama relawan asli yang bertugas melalui menu **Manajemen User**.

Seluruh modul entri pendukung dan aspirasi siap diisi dengan data ril dari lapangan.

---

## 5. Cara Memasang Aplikasi di Ponsel Relawan (Mobile PWA)

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