# LAPORAN AUDIT SISTEM: PERFORMANCE, RELIABILITY & DEPLOYMENT AUDIT
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. EVALUASI PERFORMA & EFISIENSI SUMBER DAYA

Pemeriksaan mendalam terhadap konsumsi sumber daya komputasi, beban jaringan, dan latensi query menunjukkan sejumlah hambatan skalabilitas serius:

#### A. Beban Query Tanpa Paginasi (Unpaginated Full Table Dumps)
1. **`api/dashboard.php` & `DashboardController.php`**:
   - Query: `SELECT ... FROM pendukung ... ORDER BY created_at DESC`
   - Data dikembalikan utuh dalam kunci response `'allRecords' => $records`.
   - Pada target perolehan suara pemilu legislatif (misal 30.000 pendukung):
     - Ukuran payload JSON mentah diperkirakan mencapai 15 MB - 25 MB per request.
     - Memori PHP yang dibutuhkan untuk memproses array objek ini melebihi limit default shared hosting (`128M`), memicu error fatal `Fatal error: Allowed memory size of 134217728 bytes exhausted`.
     - Browser ponsel pintar relawan akan mengalami freeze (jank) atau browser crash saat mencoba melakukan parsing JSON sebesar itu ke memori perangkat.
2. **`api/pendukung.php?action=list`**:
   - Query mengambil seluruh data pendukung yang sesuai wilayah tanpa batas (`LIMIT / OFFSET`).
   - Meskipun data diurutkan `ORDER BY id DESC`, tidak tersedianya server-side pagination menyebabkan waktu loading halaman semakin lambat seiring bertambahnya data tim pemenangan.

#### B. Optimasi Jaringan & Kompresi Aset
- Konfigurasi Apache `.htaccess` telah mengaktifkan `mod_deflate` untuk kompresi gzip pada tipe berkas `application/json`, `text/html`, `application/javascript`, dan `text/css`.
- Header cache `mod_expires` telah dikonfigurasi dengan baik untuk gambar (`access plus 1 month`) dan script/css (`access plus 1 week`), mengurangi beban transfer aset statis berulang.

---

### 2. ANALISIS KEANDALAN SISTEM (RELIABILITY & FAULT TOLERANCE)

#### A. Ketiadaan Transaksi Basis Data (Database Transactions)
Operasi bisnis multi-langkah dieksekusi tanpa pembungkusan transaksi ACID (`BEGIN TRANSACTION ... COMMIT`):
1. **Pendaftaran Kehadiran Reses + Auto-Enroll (`api/reses.php:224-286`)**:
   - Langkah 1: `INSERT INTO reses_kehadiran`.
   - Langkah 2: `INSERT INTO pendukung`.
   - Jika langkah 2 gagal (misal koneksi terputus atau constraint error), langkah 1 tetap tersimpan. Terjadi ketidaksinkronan data antara daftar hadir dan bank suara.
2. **Promosi Operator Lapangan (`api/users.php:269-281`)**:
   - Penambahan akun pengguna baru di tabel `users` tidak dibungkus transaksi dengan pembaruan status pendukung induk.

#### B. Perilaku Kegagalan Koneksi Basis Data (Failure Mode)
- Ketika koneksi MySQL gagal, sistem beralih ke `local_db.json`.
- Namun, sinkronisasi dua arah tidak tersedia: saat database MySQL kembali online, perubahan data yang dicatat di `local_db.json` saat masa offline TIDAK PERNAH DIMIGRASIKAN KEMBALI ke MySQL. Data yang diinput relawan selama database gangguan menjadi tertinggal di file lokal dan hilang dari rekap resmi.

