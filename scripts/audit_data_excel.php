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
    if (empty($hp)) return '';
    $hp = preg_replace('/[^0-9]/', '', $hp);
    if (str_starts_with($hp, '62')) {
        $hp = '0' . substr($hp, 2);
    } elseif (str_starts_with($hp, '8')) {
        $hp = '0' . $hp;
    }
    return $hp;
}

$allRecords = [];

// ==========================================
// 1. KECAMATAN BESUK
// ==========================================
$fileBesuk = $dir . 'media_1792298516657_2e34d9fb.xlsx';
// Sheet 1: DPRT Besuk
$sheet1 = parseXlsxSheet($fileBesuk, 'xl/worksheets/sheet1.xml');
$currentDesa = '';
foreach ($sheet1 as $rNum => $row) {
    $colA = $row['A'] ?? '';
    if (stripos($colA, 'DESA :') !== false || stripos($colA, 'DESA:') !== false) {
        $currentDesa = trim(str_ireplace(['DESA :', 'DESA:'], '', $colA));
        continue;
    }
    $nama = trim($row['B'] ?? '');
    $jabatan = trim($row['C'] ?? '');
    $nik = trim($row['D'] ?? '');
    $hp = cleanHp($row['E'] ?? '');
    $alamat = trim($row['F'] ?? '');

    // Ignore headers, totals, counters
    if (empty($nama) && empty($nik)) continue;
    if (in_array(strtoupper($jabatan), ['TOTAL', 'JABATAN']) || in_array(strtoupper($nama), ['TOTAL', 'NAMA'])) continue;
    if (is_numeric($jabatan) && empty($nama) && empty($nik)) continue;

    $allRecords[] = [
        'jalur' => 'DPRT',
        'kecamatan' => 'Besuk',
        'desa' => $currentDesa,
        'nama' => $nama,
        'jabatan' => $jabatan,
        'nik' => $nik,
        'hp' => $hp,
        'alamat' => $alamat,
        'source' => 'Besuk Sheet 1 (DPRT) Row ' . $rNum
    ];
}

// Sheet 2: DPC Pengurus Harian Besuk
$sheet2 = parseXlsxSheet($fileBesuk, 'xl/worksheets/sheet2.xml');
foreach ($sheet2 as $rNum => $row) {
    if (($row['A'] ?? '') === 'NO' || ($row['B'] ?? '') === 'JABATAN') continue;
    if (stripos($row['A'] ?? '', 'PENGURUS HARIAN') !== false) continue;
    $jabatan = $row['B'] ?? '';
    $nama = $row['C'] ?? '';
    $nik = $row['D'] ?? '';
    $hp = cleanHp($row['E'] ?? '');
    $alamat = $row['F'] ?? '';

    if (!empty($nama)) {
        $allRecords[] = [
            'jalur' => 'DPC',
            'kecamatan' => 'Besuk',
            'desa' => '',
            'nama' => $nama,
            'jabatan' => $jabatan,
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat,
            'source' => 'Besuk Sheet 2 (DPC Harian) Row ' . $rNum
        ];
    }
}

// ==========================================
// 2. KECAMATAN GADING
// ==========================================
$fileGading = $dir . 'media_1792298520340_313a98d3.xlsx';
// Sheet 3: 15 DPRT Gading (Lebih lengkap daripada Sheet 1 KSB karena ada anggota)
$sheetGading15 = parseXlsxSheet($fileGading, 'xl/worksheets/sheet3.xml');
$currentDesa = '';
foreach ($sheetGading15 as $rNum => $row) {
    $colA = $row['A'] ?? '';
    if (stripos($colA, 'DESA :') !== false || stripos($colA, 'DESA:') !== false) {
        $currentDesa = trim(str_ireplace(['DESA :', 'DESA:'], '', $colA));
        continue;
    }
    if (empty($row['B']) && empty($row['C']) && empty($row['D'])) continue;
    if (($row['B'] ?? '') === 'NAMA' || ($row['A'] ?? '') === 'NO.' || ($row['A'] ?? '') === 'TOTAL') continue;

    $nama = $row['B'] ?? '';
    $jabatan = $row['C'] ?? '';
    $nik = $row['D'] ?? '';
    $hp = cleanHp($row['E'] ?? '');
    $alamat = $row['F'] ?? '';

    // Hanya ambil yang ada nama atau NIK
    if (!empty($nama)) {
        $allRecords[] = [
            'jalur' => 'DPRT',
            'kecamatan' => 'Gading',
            'desa' => $currentDesa,
            'nama' => $nama,
            'jabatan' => $jabatan,
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat,
            'source' => 'Gading Sheet 3 (15 DPRT) Row ' . $rNum
        ];
    }
}

