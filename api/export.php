<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = require_auth();
$jalur = $_GET['jalur'] ?? 'ALL';
$kecamatan = $_GET['kecamatan'] ?? '';

$records = [];
if (Database::isMysql()) {
    $pdo = Database::getPdo();
    $where = [];
    $params = [];
    if ($jalur && $jalur !== 'ALL') {
        $where[] = "jalur = :jalur";
        $params['jalur'] = $jalur;
    }
    if ($kecamatan) {
        $where[] = "kecamatan = :kec";
        $params['kec'] = $kecamatan;
    }
    $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
    $stmt = $pdo->prepare("SELECT id, jalur, nik, nama, hp, umur, jabatan, koordinator, alamat, 
                                  kecamatan, desa, status, input_by_user_name, created_at 
                           FROM pendukung {$whereSql} ORDER BY id ASC");
    $stmt->execute($params);
    $records = $stmt->fetchAll();
} else {
    $data = Database::getJsonData();
    foreach ($data['pendukung'] ?? [] as $r) {
        if ($jalur && $jalur !== 'ALL' && strtoupper($r['jalur']) !== strtoupper($jalur)) continue;
        if ($kecamatan && ($r['kecamatan'] ?? '') !== $kecamatan) continue;
        $records[] = $r;
    }
}

// Format Nama File CSV
$filename = "Rekap_GusDim_" . ($jalur !== 'ALL' ? $jalur . '_' : '') . date('Ymd_His') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
// UTF-8 BOM untuk Microsoft Excel agar huruf/tanda baca tidak rusak
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header Kolom
fputcsv($output, [
    'No', 'ID', 'Jalur', 'NIK', 'Nama Lengkap', 'No. HP', 'Umur', 
    'Jabatan / Status Khusus', 'Koordinator', 'Alamat', 'Kecamatan', 'Desa', 
    'Status Verifikasi', 'Penginput', 'Tanggal Input'
]);

$no = 1;
foreach ($records as $r) {
    fputcsv($output, [
        $no++,
        $r['id'] ?? '',
        $r['jalur'] ?? '',
        "'" . ($r['nik'] ?? ''), // Prefix kutip agar Excel tidak mengubah angka 16 digit jadi notasi ilmiah
        $r['nama'] ?? '',
        "'" . ($r['hp'] ?? ''),
        $r['umur'] ?? '',
        $r['jabatan'] ?? '',
        $r['koordinator'] ?? '',
        $r['alamat'] ?? '',
        $r['kecamatan'] ?? '',
        $r['desa'] ?? '',
        $r['status'] ?? '',
        $r['input_by_user_name'] ?? ($r['userInput'] ?? ''),
        $r['created_at'] ?? ''
    ]);
}

fclose($output);
exit;