#### C. Toleransi Gangguan Jaringan (Offline Handling di Lapangan)
- Relawan seringkali beroperasi di area desa pelosok (misal pegunungan di Kecamatan Gading) dengan sinyal seluler yang tidak stabil.
- Service Worker di `sw.js` root justru mem-bypass cache (`fetch(event.request)` langsung ke network).
- Service Worker mobile di `mobile/sw.js` menangani offline secara pasif dengan mengembalikan JSON `{ offline: true }`, namun tidak menyediakan antrean lokal (Local IndexedDB Queue) untuk menampung form yang diinput saat offline dan melakukan auto-sync saat sinyal kembali tersedia.

---

### 3. AUDIT PROSES & SKRIP DEPLOYMENT (CPANEL AUTOMATION)

Repositori menyertakan skrip deployment otomatis via Git Terminal cPanel: `cpanel_quick_deploy.sh` dan `cpanel_clean_database.sh`. Ditemukan beberapa risiko keamanan dan stabilitas pada skrip tersebut:

#### A. Bypass Pemeriksaan Platform Composer (`cpanel_quick_deploy.sh:41-44`)
```bash
# Bypass Composer platform check untuk kompatibilitas PHP server
if [ -f "$BACKEND_DIR/vendor/composer/platform_check.php" ]; then
    echo "<?php return;" > "$BACKEND_DIR/vendor/composer/platform_check.php"
fi
```
- **Bahaya**: Skrip secara sengaja mematikan fitur integritas Composer `platform_check.php`. Jika hosting memiliki perbedaan versi ekstensi PHP kritis (misalnya ekstensi `pdo_mysql`, `mbstring`, `openssl`, atau `bcmath` tidak aktif), aplikasi tidak akan berhenti secara anggun saat bootstrap, melainkan menghasilkan fatal runtime error di tengah transaksi pengguna.

#### B. Penetapan Izin Berkas Berbahaya (World-Writable Permissions)
```bash
# cpanel_quick_deploy.sh:71
chmod -R 775 "$BACKEND_DIR/storage" ... || chmod -R 777 "$BACKEND_DIR/storage" ...
```
- **Bahaya**: Perintah fallback `chmod -R 777` membuka izin baca, tulis, dan eksekusi untuk seluruh akun pengguna lain di server shared hosting yang sama, melanggar prinsip Least Privilege.

#### C. Kebocoran Kredensial Database di Daftar Proses Server
```bash
# cpanel_clean_database.sh:50
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < ...
```
- **Bahaya**: Penggunaan argumen `-p"$DB_PASS"` pada terminal Bash mengekspos kata sandi database ke tabel proses sistem operasi. Perintah `ps aux` yang dijalankan pengguna shared hosting lain dapat membaca password database produksi secara langsung.

#### D. Penumpukan Berkas Binary di Git Repositori (Repository Bloat)
- Berkas `backend/vendor.zip` sebesar 36 MB di-track dan disimpan di dalam repositori Git.
- Praktik ini memperlambat proses git clone, membuang bandwidth, dan melanggar prinsip repositori modern di mana pustaka pihak ketiga harus dikelola melalui `composer install` dan dikecualikan via `.gitignore`.

---

### 4. MATRIKS RISIKO KESIAPAN DEPLOYMENT

```text
+-----------------------------------+--------------------+------------------------------------+
| Komponen Deployment               | Status Kesiapan    | Rekomendasi Mitigasi               |
+-----------------------------------+--------------------+------------------------------------+
| Konfigurasi .htaccess Apache      | Siap (Hardened)    | Pertahankan proteksi direktori     |
| Konfigurasi Nginx / Reverse Proxy | Belum Siap         | Buat template vhost Nginx resmi    |
| Skrip Automasi Deployment         | Perlu Perbaikan    | Hapus chmod 777 & bypass composer  |
| Manajemen Secret Environment      | Rentan             | Pindahkan seluruh secret ke .env   |
| Pemisahan Web Root Publik         | Rawan Kerancuan    | Standarkan web root ke public/     |
| Service Worker PWA                | Perlu Peningkatan  | Tambahkan Background Sync offline  |
+-----------------------------------+--------------------+------------------------------------+
```
