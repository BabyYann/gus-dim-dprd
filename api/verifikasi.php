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

            // Batasi data per role jika Koordinator Desa / Admin Ranting / Kecamatan
            if ($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') {
                if (!empty($user['desa'])) {
                    $where[] = "desa = :uDesa";
                    $params['uDesa'] = $user['desa'];
                } else {
                    $where[] = "1 = 0";
                }
            } else if ($user['role'] === 'Koordinator Kecamatan') {
                if (!empty($user['kecamatan'])) {
                    $where[] = "kecamatan = :uKec";
                    $params['uKec'] = $user['kecamatan'];
                } else {
                    $where[] = "1 = 0";
                }
            }

            $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", array_map(fn($w) => "p." . $w, $where)) : "";
            $sql = "SELECT p.id as rowNumber, p.id, p.jalur as jalurKey, p.nik, p.nama, p.hp, p.alamat, 
                           p.foto_wajah as foto, p.kecamatan, p.desa, p.koordinator, p.jabatan, 
                           p.input_by_user_name as userInput, 
                           DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') as tanggal,
                           p.status, p.catatan,
                           u.id as operator_user_id, u.username as operator_username, u.role as operator_role, u.status as operator_status
                    FROM pendukung p
                    LEFT JOIN users u ON (u.pendukung_id = p.id OR (p.hp != '' AND (u.username = p.hp OR u.username = REPLACE(REPLACE(p.hp, '-', ''), ' ', ''))))
                    {$whereSql} 
                    ORDER BY p.id DESC";
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
                // Batasi wilayah jika bukan Superadmin
                if ($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') {
                    if (empty($user['desa']) || strcasecmp($item['desa'] ?? '', $user['desa']) !== 0) continue;
                } else if ($user['role'] === 'Koordinator Kecamatan') {
                    if (empty($user['kecamatan']) || strcasecmp($item['kecamatan'] ?? '', $user['kecamatan']) !== 0) continue;
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
            json_response(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
        }

        // Ambil data pendukung untuk pengecekan wewenang dan wilayah
        $item = null;
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT id, nama, jalur, kecamatan, desa, status FROM pendukung WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $item = $stmt->fetch();
        } else {
            $data = Database::getJsonData();
            foreach ($data['pendukung'] as $p) {
                if ($p['id'] === $id) {
                    $item = $p;
                    break;
                }
            }
        }

        if (!$item) {
            json_response(['success' => false, 'message' => 'Data pendukung tidak ditemukan.'], 404);
        }

        // Cek wewenang status sesuai Role (Skema A: Fleksibel Berjenjang dengan Mekanisme Buka Kunci)
        $role = $user['role'];
        $currentStatus = $item['status'] ?? 'Diinput';
        $allowed = false;

        if ($role === 'Superadmin') {
            // Superadmin memiliki wewenang penuh atas semua status kapan saja
            $allowed = true;
        } else if ($role === 'Koordinator Kecamatan') {
            // Korcam bisa validasi, finalkan, tolak, dan membuka kunci/revisi ke Desa atau Draf Diinput
            if (in_array($newStatus, ['Divalidasi Kecamatan', 'Final', 'Diverifikasi Desa', 'Diinput', 'Ditolak'])) {
                $allowed = true;
            }
        } else if ($role === 'Koordinator Desa' || $role === 'Admin Ranting') {
            // Kordes hanya bisa memverifikasi/menolak/reset draf data jika BELUM disahkan Korcam/Superadmin
            if (in_array($currentStatus, ['Divalidasi Kecamatan', 'Final'])) {
                json_response([
                    'success' => false,
                    'message' => "Data sudah berstatus '{$currentStatus}'. Hanya Koordinator Kecamatan atau Superadmin yang berwenang membuka kunci / merevisi data ini."
                ], 403);
            }
            if (in_array($newStatus, ['Diverifikasi Desa', 'Diinput', 'Ditolak'])) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            json_response(['success' => false, 'message' => "Role '{$role}' tidak berwenang mengubah status menjadi '{$newStatus}'."], 403);
        }

        // Territorial Guard: Pastikan akun hanya bekerja pada wilayahnya
        if ($role === 'Koordinator Desa' || $role === 'Admin Ranting') {
            $userDesa = trim($user['desa'] ?? '');
            $targetDesa = trim($item['desa'] ?? '');
            if ($userDesa === '' || strcasecmp($userDesa, $targetDesa) !== 0) {
                json_response([
                    'success' => false, 
                    'message' => $userDesa === ''
                        ? "Akses Ditolak: Profil akun Anda belum terikat dengan Desa tugas."
                        : "Akses Ditolak: Anda bertugas di Desa '{$userDesa}', tidak berwenang memverifikasi data Desa '{$targetDesa}'."
                ], 403);
            }
        } else if ($role === 'Koordinator Kecamatan') {
            $userKec = trim($user['kecamatan'] ?? '');
            $targetKec = trim($item['kecamatan'] ?? '');
            if ($userKec === '' || strcasecmp($userKec, $targetKec) !== 0) {
                json_response([
                    'success' => false, 
                    'message' => $userKec === ''
                        ? "Akses Ditolak: Profil akun Anda belum terikat dengan Kecamatan tugas."
                        : "Akses Ditolak: Anda bertugas di Kecamatan '{$userKec}', tidak berwenang memvalidasi data Kecamatan '{$targetKec}'."
                ], 403);
            }
        }

        $targetName = "{$item['nama']} ({$item['jalur']})";

        if (Database::isMysql()) {
            $sql = "UPDATE pendukung SET status = :st" . ($catatan ? ", catatan = :ct" : "") . " WHERE id = :id";
            $params = ['st' => $newStatus, 'id' => $id];
            if ($catatan) $params['ct'] = $catatan;
            $pdo->prepare($sql)->execute($params);
        } else {
            foreach ($data['pendukung'] as &$p) {
                if ($p['id'] === $id) {
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
