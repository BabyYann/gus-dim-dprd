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
