<?php

$dir = 'C:/Users/User/.gemini/antigravity/brain/dd13c00a-7496-4a71-9da1-4099a9679ae6/.user_uploaded/';

function parseXlsxSheet($zipPath, $sheetEntryName) {
    $zip = new ZipArchive();
    if ($zip->open($zipPath) !== true) return [];

    $sharedStrings = [];
    $ssXml = $zip->getFromName('xl/sharedStrings.xml');
    if ($ssXml) {
        $xml = simplexml_load_string($ssXml);
        foreach ($xml->si as $val) {
            $sharedStrings[] = (string)($val->t ?? ($val->r ? $val->r->t : ''));
        }
    }

    $sheetXml = $zip->getFromName($sheetEntryName);
    if (!$sheetXml) {
        $zip->close();
        return [];
    }

    $xml = simplexml_load_string($sheetXml);
    $rows = [];
    foreach ($xml->sheetData->row as $r) {
        $rowNum = (int)$r['r'];
        $cells = [];
        foreach ($r->c as $c) {
            $ref = (string)$c['r'];
            $col = preg_replace('/[0-9]/', '', $ref);
            $type = (string)$c['t'];
            $val = (string)$c->v;
            if ($type === 's') {
                $val = $sharedStrings[(int)$val] ?? $val;
            }
            $cells[$col] = trim($val);
        }
        $rows[$rowNum] = $cells;
    }
    $zip->close();
    return $rows;
}

function cleanHp($hp) {
    if (empty($hp)) return null;
    $hp = preg_replace('/[^0-9]/', '', $hp);
    if (str_starts_with($hp, '62')) {
        $hp = '0' . substr($hp, 2);
    } elseif (str_starts_with($hp, '8')) {
        $hp = '0' . $hp;
    }
    return !empty($hp) ? $hp : null;
}

function cleanNik($nik) {
    if (empty($nik)) return null;
    $nik = trim($nik, "' \t\n\r\0\x0B");
    $nik = preg_replace('/[^0-9]/', '', $nik);
    return !empty($nik) ? $nik : null;
}

$records = [];

// 1. BESUK
$fileBesuk = $dir . 'media_1792298516657_2e34d9fb.xlsx';
$bSheet1 = parseXlsxSheet($fileBesuk, 'xl/worksheets/sheet1.xml');
$curDesa = '';
foreach ($bSheet1 as $rNum => $row) {
    $colA = $row['A'] ?? '';
    if (stripos($colA, 'DESA') !== false) {
        $curDesa = trim(str_ireplace(['DESA :', 'DESA:'], '', $colA));
        continue;
    }
    $nama = trim($row['B'] ?? '');
    $jabatan = trim($row['C'] ?? '');
    $nik = cleanNik($row['D'] ?? '');
    $hp = cleanHp($row['E'] ?? '');
    $alamat = trim($row['F'] ?? '');

    if (empty($nama) && empty($nik)) continue;
    if (in_array(strtoupper($jabatan), ['TOTAL', 'JABATAN']) || in_array(strtoupper($nama), ['TOTAL', 'NAMA'])) continue;
    if (is_numeric($jabatan) && empty($nama) && empty($nik)) continue;

    $records[] = [
        'jalur' => 'DPRT',
        'kecamatan' => 'Besuk',
        'desa' => ucwords(strtolower($curDesa)),
        'nama' => strtoupper($nama),
        'jabatan' => strtoupper($jabatan),
        'nik' => $nik,
        'hp' => $hp,
        'alamat' => $alamat ?: null,
        'source' => "Besuk DPRT R{$rNum}"
    ];
}

