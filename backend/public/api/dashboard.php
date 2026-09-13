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
    $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
    $sql = "SELECT id, jalur, nik, nama, hp, umur, jabatan, koordinator, alamat, 
                   kecamatan, desa, latitude as lat, longitude as lng, foto_wajah as foto, 
                   foto_ktp, status, catatan, input_by_user_name as userInput, created_at as tanggalRaw,
                   DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as tanggal
            FROM pendukung 
            {$whereSql}
            ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $records = $stmt->fetchAll();
} else {
    $data = Database::getJsonData();
    $all = $data['pendukung'] ?? [];
    foreach ($all as $item) {
        if (($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') && !empty($user['desa'])) {
            if (strcasecmp($item['desa'] ?? '', $user['desa']) !== 0) continue;
        } else if ($user['role'] === 'Koordinator Kecamatan' && !empty($user['kecamatan'])) {
            if (strcasecmp($item['kecamatan'] ?? '', $user['kecamatan']) !== 0) continue;
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

json_response([
    'success' => true,
    'total' => count($records),
    'byJalur' => $byJalur,
    'byKecamatan' => $byKecamatan,
    'mapPoints' => $mapPoints,
    'recent' => array_slice($records, 0, 10),
    'allRecords' => $records
]);
