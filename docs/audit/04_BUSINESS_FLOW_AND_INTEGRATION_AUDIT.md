# LAPORAN AUDIT SISTEM: BUSINESS FLOW & INTEGRATION AUDIT
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. MODEL BISNIS PEMENANGAN PEMILU LEGISLATIF

Sistem Informasi Manajemen Gus Dim dirancang untuk mendukung strategi pemenangan kursi DPRD Kabupaten Probolinggo pada Daerah Pemilihan (Dapil) Kraksaan Raya yang mencakup 3 kecamatan kunci:
1. **Kecamatan Kraksaan** (12 desa/kelurahan: Kraksaan Wetan, Semampir, Kalibuntu, Patokan, dll)
2. **Kecamatan Besuk** (8 desa: Besuk Kidul, Besuk Agung, Alaskandang, Bago, Klampokan, dll)
3. **Kecamatan Gading** (8 desa: Gading Wetan, Gading Kulon, Mojolegi, Prasi, Sentul, dll)

Operasional sistem bersandar pada 5 jalur penghimpunan konstituen:
- **Jalur DPC**: Jaringan struktural pengurus tingkat kecamatan.
- **Jalur DPRT**: Jaringan pengurus ranting tingkat desa.
- **Jalur PIP**: Jaringan orang tua siswa penerima bantuan Program Indonesia Pintar.
- **Jalur KIP**: Jaringan mahasiswa penerima beasiswa Kuliah Kartu Indonesia Pintar.
- **Jalur RELAWAN**: Komunitas relawan TPS, tokoh pemuda, dan simpatisan warga non-partai.

---

### 2. AUDIT ALUR VERIFIKASI BERJENJANG (VERIFICATION LIFECYCLE)

#### A. Desain Alur Verifikasi Ideal
Sistem menerapkan model verifikasi berjenjang piramidal untuk menjamin keaslian data pemilih dan mencegah data fiktif:

```text
[Operator Lapangan] ──> Status: 'Diinput' (Draf Belum Sah)
                              │
                              ▼
[Koordinator Desa]   ──> Status: 'Diverifikasi Desa' (Faktual Lapangan)
                              │
                              ▼
[Koordinator Kec.]   ──> Status: 'Divalidasi Kecamatan' (Struktural)
                              │
                              ▼
[Superadmin / Korcam]──> Status: 'Final' (Sah Masuk Bank Suara)
                              │
                              └── (Jika Tidak Valid: Status 'Ditolak')
```

#### B. Matriks Wewenang Status Aktual pada Kode Sumber
Berdasarkan investigasi berkas `api/verifikasi.php` (baris 149-176):

| Role Pengguna | Status Diinput | Status Diverifikasi Desa | Status Divalidasi Kecamatan | Status Final | Status Ditolak | Catatan Batasan |
| :--- | :---: | :---: | :---: | :---: | :---: | :--- |
| Superadmin | Ya | Ya | Ya | Ya | Ya | Wewenang mutlak di seluruh jenjang & wilayah |
| Koordinator Kecamatan | Ya | Ya | Ya | Ya | Ya | Wewenang validasi, finalisasi, dan unlock draf |
| Koordinator Desa | Ya | Ya | Tidak | Tidak | Ya | Hanya jika data belum berstatus Divalidasi/Final |
| Admin Ranting | Ya | Ya | Tidak | Tidak | Ya | Sama dengan Koordinator Desa |
| Operator Biasa / Relawan | Tidak | Tidak | Tidak | Tidak | Tidak | Seharusnya hanya bisa menginput draf |

#### C. Kerentanan & Pelanggaran Alur Bisnis yang Ditemukan
1. **Bypass Langsung ke Status Final via Input API (`api/pendukung.php:176`)**:
   - *Masalah*: Form input pada `api/pendukung.php` mengekstrak field `status` dari body HTTP dan memvalidasinya terhadap daftar array `['Diinput', 'Diverifikasi Desa', 'Divalidasi Kecamatan', 'Final', 'Ditolak']`.
   - *Dampak Bisnis*: Operator lapangan nakal dapat memodifikasi payload HTTP request saat menyimpan warga baru dengan menyertakan `"status": "Final"`. Data langsung disahkan ke bank suara tanpa melewati verifikasi fisik Koordinator Desa maupun Kecamatan.