// Besuk DPC Harian
$bSheet2 = parseXlsxSheet($fileBesuk, 'xl/worksheets/sheet2.xml');
foreach ($bSheet2 as $rNum => $row) {
    if (($row['A'] ?? '') === 'NO' || ($row['B'] ?? '') === 'JABATAN') continue;
    if (stripos($row['A'] ?? '', 'PENGURUS HARIAN') !== false) continue;
    $jabatan = trim($row['B'] ?? '');
    $nama = trim($row['C'] ?? '');
    $nik = cleanNik($row['D'] ?? '');
    $hp = cleanHp($row['E'] ?? '');
    $alamat = trim($row['F'] ?? '');

    if (!empty($nama)) {
        $records[] = [
            'jalur' => 'DPC',
            'kecamatan' => 'Besuk',
            'desa' => null,
            'nama' => strtoupper($nama),
            'jabatan' => strtoupper($jabatan),
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat ?: null,
            'source' => "Besuk DPC R{$rNum}"
        ];
    }
}

// 2. GADING
$fileGading = $dir . 'media_1792298520340_313a98d3.xlsx';
$gSheet3 = parseXlsxSheet($fileGading, 'xl/worksheets/sheet3.xml');
$curDesa = '';
foreach ($gSheet3 as $rNum => $row) {
    $colA = $row['A'] ?? '';
    if (stripos($colA, 'DESA') !== false) {
        $curDesa = trim(str_ireplace(['DESA :', 'DESA:'], '', $colA));
        continue;
    }
    $nama = trim($row['B'] ?? '');
    $jabatan = trim($row['C'] ?? '');
    $nik = cleanNik($row['D'] ?? '');
    $hp = cleanHp($row['E'] ?? '');
    $alamat = trim($row['F'] ?? '');

    if (empty($nama) && empty($nik)) continue;
    if (in_array(strtoupper($jabatan), ['TOTAL', 'JABATAN']) || in_array(strtoupper($nama), ['TOTAL', 'NAMA'])) continue;

    $records[] = [
        'jalur' => 'DPRT',
        'kecamatan' => 'Gading',
        'desa' => ucwords(strtolower($curDesa)),
        'nama' => strtoupper($nama),
        'jabatan' => strtoupper($jabatan),
        'nik' => $nik,
        'hp' => $hp,
        'alamat' => $alamat ?: null,
        'source' => "Gading DPRT R{$rNum}"
    ];
}

// Gading DPC Harian
$gSheet2 = parseXlsxSheet($fileGading, 'xl/worksheets/sheet2.xml');
foreach ($gSheet2 as $rNum => $row) {
    if (($row['A'] ?? '') === 'NO.' || ($row['B'] ?? '') === 'JABATAN') continue;
    if (stripos($row['A'] ?? '', 'PENGURUS HARIAN') !== false) continue;
    $jabatan = trim($row['B'] ?? '');
    $nama = trim($row['C'] ?? '');
    $nik = cleanNik($row['D'] ?? '');
    $hp = cleanHp($row['E'] ?? '');
    $alamat = trim($row['F'] ?? '');

    if (!empty($nama)) {
        $records[] = [
            'jalur' => 'DPC',
            'kecamatan' => 'Gading',
            'desa' => null,
            'nama' => strtoupper($nama),
            'jabatan' => strtoupper($jabatan),
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat ?: null,
            'source' => "Gading DPC R{$rNum}"
        ];
    }
}

