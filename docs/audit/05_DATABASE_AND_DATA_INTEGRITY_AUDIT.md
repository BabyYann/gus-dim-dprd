# LAPORAN AUDIT SISTEM: DATABASE & DATA INTEGRITY AUDIT
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. PERBANDINGAN STRUKTUR SKEMA BASIS DATA

Audit membandingkan tiga sumber definisi skema basis data di dalam repositori:
1. **Skema SQL Manual**: `database/schema.sql` dan `database/production_clean_schema.sql`.
2. **Laravel Database Migrations**: `backend/database/migrations/*.php`.
3. **Runtime Self-Healing Tables**: Method `ensureTables()` pada `config/database.php`.

Ditemukan ketidaksesuaian struktural dan inkonsistensi tipe data yang signifikan antara ketiga definisi tersebut:

```text
+-------------------+--------------------+----------------------+--------------------------+
| Nama Tabel        | Ada di schema.sql? | Ada di Migrations?   | Ada di ensureTables()?   |
+-------------------+--------------------+----------------------+--------------------------+
| users             | Ya                 | Ya                   | Ya (via ALTER column)    |
| user_tokens       | Ya                 | Tidak (Pakai Sanctum)| Ya (CREATE TABLE)        |
| pendukung         | Ya                 | Ya                   | Tidak                    |
| audit_logs        | Ya                 | Ya                   | Tidak                    |
| wilayah_referensi | Ya                 | Ya                   | Tidak                    |
| aspirasi          | Ya                 | TIDAK ADA            | Tidak                    |
| reses_titik       | Tidak              | TIDAK ADA            | Ya (CREATE TABLE)        |
| reses_kehadiran   | Tidak              | TIDAK ADA            | Ya (CREATE TABLE)        |
| pokir_usulan      | Tidak              | TIDAK ADA            | Ya (CREATE TABLE)        |
+-------------------+--------------------+----------------------+--------------------------+
```

---

### 2. ANOMALI KOLOM PASSWORD: `password` VS `password_hash`

Salah satu temuan paling berisiko terhadap fungsionalitas sistem adalah konflik penamaan kolom kredensial pengguna pada tabel `users`.

#### A. Definisi di Laravel (`0001_01_01_000000_create_users_table.php:20`):
```php
Schema::create('users', function (Blueprint $table) {
    ...
    $table->string('password'); // Kolom standar Laravel
    ...
});
```

#### B. Definisi di SQL Root (`database/schema.sql:14`):
```sql
CREATE TABLE IF NOT EXISTS `users` (
  ...
  `password_hash` VARCHAR(255) NOT NULL,
  ...
);
```

#### C. Penanganan Darurat Runtime di `config/database.php` (baris 104-108):
```php
$cols = self::$pdo->query("SHOW COLUMNS FROM `users` LIKE 'password_hash'")->fetchAll();
if (empty($cols)) {
    self::$pdo->exec("ALTER TABLE `users` ADD COLUMN `password_hash` varchar(255) DEFAULT NULL AFTER `password`");
    self::$pdo->exec("UPDATE `users` SET `password_hash` = `password` WHERE `password_hash` IS NULL");
}
```

#### D. Analisis Dampak Integritas:
1. Ketika tabel dibuat via Laravel Migration, kolom `password_hash` tidak ada. Saat script PHP pertama kali berjalan, PHP mengeksekusi DDL `ALTER TABLE` secara diam-diam.
2. Ketika user baru mendaftar via `backend/app/Http/Controllers/Api/UserController.php`:
   - Laravel mengisi kolom `password`, membiarkan kolom `password_hash` bernilai `NULL`.
   - Akibatnya, pengguna tersebut TIDAK DAPAT LOGIN di backend PHP prosedural (`api/auth.php`) karena query PHP hanya membaca `password_hash`.
3. Ketika user baru mendaftar via `api/users.php`:
   - PHP mengisi kolom `password_hash`, membiarkan kolom `password` bernilai `NULL`.
   - Akibatnya, pengguna tersebut TIDAK DAPAT LOGIN di backend Laravel (`AuthController.php`) karena Eloquent membaca kolom `password`.
4. Keberadaan dua kolom password aktif pada satu tabel merupakan pelanggaran prinsip normalisasi dan membuka celah desinkronisasi kredensial saat pengguna memperbarui kata sandi.

---

### 3. INTEGRITAS RELASIONAL DAN KETIADAAN KUNCI ASING (FOREIGN KEYS)

Tabel basis data pada sistem ini memiliki banyak dependensi relasional logis, namun sebagian besar tidak diikat oleh constraint `FOREIGN KEY` di tingkat database engine (InnoDB):

1. **`pendukung.input_by_user_id` -> `users.id`**:
   - Status: **TIDAK MEMILIKI CONSTRAINT FK**.
   - Risiko: Jika akun operator di tabel `users` dihapus, data di kolom `input_by_user_id` menjadi orphan (mengarah ke user ID yang sudah tidak ada). Riwayat pertanggungjawaban input menjadi putus.
2. **`audit_logs.user_id` -> `users.id`**:
   - Status: **TIDAK MEMILIKI CONSTRAINT FK**.
   - Risiko: Jejak audit dapat mengarah ke ID pengguna yang tidak terdaftar, menyulitkan rekonstruksi forensik jika terjadi sengketa data.
