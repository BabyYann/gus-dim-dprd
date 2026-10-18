<?php

$dir = 'C:/Users/User/.gemini/antigravity/brain/dd13c00a-7496-4a71-9da1-4099a9679ae6/.user_uploaded/';
$files = [
    'media_1792298516657_2e34d9fb.xlsx',
    'media_1792298520340_313a98d3.xlsx',
    'media_1792298523153_5c5ed757.xlsx'
];

foreach ($files as $f) {
    $path = $dir . $f;
    echo "========================================\n";
    echo "FILE: $f\n";
    echo "========================================\n";

    $zip = new ZipArchive();
    if ($zip->open($path) !== true) {
        echo "Gagal membuka zip $f\n";
        continue;
    }

    // 1. Baca shared strings jika ada
    $sharedStrings = [];
    $ssXml = $zip->getFromName('xl/sharedStrings.xml');
    if ($ssXml) {
        $xml = simplexml_load_string($ssXml);
        foreach ($xml->si as $val) {
            $sharedStrings[] = (string)($val->t ?? ($val->r ? $val->r->t : ''));
        }
    }

    // 2. Baca sheets di workbook
    $wbXml = $zip->getFromName('xl/workbook.xml');
    $sheets = [];
    if ($wbXml) {
        $xml = simplexml_load_string($wbXml);
        foreach ($xml->sheets->sheet as $s) {
            $sheets[(string)$s['sheetId']] = (string)$s['name'];
        }
    }
    echo "Daftar Sheet: " . implode(', ', $sheets) . "\n";

    // 3. Baca sheet1
    for ($i = 1; $i <= 5; $i++) {
        $sheetXml = $zip->getFromName("xl/worksheets/sheet{$i}.xml");
        if (!$sheetXml) continue;
        $sheetName = $sheets[$i] ?? "Sheet $i";
        echo "--- SHEET $i: $sheetName ---\n";
        $xml = simplexml_load_string($sheetXml);
        $rowCount = 0;
        foreach ($xml->sheetData->row as $row) {
            $rowNum = (int)$row['r'];
            $cols = [];
            foreach ($row->c as $c) {
                $ref = (string)$c['r'];
                $colLetter = preg_replace('/[0-9]/', '', $ref);
                $type = (string)$c['t'];
                $val = (string)$c->v;
                if ($type === 's') {
                    $val = $sharedStrings[(int)$val] ?? $val;
                }
                $cols[$colLetter] = $val;
            }
            if (!empty(array_filter($cols))) {
                $rowCount++;
                if ($rowCount <= 20) {
                    echo "R{$rowNum}: " . json_encode($cols, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
                }
            }
        }
        echo "Total baris aktif: $rowCount\n";
    }
    $zip->close();
}
