<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = require_auth();

$records = [];
$where = [];
$params = [];

if (($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') && !empty($user['desa'])) {
    $where[] = "desa = :uDesa";
    $params['uDesa'] = $user['desa'];
} else if ($user['role'] === 'Koordinator Kecamatan' && !empty($user['kecamatan'])) {
    $where[] = "kecamatan = :uKec";
    $params['uKec'] = $user['kecamatan'];
}

if (Database::isMysql()) {
    $pdo = Database::getPdo();
    $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", array_map(fn($w) => "p." . $w, $where)) : "";
    $sql = "SELECT p.id, p.jalur, p.nik, p.nama, p.hp, p.umur, p.jabatan, p.koordinator, p.alamat, 
                   p.kecamatan, p.desa, p.latitude as lat, p.longitude as lng, p.foto_wajah as foto, 
                   p.foto_ktp, p.status, p.catatan, p.input_by_user_name as userInput, p.created_at as tanggalRaw,
                   DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') as tanggal,
                   u.id as operator_user_id, u.username as operator_username, u.role as operator_role, u.status as operator_status
            FROM pendukung p
            LEFT JOIN users u ON (u.pendukung_id = p.id OR (p.hp != '' AND (u.username = p.hp OR u.username = REPLACE(REPLACE(p.hp, '-', ''), ' ', ''))))
            {$whereSql}
            ORDER BY p.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $records = $stmt->fetchAll();
} else {
    $data = Database::getJsonData();
    $all = $data['pendukung'] ?? [];
    $usersList = $data['users'] ?? [];
    foreach ($all as $item) {
        if (($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') && !empty($user['desa'])) {
            if (strcasecmp($item['desa'] ?? '', $user['desa']) !== 0) continue;
        } else if ($user['role'] === 'Koordinator Kecamatan' && !empty($user['kecamatan'])) {
            if (strcasecmp($item['kecamatan'] ?? '', $user['kecamatan']) !== 0) continue;
        }
        $item['operator_user_id'] = null;
        $item['operator_username'] = null;
        $item['operator_role'] = null;
        $item['operator_status'] = null;
        foreach ($usersList as $u) {
            if (($u['pendukung_id'] ?? 0) === $item['id'] || (!empty($item['hp']) && $u['username'] === $item['hp'])) {
                $item['operator_user_id'] = $u['id'];
                $item['operator_username'] = $u['username'];
                $item['operator_role'] = $u['role'];
                $item['operator_status'] = $u['status'] ?? 'Aktif';
                break;
            }
        }
        $records[] = $item;
    }
    usort($records, function($a, $b) {
        return strtotime($b['created_at'] ?? 'now') - strtotime($a['created_at'] ?? 'now');
    });
    foreach ($records as &$r) {
        $r['lat'] = $r['latitude'] ?? null;
        $r['lng'] = $r['longitude'] ?? null;
        $r['foto'] = $r['foto_wajah'] ?? ($r['foto'] ?? '');
        $r['userInput'] = $r['input_by_user_name'] ?? ($r['userInput'] ?? '');
        $r['tanggalRaw'] = $r['created_at'] ?? '';
        $r['tanggal'] = date('d/m/Y H:i', strtotime($r['created_at'] ?? 'now'));
    }
}

// Hitung statistik by Jalur
$byJalur = [
    'DPC Kecamatan' => 0,
    'DPRT Desa' => 0,
    'PIP' => 0,
    'KIP' => 0,
    'Relawan' => 0
];
$jalurLabelMap = [
    'DPC' => 'DPC Kecamatan',
    'DPRT' => 'DPRT Desa',
    'PIP' => 'PIP',
    'KIP' => 'KIP',
    'RELAWAN' => 'Relawan'
];

$byKecamatan = [];
$mapPoints = [];

foreach ($records as &$r) {
    $rawJalur = strtoupper($r['jalur'] ?? '');
    $labelJalur = $jalurLabelMap[$rawJalur] ?? ($r['jalur'] ?? 'Lainnya');
    $r['jalurKey'] = $rawJalur;
    $r['jalur'] = $labelJalur;

    if (isset($byJalur[$labelJalur])) {
        $byJalur[$labelJalur]++;
    } else {
        $byJalur[$labelJalur] = ($byJalur[$labelJalur] ?? 0) + 1;
    }

    $kec = $r['kecamatan'] ?: 'Lainnya';
    $byKecamatan[$kec] = ($byKecamatan[$kec] ?? 0) + 1;

    // Titik peta jika ada koordinat
    if (!empty($r['lat']) && !empty($r['lng'])) {
        $mapPoints[] = [
            'nama' => $r['nama'],
            'jalur' => $r['jalur'],
            'kecamatan' => $r['kecamatan'] ?? '',
            'desa' => $r['desa'] ?? '',
            'alamat' => $r['alamat'] ?? '',
            'lat' => (float)$r['lat'],
            'lng' => (float)$r['lng'],
            'foto' => $r['foto'] ?? ''
        ];
    }
}

// Hitung Real Leaderboard Tim dari data pendukung asli
$leaderboard = [];
if (Database::isMysql()) {
    try {
        $pdo = Database::getPdo();
        $sqlLb = "SELECT 
                    COALESCE(NULLIF(p.input_by_user_name, ''), 'Koordinator Lapangan') as nama,
                    MAX(COALESCE(u.role, p.jabatan, 'Relawan Lapangan')) as role,
                    MAX(COALESCE(NULLIF(u.desa, ''), p.desa, '')) as desa,
                    MAX(COALESCE(NULLIF(u.kecamatan, ''), p.kecamatan, 'Kraksaan')) as kecamatan,
                    COUNT(p.id) as total
                  FROM pendukung p
                  LEFT JOIN users u ON (u.username = p.input_by_user_name OR u.nama = p.input_by_user_name)
                  GROUP BY COALESCE(NULLIF(p.input_by_user_name, ''), 'Koordinator Lapangan')
                  ORDER BY total DESC";
        $stmtLb = $pdo->query($sqlLb);
        $rawLb = $stmtLb->fetchAll();
        foreach ($rawLb as $idx => $row) {
            $total = (int)$row['total'];
            $persen = min(100, round(($total / 50) * 100));
            $wilayah = '';
            if (!empty($row['desa']) && !empty($row['kecamatan'])) {
                $wilayah = 'Desa ' . $row['desa'] . ', Kec. ' . $row['kecamatan'];
            } elseif (!empty($row['desa'])) {
                $wilayah = 'Desa ' . $row['desa'];
            } elseif (!empty($row['kecamatan'])) {
                $wilayah = 'Kec. ' . $row['kecamatan'];
            } else {
                $wilayah = $row['role'] ?: 'Relawan Dapil';
            }
            $leaderboard[] = [
                'rank' => $idx + 1,
                'nama' => $row['nama'],
                'role' => $row['role'],
                'wilayah' => $wilayah,
                'desa' => $row['desa'],
                'kecamatan' => $row['kecamatan'],
                'total' => $total,
                'persen' => $persen
            ];
        }
    } catch (Exception $e) {
        $leaderboard = [];
    }
} else {
    $lbTally = [];
    $lbMeta = [];
    foreach ($records as $r) {
        $by = trim($r['userInput'] ?? $r['input_by_user_name'] ?? '');
        if (!$by) $by = 'Koordinator Lapangan';
        $lbTally[$by] = ($lbTally[$by] ?? 0) + 1;
        if (!isset($lbMeta[$by])) {
            $lbMeta[$by] = [
                'desa' => $r['desa'] ?? '',
                'kecamatan' => $r['kecamatan'] ?? '',
                'role' => $r['jabatan'] ?? 'Relawan'
            ];
        }
    }
    arsort($lbTally);
    $idx = 1;
    foreach ($lbTally as $by => $tot) {
        $m = $lbMeta[$by] ?? [];
        $wilayah = '';
        if (!empty($m['desa']) && !empty($m['kecamatan'])) {
            $wilayah = 'Desa ' . $m['desa'] . ', Kec. ' . $m['kecamatan'];
        } elseif (!empty($m['desa'])) {
            $wilayah = 'Desa ' . $m['desa'];
        } else {
            $wilayah = $m['role'] ?? 'Relawan Lapangan';
        }
        $leaderboard[] = [
            'rank' => $idx++,
            'nama' => $by,
            'role' => $m['role'] ?? 'Relawan',
            'wilayah' => $wilayah,
            'desa' => $m['desa'] ?? '',
            'kecamatan' => $m['kecamatan'] ?? '',
            'total' => $tot,
            'persen' => min(100, round(($tot / 50) * 100))
        ];
    }
}

json_response([
    'success' => true,
    'total' => count($records),
    'byJalur' => $byJalur,
    'byKecamatan' => $byKecamatan,
    'mapPoints' => $mapPoints,
    'leaderboard' => $leaderboard,
    'recent' => array_slice($records, 0, 10),
    'allRecords' => $records
]);
