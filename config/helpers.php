<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

if (php_sapi_name() !== 'cli') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Content-Type: application/json; charset=utf-8');

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

function json_response(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function get_request_body(): array {
    $raw = file_get_contents('php://input');
    if (!$raw) return $_POST ?: [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : ($_POST ?: []);
}

function get_auth_token(): ?string {
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $auth = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(\S+)/i', $auth, $m)) {
        return $m[1];
    }
    return $_REQUEST['token'] ?? null;
}

function get_auth_user(): ?array {
    $token = get_auth_token();
    if (!$token) return null;

    if (Database::isMysql()) {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("SELECT u.* FROM users u 
                               JOIN user_tokens t ON u.id = t.user_id 
                               WHERE t.token = :token AND t.expires_at > NOW() AND u.status = 'Aktif' 
                               LIMIT 1");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();
        if ($user) {
            unset($user['password_hash']);
            return $user;
        }
    } else {
        $data = Database::getJsonData();
        $tokens = $data['tokens'] ?? [];
        $now = date('Y-m-d H:i:s');
        foreach ($tokens as $t) {
            if ($t['token'] === $token && $t['expires_at'] > $now) {
                foreach ($data['users'] as $u) {
                    if ($u['id'] == $t['user_id'] && $u['status'] === 'Aktif') {
                        unset($u['password_hash']);
                        return $u;
                    }
                }
            }
        }
    }
    return null;
}

function require_auth(): array {
    $user = get_auth_user();
    if (!$user) {
        json_response(['success' => false, 'message' => 'Sesi tidak valid atau telah kedaluwarsa. Silakan login kembali.'], 401);
    }
    return $user;
}

function log_activity(?int $userId, string $userName, string $aksi, ?string $idReferensi, ?string $keterangan): void {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if (Database::isMysql()) {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, user_nama, aksi, id_referensi, keterangan, ip_address) 
                               VALUES (:uid, :unama, :aksi, :idref, :ket, :ip)");
        $stmt->execute([
            'uid' => $userId,
            'unama' => $userName,
            'aksi' => $aksi,
            'idref' => $idReferensi,
            'ket' => $keterangan,
            'ip' => $ip
        ]);
    } else {
        $data = Database::getJsonData();
        $logs = $data['logs'] ?? [];
        $nextId = count($logs) > 0 ? (max(array_column($logs, 'id')) + 1) : 1;
        $logs[] = [
            'id' => $nextId,
            'user_id' => $userId,
            'user_nama' => $userName,
            'aksi' => $aksi,
            'id_referensi' => $idReferensi,
            'keterangan' => $keterangan,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $data['logs'] = $logs;
        Database::saveJsonData($data);
    }
}

function hitung_umur_nik(?string $nik): ?int {
    if (!$nik || strlen($nik) < 12) return null;
    $dd = (int)substr($nik, 6, 2);
    $mm = (int)substr($nik, 8, 2);
    $yy = (int)substr($nik, 10, 2);
    if ($dd > 40) $dd -= 40; // Perempuan di Indonesia tanggal lahir ditambah 40
    if ($dd < 1 || $dd > 31 || $mm < 1 || $mm > 12) return null;

    $currentYY = (int)date('y');
    $fullYear = ($yy <= $currentYY) ? (2000 + $yy) : (1900 + $yy);
    $lahir = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $fullYear, $mm, $dd));
    if (!$lahir) return null;

    $now = new DateTime();
    $diff = $now->diff($lahir);
    $umur = $diff->y;
    return ($umur >= 0 && $umur < 120) ? $umur : null;
}

function save_base64_image(?string $base64Data, string $targetDir, string $prefix = 'img'): ?string {
    if (!$base64Data) return null;
    if (strpos($base64Data, ',') !== false) {
        $parts = explode(',', $base64Data);
        $base64Data = $parts[1];
    }
    $decoded = base64_decode($base64Data);
    if (!$decoded) return null;

    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0755, true);
    }

    $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.jpg';
    $filePath = $targetDir . '/' . $filename;
    file_put_contents($filePath, $decoded);

    // Dapatkan URL relatif publik
    $sub = basename($targetDir);
    return 'uploads/' . $sub . '/' . $filename;
}