2. **Ketiadaan Pembatasan Teritorial di Backend Laravel (`VerifikasiController.php:76`)**:
   - *Masalah*: Pada controller Laravel, validasi wilayah penugasan ditiadakan sama sekali.
   - *Dampak Bisnis*: Koordinator Desa dari Kecamatan Gading dapat mengesahkan atau menolak data pemilih dari Kecamatan Kraksaan. Hal ini melanggar batasan yurisdiksi tim lapangan.
3. **Fail-Open pada Scope Kosong (`api/verifikasi.php:180`)**:
   - *Masalah*: Jika akun Koordinator Desa memiliki kolom `desa` kosong di database, sistem mengabaikan territorial guard.
   - *Dampak Bisnis*: Akun koordinator tanpa penugasan desa spesifik otomatis menjadi koordinator universal yang dapat memverifikasi seluruh desa.

---

### 3. AUDIT ALUR CEK DUPLIKASI NIK (ANTI-DUPLICATION WORKFLOW)

#### A. Mekanisme Pengecekan
Pencegahan duplikasi data dukungan sangat krusial dalam pemilu guna menghindari data ganda antar-jalur (misalnya warga sudah masuk via Jalur DPRT tetapi diinput ulang via Jalur PIP).

Sistem menerapkan dua tahap pemeriksaan:
1. **Pemeriksaan Interaktif Klien**: Melalui API `action=check-nik`. Saat pengguna mengetikkan 16 digit NIK pada form, sistem mengirimkan request AJAX untuk memeriksa database. Jika ditemukan, sistem menampilkan nama pemilik NIK dan jalur sebelumnya.
2. **Pemeriksaan Server-Side (Submit & Update)**:
   ```php
   // api/pendukung.php:142
   $stmt = $pdo->prepare("SELECT id, nama, jalur FROM pendukung WHERE nik = :nik LIMIT 1");
   $stmt->execute(['nik' => $nik]);
   if ($stmt->fetch()) {
       // Tolak penyimpanan
   }
   ```

#### B. Celah Integritas: Ketiadaan Database Unique Constraint (Race Condition)
- **Fakta Teknis**: Pemeriksaan dilakukan pada level aplikasi via `SELECT` sebelum `INSERT`. Pada skema database MySQL (`database/schema.sql:63` dan migrasi Laravel), kolom `nik` hanya didefinisikan dengan index biasa (`INDEX idx_nik (nik)`), BUKAN `UNIQUE INDEX`.
- **Skenario Kegagalan Bisnis**: Dua relawan di dua TPS berbeda memasukkan NIK kepala keluarga yang sama secara bersamaan (misal selisih 100 milidetik). Kedua query `SELECT` membaca bahwa NIK belum ada, lalu kedua request mengeksekusi `INSERT`. Database MySQL menerima kedua record tersebut. Akibatnya, terjadi duplikasi NIK di dalam sistem, menggelembungkan klaim perolehan suara fiktif.

---

### 4. AUDIT AUTO-ENROLLMENT DARI KEHADIRAN RESES KE BANK PENDUKUNG

Pada berkas `api/reses.php` (baris 265-286), saat operator memasukkan absensi warga peserta kegiatan reses, sistem menjalankan logika otomasi:

```php
// Auto-enroll into voters / pendukung if NIK or HP provided
if (!empty($nik) || !empty($hp)) {
    $check = $pdo->prepare("SELECT id FROM pendukung WHERE ...");
    if (!$check->fetch()) {
        $ins = $pdo->prepare("INSERT INTO pendukung 
            (jalur, nama, nik, hp, kecamatan, desa, status, ...)
            VALUES ('RELAWAN', :nama, :nik, :hp, :kec, :desa, 'Diverifikasi Desa', ...)");
    }
}
```

#### Analisis Dampak Bisnis:
1. **Lompatan Status Tanpa Verifikasi Faktual**: Warga yang hanya hadir reses untuk mengambil souvenir atau konsumsi secara sepihak didaftarkan ke sistem pemenangan sebagai konstituen jalur `RELAWAN` dengan status langsung `'Diverifikasi Desa'`.
2. **Ketiadaan Berkas KTP**: Warga hasil auto-enroll ini tidak memiliki foto KTP (`foto_ktp = NULL`), sehingga menurunkan kualitas akurasi bank data pemilih dan menyulitkan audit verifikasi KPU di kemudian hari.
3. **Penyimpangan Jalur**: Peserta reses mungkin merupakan simpatisan partai lain yang hadir memenuhi undangan desa, namun dicatat otomatis sebagai pendukung terverifikasi.