// 3. KRAKSAAN
$fileKraksaan = $dir . 'media_1792298523153_5c5ed757.xlsx';
$kSheet3 = parseXlsxSheet($fileKraksaan, 'xl/worksheets/sheet3.xml');
$curDesa = '';
foreach ($kSheet3 as $rNum => $row) {
    $colB = $row['B'] ?? '';
    if (stripos($colB, 'DESA') !== false) {
        $curDesa = trim(str_ireplace(['DESA :', 'DESA:'], '', $colB));
        continue;
    }
    $nama = trim($row['C'] ?? '');
    $jabatan = trim($row['D'] ?? '');
    $nik = cleanNik($row['E'] ?? '');
    $hp = cleanHp($row['F'] ?? '');
    $alamat = trim($row['G'] ?? '');

    if (empty($nama) && empty($nik)) continue;
    if (in_array(strtoupper($jabatan), ['TOTAL', 'JABATAN']) || in_array(strtoupper($nama), ['TOTAL', 'NAMA'])) continue;

    // Periksa revisi Kandang Jati Kulon (Sekretaris diubah ke NING AGUSTINI)
    if (strtoupper($curDesa) === 'KANDANG JATI KULON' && strtoupper($jabatan) === 'SEKRETARIS') {
        $nama = 'NING AGUSTINI';
        $nik = '3513146008720001';
        $hp = '082245137344';
        $alamat = 'JL. ARGOPURO 4/69A RT005/RW001';
    }

    $records[] = [
        'jalur' => 'DPRT',
        'kecamatan' => 'Kraksaan',
        'desa' => ucwords(strtolower($curDesa)),
        'nama' => strtoupper($nama),
        'jabatan' => strtoupper($jabatan),
        'nik' => $nik,
        'hp' => $hp,
        'alamat' => $alamat ?: null,
        'source' => "Kraksaan DPRT R{$rNum}"
    ];
}

// Kraksaan DPC Harian
$kSheet2 = parseXlsxSheet($fileKraksaan, 'xl/worksheets/sheet2.xml');
foreach ($kSheet2 as $rNum => $row) {
    if (($row['A'] ?? '') === 'NO' || ($row['B'] ?? '') === 'JABATAN') continue;
    if (stripos($row['A'] ?? '', 'PENGURUS HARIAN') !== false) continue;
    $jabatan = trim($row['B'] ?? '');
    $nama = trim($row['C'] ?? '');
    $nik = cleanNik($row['D'] ?? '');
    $hp = cleanHp($row['E'] ?? '');
    $alamat = trim($row['F'] ?? '');

    if (!empty($nama)) {
        $records[] = [
            'jalur' => 'DPC',
            'kecamatan' => 'Kraksaan',
            'desa' => null,
            'nama' => strtoupper($nama),
            'jabatan' => strtoupper($jabatan),
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat ?: null,
            'source' => "Kraksaan DPC R{$rNum}"
        ];
    }
}

// Write to SQL file
$sql = "-- SQL Seeder DPC & DPRT Dapil Kraksaan Raya (Besuk, Gading, Kraksaan)\n";
$sql .= "-- Total Rekord: " . count($records) . "\n\n";

$sql .= "INSERT INTO `pendukung` (`jalur`, `nik`, `nama`, `hp`, `jabatan`, `alamat`, `kecamatan`, `desa`, `status`, `created_at`, `updated_at`) VALUES\n";

$rowsSql = [];
foreach ($records as $r) {
    $jalur = addslashes($r['jalur']);
    $nikVal = $r['nik'] ? "'" . addslashes($r['nik']) . "'" : "''";
    $nama = addslashes($r['nama']);
    $hpVal = $r['hp'] ? "'" . addslashes($r['hp']) . "'" : "NULL";
    $jabatan = addslashes($r['jabatan']);
    $alamatVal = $r['alamat'] ? "'" . addslashes($r['alamat']) . "'" : "NULL";
    $kecamatan = addslashes($r['kecamatan']);
    $desaVal = $r['desa'] ? "'" . addslashes($r['desa']) . "'" : "NULL";

    $rowsSql[] = "('{$jalur}', {$nikVal}, '{$nama}', {$hpVal}, '{$jabatan}', {$alamatVal}, '{$kecamatan}', {$desaVal}, 'Final', NOW(), NOW())";
}

$sql .= implode(",\n", $rowsSql) . ";\n";

file_put_contents('backend/database/seeders/seeds_dpc_dprt_audited.sql', $sql);
file_put_contents('scripts/audited_records.json', json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

echo "Berhasil menghasilkan seeds_dpc_dprt_audited.sql dan audited_records.json dengan total " . count($records) . " rekord.\n";
