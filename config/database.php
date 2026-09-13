<?php
require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $pdo = null;
    private static bool $isMysql = false;
    private static string $jsonFile = '';

    public static function init(): void {
        if (empty(self::$jsonFile)) {
            self::$jsonFile = dirname(__DIR__) . '/database/local_db.json';
        }

        if (self::$pdo === null && !self::$isMysql) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
                self::$isMysql = true;
            } catch (PDOException $e) {
                // Jika koneksi MySQL belum aktif (misal mode development lokal),
                // aktifkan mode penyimpanan JSON lokal otomatis agar web tetap berfungsi 100%!
                self::$isMysql = false;
                self::$pdo = null;
                self::ensureJsonStore();
            }
        }
    }

    public static function isMysql(): bool {
        self::init();
        return self::$isMysql;
    }

    public static function getPdo(): ?PDO {
        self::init();
        return self::$pdo;
    }

    // ==========================================
    // JSON LOCAL STORAGE FALLBACK (DEV MODE)
    // ==========================================
    private static function ensureJsonStore(): void {
        if (empty(self::$jsonFile)) {
            self::$jsonFile = dirname(__DIR__) . '/database/local_db.json';
        }
        if (!file_exists(self::$jsonFile)) {
            $initialData = [
                'users' => [
                    [
                        'id' => 1,
                        'username' => 'superadmin',
                        'password_hash' => '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u',
                        'nama' => 'Superadmin Pusat',
                        'role' => 'Superadmin',
                        'kecamatan' => '',
                        'desa' => '',
                        'ranting' => '',
                        'foto_profil' => '',
                        'status' => 'Aktif',
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 2,
                        'username' => 'korcam_kraksaan',
                        'password_hash' => '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne',
                        'nama' => 'H. Mansyur (Korcam)',
                        'role' => 'Koordinator Kecamatan',
                        'kecamatan' => 'Kraksaan',
                        'desa' => '',
                        'ranting' => '',
                        'foto_profil' => '',
                        'status' => 'Aktif',
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 3,
                        'username' => 'kordes_wetan',
                        'password_hash' => '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne',
                        'nama' => 'Ust. Bahri (Kordes)',
                        'role' => 'Koordinator Desa',
                        'kecamatan' => 'Kraksaan',
                        'desa' => 'Kraksaan Wetan',
                        'ranting' => '',
                        'foto_profil' => '',
                        'status' => 'Aktif',
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 4,
                        'username' => 'ranting_kraksaan',
                        'password_hash' => '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne',
                        'nama' => 'Admin Ranting Kraksaan Kota',
                        'role' => 'Admin Ranting',
                        'kecamatan' => 'Kraksaan',
                        'desa' => 'Kraksaan Wetan',
                        'ranting' => 'Ranting Kraksaan Kota',
                        'foto_profil' => '',
                        'status' => 'Aktif',
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                ],
                'tokens' => [],
                'pendukung' => [
                    [
                        'id' => 1,
                        'jalur' => 'DPC',
                        'nik' => '3513121508820001',
                        'nama' => 'Ahmad Fauzi, S.Pd',
                        'hp' => '081234567890',
                        'umur' => 44,
                        'jabatan' => 'Ketua DPC',
                        'koordinator' => '',
                        'alamat' => 'Jl. Panglima Sudirman No. 45',
                        'kecamatan' => 'Kraksaan',
                        'desa' => 'Kraksaan Wetan',
                        'latitude' => -7.7580,
                        'longitude' => 113.4150,
                        'foto_wajah' => '',
                        'foto_ktp' => '',
                        'data_khusus' => null,
                        'status' => 'Final',
                        'catatan' => 'Tokoh masyarakat Kraksaan',
                        'input_by_user_id' => 1,
                        'input_by_user_name' => 'Superadmin Pusat',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
                    ],
                    [
                        'id' => 2,
                        'jalur' => 'DPRT',
                        'nik' => '3513125203890002',
                        'nama' => 'Siti Aminah',
                        'hp' => '085233445566',
                        'umur' => 37,
                        'jabatan' => 'Sekretaris DPRT',
                        'koordinator' => '',
                        'alamat' => 'Dusun Krajan RT 02/RW 01',
                        'kecamatan' => 'Kraksaan',
                        'desa' => 'Kraksaan Kulon',
                        'latitude' => -7.7550,
                        'longitude' => 113.4090,
                        'foto_wajah' => '',
                        'foto_ktp' => '',
                        'data_khusus' => null,
                        'status' => 'Divalidasi Kecamatan',
                        'catatan' => 'Koordinator ibu-ibu pengajian',
                        'input_by_user_id' => 4,
                        'input_by_user_name' => 'Admin Ranting Kraksaan Kota',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
                    ],
                    [
                        'id' => 3,
                        'jalur' => 'PIP',
                        'nik' => '3513120101080003',
                        'nama' => 'Rizky Ramadhan',
                        'hp' => '087811223344',
                        'umur' => 18,
                        'jabatan' => '',
                        'koordinator' => '',
                        'alamat' => 'Jl. Ikan Paus RT 03/RW 02',
                        'kecamatan' => 'Kraksaan',
                        'desa' => 'Semampir',
                        'latitude' => -7.7480,
                        'longitude' => 113.4210,
                        'foto_wajah' => '',
                        'foto_ktp' => '',
                        'data_khusus' => ['sekolah' => 'SMAN 1 Kraksaan'],
                        'status' => 'Diverifikasi Desa',
                        'catatan' => 'Siswa SMAN 1 Kraksaan berprestasi',
                        'input_by_user_id' => 4,
                        'input_by_user_name' => 'Admin Ranting Kraksaan Kota',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
                    ],
                    [
                        'id' => 4,
                        'jalur' => 'KIP',
                        'nik' => '3513134511030004',
                        'nama' => 'Putri Ayu Lestari',
                        'hp' => '089677889900',
                        'umur' => 23,
                        'jabatan' => '',
                        'koordinator' => '',
                        'alamat' => 'Dusun Timur RT 01/RW 04',
                        'kecamatan' => 'Besuk',
                        'desa' => 'Besuk Kidul',
                        'latitude' => -7.8020,
                        'longitude' => 113.4420,
                        'foto_wajah' => '',
                        'foto_ktp' => '',
                        'data_khusus' => ['kampus' => 'Universitas Nurul Jadid'],
                        'status' => 'Diinput',
                        'catatan' => 'Mahasiswi Universitas Nurul Jadid',
                        'input_by_user_id' => 2,
                        'input_by_user_name' => 'H. Mansyur (Korcam)',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
                    ],
                    [
                        'id' => 5,
                        'jalur' => 'RELAWAN',
                        'nik' => '3513141006950005',
                        'nama' => 'Bambang Sutrisno',
                        'hp' => '082199887766',
                        'umur' => 31,
                        'jabatan' => 'Anggota',
                        'koordinator' => 'Relawan Sayap Muda Gus Dim',
                        'alamat' => 'Jl. Raya Gading No. 12',
                        'kecamatan' => 'Gading',
                        'desa' => 'Gading Wetan',
                        'latitude' => -7.8500,
                        'longitude' => 113.4600,
                        'foto_wajah' => '',
                        'foto_ktp' => '',
                        'data_khusus' => null,
                        'status' => 'Final',
                        'catatan' => 'Koordinator pemuda Gading',
                        'input_by_user_id' => 1,
                        'input_by_user_name' => 'Superadmin Pusat',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-1 days'))
                    ]
                ],
                'logs' => [
                    [
                        'id' => 1,
                        'user_id' => 1,
                        'user_nama' => 'Superadmin Pusat',
                        'aksi' => 'Inisialisasi Sistem',
                        'id_referensi' => 'INIT',
                        'keterangan' => 'Inisialisasi sistem manajemen pendukung Gus Dim siap digunakan',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
                    ],
                    [
                        'id' => 2,
                        'user_id' => 1,
                        'user_nama' => 'Superadmin Pusat',
                        'aksi' => 'Input Data DPC',
                        'id_referensi' => 'DPC1',
                        'keterangan' => 'Input data DPC Kecamatan: Ahmad Fauzi, S.Pd (Ketua DPC)',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
                    ],
                    [
                        'id' => 3,
                        'user_id' => 4,
                        'user_nama' => 'Admin Ranting Kraksaan Kota',
                        'aksi' => 'Input Data PIP',
                        'id_referensi' => 'PIP3',
                        'keterangan' => 'Input data PIP: Rizky Ramadhan (Semampir)',
                        'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
                    ]
                ]
            ];
            file_put_contents(self::$jsonFile, json_encode($initialData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }

    public static function getJsonData(): array {
        self::ensureJsonStore();
        $c = file_get_contents(self::$jsonFile);
        return json_decode($c, true) ?: [];
    }

    public static function saveJsonData(array $data): void {
        self::ensureJsonStore();
        file_put_contents(self::$jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
