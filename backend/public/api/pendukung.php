<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = require_auth();
$action = $_GET['action'] ?? 'list';
$body = get_request_body();

switch ($action) {
    case 'submit':
        if (empty($body) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            json_response(['success' => false, 'message' => 'Ukuran data foto melebihi kapasitas server. Sistem telah mengoptimalkan kompresi, silakan ulangi simpan data.']);
        }
        $jalur = strtoupper(trim($body['jalur'] ?? ''));
        $allowedJalur = ['DPC', 'DPRT', 'PIP', 'KIP', 'RELAWAN'];
        if (!in_array($jalur, $allowedJalur)) {
            json_response(['success' => false, 'message' => 'Jalur data tidak valid.']);
        }

        // Ekstraksi field berdasarkan jalur
        $nama = '';
        $nik = '';
        $hp = '';
        $jabatan = '';
        $koordinator = '';
        $kecamatan = '';
        $desa = '';
        $alamat = '';
        $dataKhusus = null;

        if ($jalur === 'DPC') {
            $nama = trim($body['nama'] ?? $body['namaAnggota'] ?? '');
            $nik = trim($body['nik'] ?? $body['nikAnggota'] ?? '');
            $hp = trim($body['hp'] ?? $body['hpAnggota'] ?? '');
            $jabatan = trim($body['jabatan'] ?? 'Pengurus DPC');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamat'] ?? '');
        } else if ($jalur === 'DPRT') {
            $nama = trim($body['nama'] ?? $body['namaAnggota'] ?? '');
            $nik = trim($body['nik'] ?? $body['nikAnggota'] ?? '');
            $hp = trim($body['hp'] ?? $body['hpAnggota'] ?? '');
            $jabatan = trim($body['jabatan'] ?? 'Pengurus DPRT');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamat'] ?? '');
        } else if ($jalur === 'PIP') {
            $nama = trim($body['namaAnak'] ?? $body['nama'] ?? '');
            $nik = trim($body['nikAnak'] ?? $body['nik'] ?? '');
            $hp = trim($body['hpAnak'] ?? $body['hp'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamatKeluarga'] ?? $body['alamat'] ?? '');
            $dataKhusus = [
                'namaSekolah' => trim($body['namaSekolah'] ?? ''),
                'tingkatSekolah' => trim($body['tingkatSekolah'] ?? ''),
                'alamatSekolah' => trim($body['alamatSekolah'] ?? ''),
                'namaAyah' => trim($body['namaAyah'] ?? ''),
                'nikAyah' => trim($body['nikAyah'] ?? ''),
                'hpAyah' => trim($body['hpAyah'] ?? ''),
                'namaIbu' => trim($body['namaIbu'] ?? ''),
                'nikIbu' => trim($body['nikIbu'] ?? ''),
                'hpIbu' => trim($body['hpIbu'] ?? ''),
                'jumlahSaudara' => trim($body['jumlahSaudara'] ?? ''),
                'namaSaudara' => trim($body['namaSaudara'] ?? ''),
                'nikSaudara' => trim($body['nikSaudara'] ?? '')
            ];
        } else if ($jalur === 'KIP') {
            $nama = trim($body['namaAnak'] ?? $body['nama'] ?? '');
            $nik = trim($body['nikAnak'] ?? $body['nik'] ?? '');
            $hp = trim($body['hpAnak'] ?? $body['hp'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamatKeluarga'] ?? $body['alamat'] ?? '');
            $dataKhusus = [
                'namaKampus' => trim($body['namaKampus'] ?? ''),
                'fakultas' => trim($body['fakultas'] ?? ''),
                'jurusan' => trim($body['jurusan'] ?? ''),
                'alamatKampus' => trim($body['alamatKampus'] ?? ''),
                'namaAyah' => trim($body['namaAyah'] ?? ''),
                'nikAyah' => trim($body['nikAyah'] ?? ''),
                'hpAyah' => trim($body['hpAyah'] ?? ''),
                'namaIbu' => trim($body['namaIbu'] ?? ''),
                'nikIbu' => trim($body['nikIbu'] ?? ''),
                'hpIbu' => trim($body['hpIbu'] ?? ''),
                'jumlahSaudara' => trim($body['jumlahSaudara'] ?? ''),
                'namaSaudara' => trim($body['namaSaudara'] ?? ''),
                'nikSaudara' => trim($body['nikSaudara'] ?? '')
            ];
        } else if ($jalur === 'RELAWAN') {
            $nama = trim($body['namaAnggota'] ?? $body['nama'] ?? '');
            $nik = trim($body['nikAnggota'] ?? $body['nik'] ?? '');
            $hp = trim($body['hpAnggota'] ?? $body['hp'] ?? '');
            $jabatan = trim($body['jabatan'] ?? $body['kategoriRelawan'] ?? $body['rlwJabatan'] ?? 'Relawan TPS');
            $koordinator = trim($body['namaKoordinator'] ?? $body['koordinator'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamatAnggota'] ?? $body['alamat'] ?? '');
        }

        // Fallback menyeluruh jika field nama / nik dikirim dengan penamaan alternatif
        if (!$nama) {
            $nama = trim($body['nama'] ?? $body['namaAnggota'] ?? $body['namaAnak'] ?? $body['dpcNama'] ?? $body['dprtNama'] ?? $body['pipNamaAnak'] ?? $body['kipNamaAnak'] ?? $body['rlwNamaAnggota'] ?? '');
        }
        if (!$nik) {
            $nik = trim($body['nik'] ?? $body['nikAnggota'] ?? $body['nikAnak'] ?? $body['dpcNik'] ?? $body['dprtNik'] ?? $body['pipNikAnak'] ?? $body['kipNikAnak'] ?? $body['rlwNikAnggota'] ?? '');
        }
        if (!$hp) {
            $hp = trim($body['hp'] ?? $body['hpAnggota'] ?? $body['hpAnak'] ?? $body['dpcHp'] ?? $body['dprtHp'] ?? $body['pipHpAnak'] ?? $body['kipHpAnak'] ?? $body['rlwHpAnggota'] ?? '');
        }
        if (!$kecamatan) {
            $kecamatan = trim($body['kecamatan'] ?? $body['dpcKecamatan'] ?? $body['dprtKecamatan'] ?? $body['pipKecamatan'] ?? $body['kipKecamatan'] ?? $body['rlwKecamatan'] ?? '');
        }
        if (!$desa) {
            $desa = trim($body['desa'] ?? $body['dpcDesa'] ?? $body['dprtDesa'] ?? $body['pipDesa'] ?? $body['kipDesa'] ?? $body['rlwDesa'] ?? '');
        }
        if (!$alamat) {
            $alamat = trim($body['alamat'] ?? $body['dpcAlamat'] ?? $body['dprtAlamat'] ?? $body['pipAlamatKeluarga'] ?? $body['kipAlamatKeluarga'] ?? $body['rlwAlamatAnggota'] ?? '');
        }
        $tps = trim($body['tps'] ?? $body['dpcTps'] ?? $body['dprtTps'] ?? $body['pipTps'] ?? $body['kipTps'] ?? $body['rlwTps'] ?? '');
        if ($tps !== '') {
            if (!is_array($dataKhusus)) $dataKhusus = [];
            $dataKhusus['tps'] = $tps;
        }

        if (!$nama || !$nik) {
            json_response(['success' => false, 'message' => 'Nama dan NIK wajib diisi. Silakan periksa kembali kelengkapan formulir.']);
        }

        // Kunci wilayah input jika user adalah Koordinator Desa / Admin Ranting / Koordinator Kecamatan
        if (($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') && !empty($user['desa'])) {
            $desa = $user['desa'];
            if (!empty($user['kecamatan'])) {
                $kecamatan = $user['kecamatan'];
            }
        } else if ($user['role'] === 'Koordinator Kecamatan' && !empty($user['kecamatan'])) {
            $kecamatan = $user['kecamatan'];
        }

        // Validasi duplikasi NIK di sistem
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT id, nama, jalur FROM pendukung WHERE nik = :nik LIMIT 1");
            $stmt->execute(['nik' => $nik]);
            $ada = $stmt->fetch();
            if ($ada) {
                json_response([
                    'success' => false, 
                    'message' => "NIK {$nik} sudah terdaftar sebelumnya atas nama '{$ada['nama']}' (Jalur: {$ada['jalur']})."
                ]);
            }
        }

        // Simpan file foto jika ada
        $fotoUrl = save_base64_image($body['fotoBase64'] ?? null, UPLOAD_FOTO_DIR, 'wajah');
        $ktpUrl = save_base64_image($body['ktpBase64'] ?? null, UPLOAD_KTP_DIR, 'ktp');

        $umur = hitung_umur_nik($nik);
        $lat = !empty($body['lat']) ? (float)$body['lat'] : null;
        $lng = !empty($body['lng']) ? (float)$body['lng'] : null;

        // Default koordinat per kecamatan jika user tidak share location
        if (!$lat || !$lng) {
            $defaultCoords = [
                'Kraksaan' => [-7.7580, 113.4150],
                'Besuk' => [-7.8020, 113.4420],
                'Gading' => [-7.8500, 113.4600]
            ];
            if (isset($defaultCoords[$kecamatan])) {
                $lat = $defaultCoords[$kecamatan][0] + (mt_rand(-50, 50) / 10000);
                $lng = $defaultCoords[$kecamatan][1] + (mt_rand(-50, 50) / 10000);
            }
        }

        $allowedStatuses = ['Diinput', 'Diverifikasi Desa', 'Divalidasi Kecamatan', 'Final', 'Ditolak'];
        $reqStatus = trim($body['status'] ?? '');
        $status = in_array($reqStatus, $allowedStatuses) ? $reqStatus : 'Diinput';
        $now = date('Y-m-d H:i:s');

        if (Database::isMysql()) {
            $stmt = $pdo->prepare("INSERT INTO pendukung 
                (jalur, nik, nama, hp, umur, jabatan, koordinator, alamat, kecamatan, desa, 
                 latitude, longitude, foto_wajah, foto_ktp, data_khusus, status, 
                 input_by_user_id, input_by_user_name, created_at)
                VALUES 
                (:jalur, :nik, :nama, :hp, :umur, :jabatan, :koordinator, :alamat, :kecamatan, :desa, 
                 :lat, :lng, :foto, :ktp, :khusus, :status, 
                 :uid, :unama, :cat)");
            $stmt->execute([
                'jalur' => $jalur,
                'nik' => $nik,
                'nama' => $nama,
                'hp' => $hp,
                'umur' => $umur,
                'jabatan' => $jabatan,
                'koordinator' => $koordinator,
                'alamat' => $alamat,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'lat' => $lat,
                'lng' => $lng,
                'foto' => $fotoUrl,
                'ktp' => $ktpUrl,
                'khusus' => $dataKhusus ? json_encode($dataKhusus) : null,
                'status' => $status,
                'uid' => $user['id'],
                'unama' => $user['nama'],
                'cat' => $now
            ]);
            $insertedId = $pdo->lastInsertId();
        } else {
            $data = Database::getJsonData();
            $p = $data['pendukung'] ?? [];
            $nextId = count($p) > 0 ? (max(array_column($p, 'id')) + 1) : 1;
            $newRecord = [
                'id' => $nextId,
                'jalur' => $jalur,
                'nik' => $nik,
                'nama' => $nama,
                'hp' => $hp,
                'umur' => $umur,
                'jabatan' => $jabatan,
                'koordinator' => $koordinator,
                'alamat' => $alamat,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'latitude' => $lat,
                'longitude' => $lng,
                'foto_wajah' => $fotoUrl ?: '',
                'foto_ktp' => $ktpUrl ?: '',
                'data_khusus' => $dataKhusus,
                'status' => $status,
                'catatan' => '',
                'input_by_user_id' => $user['id'],
                'input_by_user_name' => $user['nama'],
                'created_at' => $now
            ];
            $p[] = $newRecord;
            $data['pendukung'] = $p;
            Database::saveJsonData($data);
            $insertedId = $nextId;
        }

        log_activity($user['id'], $user['nama'], "Input Data {$jalur}", $jalur . $insertedId, "Input pendukung baru: {$nama} ({$jalur})");

        json_response([
            'success' => true,
            'message' => "Data {$jalur} berhasil disimpan dan masuk dalam antrean verifikasi.",
            'id' => $insertedId
        ]);
        break;

    case 'list':
        $rows = [];
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
                           p.foto_ktp, p.status, p.catatan, p.input_by_user_name, p.created_at,
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
            $rawRows = $data['pendukung'] ?? [];
            $usersList = $data['users'] ?? [];
            foreach ($rawRows as $r) {
                if (($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') && !empty($user['desa'])) {
                    if (strcasecmp($r['desa'] ?? '', $user['desa']) !== 0) continue;
                } else if ($user['role'] === 'Koordinator Kecamatan' && !empty($user['kecamatan'])) {
                    if (strcasecmp($r['kecamatan'] ?? '', $user['kecamatan']) !== 0) continue;
                }
                $r['operator_user_id'] = null;
                $r['operator_username'] = null;
                $r['operator_role'] = null;
                $r['operator_status'] = null;
                foreach ($usersList as $u) {
                    if (($u['pendukung_id'] ?? 0) === $r['id'] || (!empty($r['hp']) && $u['username'] === $r['hp'])) {
                        $r['operator_user_id'] = $u['id'];
                        $r['operator_username'] = $u['username'];
                        $r['operator_role'] = $u['role'];
                        $r['operator_status'] = $u['status'] ?? 'Aktif';
                        break;
                    }
                }
                $rows[] = $r;
            }
            usort($rows, fn($a, $b) => ($b['id'] ?? 0) - ($a['id'] ?? 0));
        }
        json_response([
            'success' => true,
            'data' => $rows,
            'rows' => $rows,
            'total' => count($rows)
        ]);
        break;

    case 'check-nik':
        $nik = trim($_GET['nik'] ?? '');
        if (!$nik) {
            json_response(['exists' => false]);
        }
        $ada = null;
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT id, nama, jalur, kecamatan, desa FROM pendukung WHERE nik = :nik LIMIT 1");
            $stmt->execute(['nik' => $nik]);
            $ada = $stmt->fetch();
        } else {
            $data = Database::getJsonData();
            foreach ($data['pendukung'] ?? [] as $item) {
                if ($item['nik'] === $nik) {
                    $ada = $item;
                    break;
                }
            }
        }
        json_response([
            'exists' => (bool)$ada,
            'data' => $ada
        ]);
        break;

    default:
        json_response(['error' => 'Aksi tidak dikenali'], 400);
}