// Sheet 2: DPC Gading
$sheetGadingDpc = parseXlsxSheet($fileGading, 'xl/worksheets/sheet2.xml');
foreach ($sheetGadingDpc as $rNum => $row) {
    if (($row['A'] ?? '') === 'NO.' || ($row['B'] ?? '') === 'JABATAN') continue;
    if (stripos($row['A'] ?? '', 'PENGURUS HARIAN') !== false) continue;
    $jabatan = $row['B'] ?? '';
    $nama = $row['C'] ?? '';
    $nik = $row['D'] ?? '';
    $hp = cleanHp($row['E'] ?? '');
    $alamat = $row['F'] ?? '';

    if (!empty($nama)) {
        $allRecords[] = [
            'jalur' => 'DPC',
            'kecamatan' => 'Gading',
            'desa' => '',
            'nama' => $nama,
            'jabatan' => $jabatan,
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat,
            'source' => 'Gading Sheet 2 (DPC Harian) Row ' . $rNum
        ];
    }
}

// ==========================================
// 3. KECAMATAN KRAKSAAN
// ==========================================
$fileKraksaan = $dir . 'media_1792298523153_5c5ed757.xlsx';
// Sheet 3: 12 DPRT Kraksaan (Ada anggota up to 15)
$sheetKraksaan12 = parseXlsxSheet($fileKraksaan, 'xl/worksheets/sheet3.xml');
$currentDesa = '';
foreach ($sheetKraksaan12 as $rNum => $row) {
    $colB = $row['B'] ?? '';
    if (stripos($colB, 'DESA :') !== false || stripos($colB, 'DESA:') !== false) {
        $currentDesa = trim(str_ireplace(['DESA :', 'DESA:'], '', $colB));
        continue;
    }
    if (empty($row['C']) && empty($row['D']) && empty($row['E'])) continue;
    if (($row['C'] ?? '') === 'NAMA' || ($row['B'] ?? '') === 'NO.') continue;

    $nama = $row['C'] ?? '';
    $jabatan = $row['D'] ?? '';
    $nik = $row['E'] ?? '';
    $hp = cleanHp($row['F'] ?? '');
    $alamat = $row['G'] ?? '';

    if (!empty($nama)) {
        $allRecords[] = [
            'jalur' => 'DPRT',
            'kecamatan' => 'Kraksaan',
            'desa' => $currentDesa,
            'nama' => $nama,
            'jabatan' => $jabatan,
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat,
            'source' => 'Kraksaan Sheet 3 (12 DPRT) Row ' . $rNum
        ];
    }
}

// Sheet 2: DPC Kraksaan
$sheetKraksaanDpc = parseXlsxSheet($fileKraksaan, 'xl/worksheets/sheet2.xml');
foreach ($sheetKraksaanDpc as $rNum => $row) {
    if (($row['A'] ?? '') === 'NO' || ($row['B'] ?? '') === 'JABATAN') continue;
    if (stripos($row['A'] ?? '', 'PENGURUS HARIAN') !== false) continue;
    $jabatan = $row['B'] ?? '';
    $nama = $row['C'] ?? '';
    $nik = $row['D'] ?? '';
    $hp = cleanHp($row['E'] ?? '');
    $alamat = $row['F'] ?? '';

    if (!empty($nama)) {
        $allRecords[] = [
            'jalur' => 'DPC',
            'kecamatan' => 'Kraksaan',
            'desa' => '',
            'nama' => $nama,
            'jabatan' => $jabatan,
            'nik' => $nik,
            'hp' => $hp,
            'alamat' => $alamat,
            'source' => 'Kraksaan Sheet 2 (DPC Harian) Row ' . $rNum
        ];
    }
}

