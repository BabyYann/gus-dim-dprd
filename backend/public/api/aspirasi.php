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

        json_response(['success' => true, 'message' => 'Aspirasi warga berhasil dicatat.', 'item' => $newItem]);
        break;

    case 'update-status':
        $id = intval($body['id'] ?? 0);
        $status = trim($body['status'] ?? 'Ditindaklanjuti');

        if ($id <= 0) {
            json_response(['success' => false, 'message' => 'ID aspirasi tidak valid.'], 400);
        }

        if (Database::isMysql()) {
            try {
                $pdo = Database::getPdo();
                $stmt = $pdo->prepare("UPDATE aspirasi SET status = :status WHERE id = :id");
                $stmt->execute(['status' => $status, 'id' => $id]);
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
                $found = true;
                break;
            }
        }
        if ($found) {
            $data['aspirasi'] = $aspirasiList;
            Database::saveJsonData($data);
        }

        json_response(['success' => true, 'message' => 'Status aspirasi berhasil diperbarui.']);
        break;

    default:
        json_response(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
}