3. **`reses_kehadiran.reses_id` -> `reses_titik.id`**:
   - Status: **TIDAK MEMILIKI CONSTRAINT FK**.
   - Risiko: Menghapus data agenda reses dewan akan meninggalkan ratusan record kehadiran warga tanpa induk kegiatan reses terkait.
4. **`pokir_usulan.reses_id` -> `reses_titik.id`**:
   - Status: **TIDAK MEMILIKI CONSTRAINT FK**.
   - Risiko: Usulan anggaran aspirasi APBD bernilai ratusan juta rupiah dapat kehilangan referensi lokasi reses asalnya.

*Rekomendasi*: Seluruh relasi logis wajib dideklarasikan dengan `FOREIGN KEY ... REFERENCES ... ON DELETE RESTRICT` (atau `ON DELETE SET NULL`) guna menjaga konsistensi referensial ACID.

---

### 4. DDL RUNTIME MUTATION DI DALAM `Database::init()`

Pada berkas `config/database.php` (baris 35-117), setiap kali koneksi basis data diinisialisasi oleh script PHP apa pun, method `ensureTables()` dijalankan:

```php
private static function ensureTables(): void {
    if (!self::$pdo) return;
    try {
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `user_tokens` ...");
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `reses_titik` ...");
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `reses_kehadiran` ...");
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS `pokir_usulan` ...");
        $cols = self::$pdo->query("SHOW COLUMNS FROM `users` LIKE 'password_hash'")->fetchAll();
        ...
        $colsPid = self::$pdo->query("SHOW COLUMNS FROM `users` LIKE 'pendukung_id'")->fetchAll();
        ...
    } catch (\Throwable $ex) {}
}
```

#### Masalah Kinerja & Keandalan:
- **Overhead Query pada Setiap HTTP Request**: Setiap kali pengguna memuat halaman atau mengirim request API, server mengeksekusi minimal 4 kali query DDL metadata (`SHOW COLUMNS`, `CREATE TABLE IF NOT EXISTS`).
- **Penyalahgunaan Hak Akses Database User**: Aplikasi mengharuskan pengguna database MySQL memiliki hak istimewa DDL (`ALTER`, `CREATE TABLE`) dalam operasional runtime sehari-hari. Pada standar hardening database produksi, user aplikasi web hanya boleh memiliki hak DML (`SELECT`, `INSERT`, `UPDATE`, `DELETE`).
- **Skema Drift Tersembunyi**: Pembuatan tabel terjadi di luar mekanisme versioning migrasi resmi.

---

### 5. ANALISIS BASIS DATA CADANGAN: `database/local_db.json`

Sistem menyediakan mekanisme fallback otomatis: jika koneksi MySQL mengalami kegagalan (`PDOException`), sistem beralih menggunakan file `database/local_db.json`.

```php
// config/database.php:25-31
} catch (PDOException $e) {
    self::$isMysql = false;
    self::$pdo = null;
    self::ensureJsonStore();
}
```

#### Temuan Kerentanan & Risiko Integritas:
1. **Ketiadaan File Locking (Race Condition & Data Truncation)**:
   Pada `config/database.php` baris 171-174:
   ```php
   public static function saveJsonData(array $data): void {
       self::ensureJsonStore();
       file_put_contents(self::$jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
   }
   ```
   Pemanggilan `file_put_contents` dilakukan TANPA flag `LOCK_EX` (exclusive lock). Jika 2 atau lebih relawan melakukan submit data pada detik yang sama saat mode JSON aktif:
   - Terjadi kondisi balapan (race condition).
   - Salah satu penulisan akan menimpa penulisan lainnya, atau file `local_db.json` terpotong (truncated) menjadi ukuran 0 byte (korup total), menghilangkan seluruh data pemilih yang tersimpan.
2. **Ketiadaan Enkripsi Data Pribadi (Plaintext Storage)**:
   File `local_db.json` menyimpan seluruh NIK 16 digit warga, nomor handphone, riwayat token sesi aktif, dan hash kata sandi dalam bentuk JSON teks polos yang tidak terenkripsi at-rest.
3. **Eksposur HTTP Langsung**:
   Jika server web tidak memproses file `.htaccess` (misal Nginx, OpenLiteSpeed), file `local_db.json` berada di dalam direktori publik `database/` dan dapat diunduh langsung oleh siapa pun via URL:
   `https://domain.com/database/local_db.json`.

---

### 6. EVALUASI INDEKS DAN PERFORMA QUERY

Pemeriksaan indeks pada tabel utama `pendukung`:
- **Indeks Tersedia**:
  - `idx_nik` pada `nik`
  - `idx_nama` pada `nama`
  - `idx_wilayah` pada `(kecamatan, desa)`
  - `idx_jalur` pada `jalur`
  - `idx_status` pada `status`
  - `idx_created` pada `created_at`
- **Celah Indeks**:
  - Kolom `nik` TIDAK MEMILIKI `UNIQUE INDEX`. Duplikasi data dukungan dapat terjadi pada tingkat engine basis data.
  - Query filtering pada `api/verifikasi.php` menggabungkan filter `(jalur, status, desa)` atau `(jalur, status, kecamatan)`. Ketiadaan indeks gabungan (composite index) seperti `idx_jalur_status_wilayah (jalur, status, kecamatan, desa)` akan memaksa MySQL melakukan index merge yang lambat saat data mencapai puluhan ribu baris.
  - Query pencarian nama menggunakan wildcard depan (`LIKE '%keyword%'`), yang menyebabkan indeks B-Tree pada kolom `nama` tidak dapat digunakan (terjadi Full Table Scan).