// Sheet 4: Koreksi Kandang Jati Kulon (Kraksaan)
$sheetKraksaanRev = parseXlsxSheet($fileKraksaan, 'xl/worksheets/sheet4.xml');
echo "Koreksi Sheet 4 Kraksaan:\n";
print_r($sheetKraksaanRev);

echo "\nData Kandang Jati Kulon di Sheet 3 Kraksaan:\n";
foreach ($allRecords as $r) {
    if (stripos($r['desa'], 'KANDANG JATI KULON') !== false) {
        echo "- {$r['jabatan']}: {$r['nama']} | NIK: {$r['nik']} | HP: {$r['hp']}\n";
    }
}

echo "\n==========================================\n";
echo "RINGKASAN HASIL AUDIT:\n";
echo "Total Rekord Ditemukan: " . count($allRecords) . "\n";
echo "==========================================\n";

$byKec = [];
$byJalur = [];
$nikIssues = [];
$emptyNama = [];
$emptyNik = [];
$duplicateNik = [];
$nikCount = [];

foreach ($allRecords as $idx => $rec) {
    $kec = $rec['kecamatan'];
    $jalur = $rec['jalur'];
    $byKec[$kec] = ($byKec[$kec] ?? 0) + 1;
    $byJalur[$jalur] = ($byJalur[$jalur] ?? 0) + 1;

    $nik = trim($rec['nik']);
    $nama = trim($rec['nama']);

    if (empty($nama)) {
        $emptyNama[] = $rec;
    }

    if (empty($nik)) {
        $emptyNik[] = $rec;
    } else {
        // Cek validitas NIK
        if (!preg_match('/^[0-9]{16}$/', $nik)) {
            $nikIssues[] = [
                'nama' => $nama,
                'kecamatan' => $kec,
                'desa' => $rec['desa'],
                'jabatan' => $rec['jabatan'],
                'nik' => $nik,
                'len' => strlen($nik),
                'source' => $rec['source']
            ];
        } else {
            $nikCount[$nik][] = $rec;
        }
    }
}

foreach ($nikCount as $nik => $list) {
    if (count($list) > 1) {
        $duplicateNik[$nik] = $list;
    }
}

echo "Per Kecamatan:\n";
print_r($byKec);
echo "Per Jalur:\n";
print_r($byJalur);

$desas = [];
foreach ($allRecords as $r) {
    if ($r['jalur'] === 'DPRT') {
        $desas[$r['kecamatan']][$r['desa']] = ($desas[$r['kecamatan']][$r['desa']] ?? 0) + 1;
    }
}
echo "\nRincian Desa per Kecamatan (DPRt):\n";
foreach ($desas as $kec => $dList) {
    echo "=== Kecamatan $kec (" . count($dList) . " Desa) ===\n";
    foreach ($dList as $dName => $cnt) {
        echo "  - $dName: $cnt orang\n";
    }
}

echo "\n--- NIK DENGAN MASALAH / FORMAT TIDAK 16 DIGIT (" . count($nikIssues) . " orang) ---\n";
foreach ($nikIssues as $ni) {
    echo "- [{$ni['kecamatan']} - {$ni['desa']}] {$ni['jabatan']}: {$ni['nama']} | NIK: '{$ni['nik']}' (Panjang: {$ni['len']}) | {$ni['source']}\n";
}

echo "\n--- REKORD TANPA NIK (" . count($emptyNik) . " orang) ---\n";
foreach ($emptyNik as $en) {
    echo "- [{$en['kecamatan']} - {$en['desa']}] {$en['jabatan']}: {$en['nama']} | Telp: {$en['hp']}\n";
}

echo "\n--- REKORD TANPA NAMA (" . count($emptyNama) . " orang) ---\n";
foreach ($emptyNama as $em) {
    echo "- [{$em['kecamatan']} - {$em['desa']}] {$em['jabatan']} | NIK: {$em['nik']} | Telp: {$em['hp']}\n";
}

echo "\n--- DUPLIKASI NIK (" . count($duplicateNik) . " NIK) ---\n";
foreach ($duplicateNik as $nik => $dups) {
    echo "- NIK $nik muncul " . count($dups) . " kali:\n";
    foreach ($dups as $d) {
        echo "   * [{$d['kecamatan']} - {$d['desa']}] {$d['jabatan']}: {$d['nama']} ({$d['source']})\n";
    }
}