---

### 5. AUDIT SIKLUS PROMOSI PENDUKUNG MENJADI OPERATOR LAPANGAN

Fitur `create-from-pendukung` pada `api/users.php` (baris 171-318) memungkinkan Superadmin mempromosikan warga dari data pendukung menjadi akun operator aplikasi.

#### A. Logika Penetapan Peran Otomatis:
- Jalur DPC -> Dipromosikan menjadi `Koordinator Kecamatan`.
- Jalur DPRT -> Dipromosikan menjadi `Koordinator Desa`.
- Jalur Lainnya (Relawan/PIP/KIP) -> Dipromosikan menjadi `Admin Ranting`.

#### B. Logika Pembuatan Kredensial:
- Username: Nomor HP (jika valid >= 9 digit), atau nama slug + `_` + ID pendukung.
- Password: 6 digit terakhir NIK (jika ada), atau default `123456`.
- Kolom Referensi: `users.pendukung_id` diisi dengan `pendukung.id`.

#### C. Kerentanan Alur: Cascade Delete Account Deletion
Pada `api/pendukung.php` (baris 636-638):
```php
// Hapus juga operator terkait jika ada pendukung_id
$stmtOp = $pdo->prepare("DELETE FROM users WHERE pendukung_id = :id");
$stmtOp->execute(['id' => $targetId]);
```
- **Masalah Bisnis**: Jika seorang Koordinator Desa menghapus data warga di desanya (karena warga tersebut pindah domisili atau meninggal), dan warga tersebut sebelumnya telah diangkat menjadi Koordinator Kecamatan atau Admin Ranting, akun login pengguna tersebut di tabel `users` akan TERHAPUS OTOMATIS SECARA PERMANEN tanpa peringatan atau konfirmasi khusus.
- **Risiko**: Terputusnya akses operasional koordinator lapangan secara tiba-tiba akibat penghapusan data warga biasa.

---

### 6. AUDIT INTEGRASI WEBSOCKET & REAL-TIME EVENT BROADCASTING

Sistem memanfaatkan Laravel Reverb / Pusher Protocol untuk mendistribusikan pembaruan data secara real-time ke antarmuka klien desktop dan mobile pada channel `gusdim-updates`.

#### A. Evaluasi Alur Siaran Event:
1. Pada `api/aspirasi.php` (baris 88 dan 136), pembuatan aspirasi baru memicu event `AspirasiCreated`, dan pembaruan status memicu event `AspirasiStatusUpdated`.
2. Fungsi `broadcast_reverb_event()` membentuk payload tanda tangan kriptografis HMAC-SHA256 menggunakan `appSecret` dan mengirimkannya via HTTP POST ke daemon Reverb lokal (`127.0.0.1:6001`).
3. Klien desktop (`assets/js/app.js`) dan mobile (`mobile/index.html`) mendengarkan event tersebut dan memperbarui badge notifikasi secara langsung.

#### B. Titik Kegagalan (Failure Points) pada Integrasi:
1. **Reverb Offline Handling**: Pada `api/aspirasi.php` (baris 179-185), timeout cURL diset 1 detik dengan `@curl_exec` bersuara diam (`try-catch Throwable`). Jika service Reverb di server mati, data aspirasi tetap tersimpan ke database, namun pengguna di perangkat lain tidak menerima notifikasi. Tidak ada mekanisme antrean (queue) atau retry.
2. **Ketiadaan SSL pada WebSocket Lokal**: Pemanggilan Reverb menggunakan `http://127.0.0.1:6001`. Pada hosting dengan reverse proxy Nginx/Apache HTTPS, koneksi WebSocket dari browser klien seringkali diblokir oleh kebijakan Mixed Content browser jika wss:// proxy belum terkonfigurasi sempurna.
3. **Paritas Event**: Laravel Backend memiliki class event `PendukungCreated` dan `PendukungStatusUpdated` di `backend/app/Events/`, namun PHP Prosedural tidak memancarkan event saat pendukung baru disimpan. Akibatnya, data pendukung baru tidak muncul secara real-time di layar monitor saksi jika diinput via PHP.
