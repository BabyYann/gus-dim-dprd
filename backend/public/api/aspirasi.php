<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = require_auth();
$action = $_GET['action'] ?? 'list';
$body = get_request_body();

switch ($action) {
    case 'list':
        $aspirasi = [];
        if (Database::isMysql()) {
            try {
                $pdo = Database::getPdo();
                $stmt = $pdo->query("SELECT * FROM aspirasi ORDER BY id DESC");
                $aspirasi = $stmt->fetchAll();
            } catch (\Exception $e) {
                $data = Database::getJsonData();
                $aspirasi = $data['aspirasi'] ?? [];
            }
        } else {
            $data = Database::getJsonData();
            $aspirasi = $data['aspirasi'] ?? [];
        }
        usort($aspirasi, fn($a, $b) => ($b['id'] ?? 0) - ($a['id'] ?? 0));
        json_response(['success' => true, 'rows' => $aspirasi]);
        break;

    case 'add':
        $nama = trim($body['nama'] ?? '');
        $hp = trim($body['hp'] ?? '');
        $kecamatan = trim($body['kecamatan'] ?? '');
        $desa = trim($body['desa'] ?? '');
        $jalur = trim($body['jalur'] ?? 'Relawan');
        $kategori = trim($body['kategori'] ?? 'Umum');
        $isi = trim($body['aspirasi'] ?? '');

        if (!$nama || !$isi) {
            json_response(['success' => false, 'message' => 'Nama warga dan aspirasi wajib diisi.'], 400);
        }

        $tanggal = date('d/m/Y H:i');
        $status = 'Baru';
        $insertedId = null;

        if (Database::isMysql()) {
            try {
                $pdo = Database::getPdo();
                $stmt = $pdo->prepare("INSERT INTO aspirasi 
                    (nama, hp, kecamatan, desa, jalur, kategori, aspirasi, status, tanggal)
                    VALUES (:nama, :hp, :kecamatan, :desa, :jalur, :kategori, :aspirasi, :status, :tanggal)");
                $stmt->execute([
                    'nama' => $nama,
                    'hp' => $hp,
                    'kecamatan' => $kecamatan,
                    'desa' => $desa,
                    'jalur' => $jalur,
                    'kategori' => $kategori,
                    'aspirasi' => $isi,
                    'status' => $status,
                    'tanggal' => $tanggal
                ]);
                $insertedId = (int)$pdo->lastInsertId();
            } catch (\Exception $e) {
                // Fallback to JSON below
            }
        }

        // Keep JSON in sync
        $data = Database::getJsonData();
        $aspirasiList = $data['aspirasi'] ?? [];
        $newId = $insertedId ?: (count($aspirasiList) > 0 ? (max(array_column($aspirasiList, 'id')) + 1) : 1);
        $newItem = [
            'id' => $newId,
            'nama' => $nama,
            'hp' => $hp,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
            'jalur' => $jalur,
            'kategori' => $kategori,
            'aspirasi' => $isi,
            'status' => $status,
            'tanggal' => $tanggal
        ];
        $aspirasiList[] = $newItem;
        $data['aspirasi'] = $aspirasiList;
        Database::saveJsonData($data);

        broadcast_reverb_event('AspirasiCreated', $newItem);

        json_response(['success' => true, 'message' => 'Aspirasi warga berhasil dicatat.', 'item' => $newItem]);
        break;

    case 'update-status':
        $id = intval($body['id'] ?? 0);
        $status = trim($body['status'] ?? 'Ditindaklanjuti');

        if ($id <= 0) {
            json_response(['success' => false, 'message' => 'ID aspirasi tidak valid.'], 400);
        }

        $topikAspirasi = 'Aspirasi Warga';

        if (Database::isMysql()) {
            try {
                $pdo = Database::getPdo();
                $stmt = $pdo->prepare("UPDATE aspirasi SET status = :status WHERE id = :id");
                $stmt->execute(['status' => $status, 'id' => $id]);

                $stmtGet = $pdo->prepare("SELECT aspirasi, nama FROM aspirasi WHERE id = :id LIMIT 1");
                $stmtGet->execute(['id' => $id]);
                $row = $stmtGet->fetch();
                if ($row) {
                    $topikAspirasi = $row['aspirasi'] ?? $row['nama'] ?? 'Aspirasi Warga';
                }
            } catch (\Exception $e) {
                // Fallback to JSON below
            }
        }

        $data = Database::getJsonData();
        $aspirasiList = $data['aspirasi'] ?? [];
        $found = false;
        foreach ($aspirasiList as &$item) {
            if (($item['id'] ?? 0) === $id) {
                $item['status'] = $status;
                $topikAspirasi = $item['aspirasi'] ?? $item['nama'] ?? $topikAspirasi;
                $found = true;
                break;
            }
        }
        if ($found) {
            $data['aspirasi'] = $aspirasiList;
            Database::saveJsonData($data);
        }

        broadcast_reverb_event('AspirasiStatusUpdated', [
            'id' => $id,
            'new_status' => $status,
            'topik' => $topikAspirasi
        ]);

        json_response(['success' => true, 'message' => 'Status aspirasi berhasil diperbarui.']);
        break;

    default:
        json_response(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
}

function broadcast_reverb_event(string $event, array $payload): void {
    try {
        $env = function_exists('gusdim_load_env') ? gusdim_load_env() : [];
        $appId = $env['REVERB_APP_ID'] ?? '918237';
        $appKey = $env['REVERB_APP_KEY'] ?? 'ifst0r0e5f3stkfy1k6g';
        $appSecret = $env['REVERB_APP_SECRET'] ?? 'k4h6xeknfxs0lftukb5x';
        $host = $env['REVERB_SERVER_HOST'] ?? '127.0.0.1';
        $port = $env['REVERB_SERVER_PORT'] ?? 6001;

        $body = json_encode([
            'name' => $event,
            'channels' => ['gusdim-updates'],
            'data' => json_encode($payload)
        ]);

        $authTimestamp = time();
        $authVersion = '1.0';
        $bodyMd5 = md5($body);
        $path = "/apps/{$appId}/events";

        $stringToSign = "POST\n{$path}\nauth_key={$appKey}&auth_timestamp={$authTimestamp}&auth_version={$authVersion}&body_md5={$bodyMd5}";
        $authSignature = hash_hmac('sha256', $stringToSign, $appSecret);

        $url = "http://{$host}:{$port}{$path}?auth_key={$appKey}&auth_timestamp={$authTimestamp}&auth_version={$authVersion}&body_md5={$bodyMd5}&auth_signature={$authSignature}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        @curl_exec($ch);
        @curl_close($ch);
    } catch (\Throwable $e) {
        // Silently fail if Reverb offline
    }
}
