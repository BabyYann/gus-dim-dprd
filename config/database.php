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
                self::ensureTables();
            } catch (PDOException $e) {
                // Jika koneksi MySQL belum aktif (misal mode development lokal),
                // aktifkan mode penyimpanan JSON lokal otomatis agar web tetap berfungsi 100%!
                self::$isMysql = false;
                self::$pdo = null;
                self::ensureJsonStore();
            }
        }
    }

    private static function ensureTables(): void {
        if (!self::$pdo) return;
        try {
            self::$pdo->exec("
                CREATE TABLE IF NOT EXISTS `user_tokens` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `user_id` bigint unsigned NOT NULL,
                  `token` varchar(128) NOT NULL,
                  `expires_at` datetime NOT NULL,
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `user_tokens_token_unique` (`token`),
                  KEY `user_tokens_user_id_index` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `reses_titik` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `nama` varchar(150) NOT NULL,
                  `masa_sidang` varchar(50) DEFAULT 'Masa Sidang I 2026',
                  `kecamatan` varchar(50) NOT NULL,
                  `desa` varchar(50) NOT NULL,
                  `dusun` varchar(100) DEFAULT NULL,
                  `lokasi_tuan_rumah` varchar(255) DEFAULT NULL,
                  `tanggal` date DEFAULT NULL,
                  `waktu` varchar(20) DEFAULT '13:30',
                  `target_peserta` varchar(100) DEFAULT 'Masyarakat Umum',
                  `catatan` text,
                  `created_by` bigint unsigned DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `reses_kehadiran` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `reses_id` bigint unsigned NOT NULL,
                  `nama` varchar(150) NOT NULL,
                  `nik` varchar(20) DEFAULT NULL,
                  `no_hp` varchar(25) DEFAULT NULL,
                  `kecamatan` varchar(50) DEFAULT NULL,
                  `desa` varchar(50) DEFAULT NULL,
                  `dusun` varchar(100) DEFAULT NULL,
                  `kategori_peserta` varchar(100) DEFAULT 'Konstituen Reses',
                  `created_by` bigint unsigned DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `reses_kehadiran_reses_id_index` (`reses_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `pokir_usulan` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `reses_id` bigint unsigned DEFAULT NULL,
                  `judul` varchar(255) NOT NULL,
                  `kategori` varchar(50) DEFAULT 'Infrastruktur',
                  `kecamatan` varchar(50) NOT NULL,
                  `desa` varchar(50) NOT NULL,
                  `dusun` varchar(100) DEFAULT NULL,
                  `estimasi_anggaran` decimal(15,2) DEFAULT '0.00',
                  `nama_pengusul` varchar(150) DEFAULT NULL,
                  `kontak_pengusul` varchar(25) DEFAULT NULL,
                  `deskripsi` text,
                  `status_tahap` varchar(50) DEFAULT 'Aspirasi Reses',
                  `catatan_progres` text,
                  `created_by` bigint unsigned DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");

            $cols = self::$pdo->query("SHOW COLUMNS FROM `users` LIKE 'password_hash'")->fetchAll();
            if (empty($cols)) {
                self::$pdo->exec("ALTER TABLE `users` ADD COLUMN `password_hash` varchar(255) DEFAULT NULL AFTER `password`");
                self::$pdo->exec("UPDATE `users` SET `password_hash` = `password` WHERE `password_hash` IS NULL");
            }

            $colsPid = self::$pdo->query("SHOW COLUMNS FROM `users` LIKE 'pendukung_id'")->fetchAll();
            if (empty($colsPid)) {
                self::$pdo->exec("ALTER TABLE `users` ADD COLUMN `pendukung_id` INT NULL DEFAULT NULL AFTER `id`, ADD INDEX (`pendukung_id`)");
            }
        } catch (\Throwable $ex) {
            // Self-healing fallback silently ignores existing structures
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
                    ]
                ],
                'tokens' => [],
                'pendukung' => [],
                'logs' => [],
                'aspirasi' => [],
                'reses_titik' => [],
                'reses_kehadiran' => [],
                'pokir_usulan' => []
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
