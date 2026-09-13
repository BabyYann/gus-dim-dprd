<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = require_auth();
$action = $_GET['action'] ?? 'riwayat';
$body = get_request_body();

$jalurLabelMap = [
    'DPC' => 'DPC Kecamatan',
    'DPRT' => 'DPRT Desa',
    'PIP' => 'PIP',
    'KIP' => 'KIP',
    'RELAWAN' => 'Relawan'
];

switch ($action) {
    case 'riwayat':
        $jalurKey = strtoupper($_GET['jalur'] ?? '');
        $statusFilter = $_GET['status'] ?? '';
        $keyword = trim($_GET['keyword'] ?? '');

        $rows = [];
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $params = [];
            $where = [];

            if ($jalurKey && $jalurKey !== 'ALL') {
                $where[] = "jalur = :jalur";
                $params['jalur'] = $jalurKey;
            }
            if ($statusFilter) {
                $where[] = "status = :status";
                $params['status'] = $statusFilter;
            }
            if ($keyword) {
                $where[] = "(nama LIKE :kw OR nik LIKE :kw OR alamat LIKE :kw)";
                $params['kw'] = '%' . $keyword . '%';
            }

            // Batasi data per role jika Koordinator Desa / Kecamatan
            if ($user['role'] === 'Koordinator Desa' && !empty($user['desa'])) {
                $where[] = "desa = :uDesa";
                $params['uDesa'] = $user['desa'];
            } else if ($user['role'] === 'Koordinator Kecamatan' && !empty($user['kecamatan'])) {
                $where[] = "kecamatan = :uKec";
                $params['uKec'] = $user['kecamatan'];
            }

            $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
            $sql = "SELECT id as rowNumber, id, jalur as jalurKey, nik, nama, hp, alamat, 
                           foto_wajah as foto, kecamatan, desa, koordinator, jabatan, 
                           input_by_user_name as userInput, 
                           DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as tanggal,
                           status, catatan
                    FROM pendukung 
                    {$whereSql} 
                    ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();
        } else {
            $data = Database::getJsonData();
            $all = $data['pendukung'] ?? [];
            foreach ($all as $item) {
                $jk = strtoupper($item['jalur'] ?? '');
                if ($jalurKey && $jalurKey !== 'ALL' && $jk !== $jalurKey) continue;
                if ($statusFilter && ($item['status'] ?? '') !== $statusFilter) continue;
                if ($keyword) {
                    $haystack = strtolower(($item['nama'] ?? '') . ' ' . ($item['nik'] ?? '') . ' ' . ($item['alamat'] ?? ''));
                    if (strpos($haystack, strtolower($keyword)) === false) continue;
                }
                $rows[] = [
                    'rowNumber' => $item['id'],
                    'id' => $item['id'],
                    'jalurKey' => $jk,
                    'jalur' => $jalurLabelMap[$jk] ?? $item['jalur'],
                    'nik' => $item['nik'],
                    'nama' => $item['nama'],
                    'hp' => $item['hp'] ?? '',
                    'alamat' => $item['alamat'] ?? '',
                    'foto' => $item['foto_wajah'] ?? ($item['foto'] ?? ''),
                    'kecamatan' => $item['kecamatan'] ?? '',
                    'desa' => $item['desa'] ?? '',
                    'koordinator' => $item['koordinator'] ?? '',
                    'jabatan' => $item['jabatan'] ?? '',
                    'userInput' => $item['input_by_user_name'] ?? ($item['userInput'] ?? ''),
                    'tanggal' => date('d/m/Y H:i', strtotime($item['created_at'] ?? 'now')),
                    'status' => $item['status'] ?? 'Diinput',
                    'catatan' => $item['catatan'] ?? ''
                ];
            }
            usort($rows, fn($a, $b) => $b['rowNumber'] - $a['rowNumber']);
        }

        foreach ($rows as &$r) {
            $jk = strtoupper($r['jalurKey'] ?? '');
            $r['jalur'] = $jalurLabelMap[$jk] ?? ($r['jalur'] ?? $jk);
        }

        json_response([
            'success' => true,
            'rows' => $rows,
            'userRole' => $user['role'],
            'jalur' => $jalurKey
        ]);
        break;

    case 'update-status':
        $id = (int)($body['rowNumber'] ?? ($body['id'] ?? 0));
        $newStatus = trim($body['newStatus'] ?? '');
        $catatan = trim($body['catatan'] ?? '');

        if (!$id || !$newStatus) {
            json_response(['success' => false, 'message' => 'Data tidak lengkap.']);
        }

        // Cek wewenang status sesuai Role
        $role = $user['role'];
        $allowed = false;
        if ($role === 'Superadmin') {
            $allowed = true;
        } else if ($role === 'Koordinator Kecamatan' && in_array($newStatus, ['Divalidasi Kecamatan', 'Final', 'Ditolak'])) {
            $allowed = true;
        } else if ($role === 'Koordinator Desa' && in_array($newStatus, ['Diverifikasi Desa', 'Ditolak'])) {
            $allowed = true;
        }

        if (!$allowed) {
            json_response(['success' => false, 'message' => "Role '{$role}' tidak berwenang mengubah status menjadi '{$newStatus}'."]);
        }

        $targetName = "ID #{$id}";
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT nama, jalur FROM pendukung WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $item = $stmt->fetch();
            if ($item) $targetName = "{$item['nama']} ({$item['jalur']})";

            $sql = "UPDATE pendukung SET status = :st" . ($catatan ? ", catatan = :ct" : "") . " WHERE id = :id";
            $params = ['st' => $newStatus, 'id' => $id];
            if ($catatan) $params['ct'] = $catatan;
            $pdo->prepare($sql)->execute($params);
        } else {
            $data = Database::getJsonData();
            foreach ($data['pendukung'] as &$p) {
                if ($p['id'] === $id) {
                    $targetName = "{$p['nama']} ({$p['jalur']})";
                    $p['status'] = $newStatus;
                    if ($catatan) $p['catatan'] = $catatan;
                    break;
                }
            }
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Update Status Verifikasi', (string)$id, "Status '{$targetName}' diubah menjadi '{$newStatus}'" . ($catatan ? " (Catatan: {$catatan})" : ""));

        json_response([
            'success' => true,
            'message' => "Status berhasil diperbarui menjadi '{$newStatus}'."
        ]);
        break;

    default:
        json_response(['error' => 'Aksi tidak valid'], 400);
}
