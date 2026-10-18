<?php
require_once 'scripts/audit_data_excel.php';

$fileGading = $dir . 'media_1792298520340_313a98d3.xlsx';
$g1 = parseXlsxSheet($fileGading, 'xl/worksheets/sheet1.xml');
$g3 = parseXlsxSheet($fileGading, 'xl/worksheets/sheet3.xml');

$desasG1 = [];
foreach ($g1 as $row) {
    $colA = $row['A'] ?? '';
    if (stripos($colA, 'DESA') !== false) {
        $desasG1[] = trim(str_ireplace(['DESA :', 'DESA:'], '', $colA));
    }
}

$desasG3 = [];
foreach ($g3 as $row) {
    $colA = $row['A'] ?? '';
    if (stripos($colA, 'DESA') !== false) {
        $desasG3[] = trim(str_ireplace(['DESA :', 'DESA:'], '', $colA));
    }
}

echo "Desa di Gading Sheet 1 (" . count($desasG1) . "):\n" . implode(', ', $desasG1) . "\n";
echo "Desa di Gading Sheet 3 (" . count($desasG3) . "):\n" . implode(', ', $desasG3) . "\n";
