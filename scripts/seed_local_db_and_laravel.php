<?php

require_once 'scripts/generate_sql_and_audit.php';

$jsonRecordsFile = 'scripts/audited_records.json';
$rawRecords = json_decode(file_get_contents($jsonRecordsFile), true);

$defaultCoords = [
    'Kraksaan' => [-7.7580, 113.4150],
    'Besuk' => [-7.8020, 113.4420],
    'Gading' => [-7.8500, 113.4600]
];

function calcAge($nik) {
    if (!$nik || strlen($nik) < 12) return null;
    $dd = (int)substr($nik, 6, 2);
    $mm = (int)substr($nik, 8, 2);
    $yy = (int)substr($nik, 10, 2);
    if ($dd > 40) $dd -= 40;
    if ($dd < 1 || $dd > 31 || $mm < 1 || $mm > 12) return null;
    $currentYY = (int)date('y');
    $fullYear = ($yy <= $currentYY) ? (2000 + $yy) : (1900 + $yy);
    $lahir = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $fullYear, $mm, $dd));
    if (!$lahir) return null;
    $now = new DateTime();
    return $now->diff($lahir)->y;
}

// 1. Update local_db.json
$localDbFile = 'backend/public/database/local_db.json';
$localDb = json_decode(file_get_contents($localDbFile), true);

$pendukungList = [];
$idCounter = 1;

foreach ($rawRecords as $r) {
    $kec = $r['kecamatan'];
    $lat = $defaultCoords[$kec][0] + (mt_rand(-50, 50) / 10000);
    $lng = $defaultCoords[$kec][1] + (mt_rand(-50, 50) / 10000);
    $umur = calcAge($r['nik']);

    $nama = $r['nama'];
    if (empty($nama)) {
        $nama = '(SEKRETARIS BELUM TERDATA)';
    }

    $pendukungList[] = [
        'id' => $idCounter++,
        'jalur' => $r['jalur'],
        'nik' => $r['nik'] ?: '',
        'nama' => $nama,
        'hp' => $r['hp'] ?: '',
        'umur' => $umur,
        'jabatan' => $r['jabatan'] ?: '',
        'koordinator' => '',
        'alamat' => $r['alamat'] ?: '',
        'kecamatan' => $r['kecamatan'] ?: '',
        'desa' => $r['desa'] ?: '',
        'latitude' => round($lat, 6),
        'longitude' => round($lng, 6),
        'foto_wajah' => '',
        'foto_ktp' => '',
        'data_khusus' => null,
        'status' => 'Final',
        'input_by_user_id' => 1,
        'input_by_user_name' => 'Superadmin Pusat',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
}

$localDb['pendukung'] = $pendukungList;
file_put_contents($localDbFile, json_encode($localDb, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
echo "local_db.json berhasil diperbarui dengan " . count($pendukungList) . " pendukung.\n";

// 2. Buat DpcDprtSeeder.php untuk Laravel
$seederPhp = "<?php\n\nnamespace Database\Seeders;\n\nuse Illuminate\Database\Seeder;\nuse App\Models\Pendukung;\n\nclass DpcDprtSeeder extends Seeder\n{\n    public function run(): void\n    {\n        \$records = " . var_export($pendukungList, true) . ";\n\n        foreach (\$records as \$r) {\n            unset(\$r['id']);\n            Pendukung::updateOrCreate(\n                [\n                    'jalur' => \$r['jalur'],\n                    'kecamatan' => \$r['kecamatan'],\n                    'desa' => \$r['desa'],\n                    'jabatan' => \$r['jabatan'],\n                    'nama' => \$r['nama']\n                ],\n                \$r\n            );\n        }\n    }\n}\n";

file_put_contents('backend/database/seeders/DpcDprtSeeder.php', $seederPhp);
echo "DpcDprtSeeder.php berhasil dibuat.\n";
