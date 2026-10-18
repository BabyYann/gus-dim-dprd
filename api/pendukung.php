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

        $status = 'Diinput';
        if (($user['role'] ?? '') === 'Superadmin') {
            $allowedStatuses = ['Diinput', 'Diverifikasi Desa', 'Divalidasi Kecamatan', 'Final', 'Ditolak'];
            $reqStatus = trim($body['status'] ?? '');
            if (in_array($reqStatus, $allowedStatuses)) {
                $status = $reqStatus;
            }
        }
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

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", array_map(fn($w) => "p." . $w, $where)) : "";
            $sql = "SELECT p.id, p.jalur, p.nik, p.nama, p.hp, p.umur, p.jabatan, p.koordinator, p.alamat, 
                           p.kecamatan, p.desa, p.latitude as lat, p.longitude as lng, p.foto_wajah as foto, 
                           p.foto_ktp, p.status, p.catatan, p.data_khusus, p.input_by_user_name, p.created_at,
                           u.id as operator_user_id, u.username as operator_username, u.role as operator_role, u.status as operator_status
                    FROM pendukung p
                    LEFT JOIN users u ON (u.pendukung_id = p.id OR (p.hp != '' AND (u.username = p.hp OR u.username = REPLACE(REPLACE(p.hp, '-', ''), ' ', ''))))
                    {$whereSql} 
                    ORDER BY p.id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();
            foreach ($rows as &$r) {
                $dk = is_string($r['data_khusus'] ?? null) ? json_decode($r['data_khusus'], true) : ($r['data_khusus'] ?? null);
                $r['tps'] = $dk['tps'] ?? ($r['tps'] ?? '01');
            }
            unset($r);
        } else {
            $data = Database::getJsonData();
            $rawRows = $data['pendukung'] ?? [];
            $usersList = $data['users'] ?? [];
            foreach ($rawRows as $r) {
                if ($user['role'] === 'Koordinator Desa' || $user['role'] === 'Admin Ranting') {
                    if (empty($user['desa']) || strcasecmp($r['desa'] ?? '', $user['desa']) !== 0) continue;
                } else if ($user['role'] === 'Koordinator Kecamatan') {
                    if (empty($user['kecamatan']) || strcasecmp($r['kecamatan'] ?? '', $user['kecamatan']) !== 0) continue;
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
                if (!isset($r['tps'])) {
                    $dk = $r['data_khusus'] ?? null;
                    $r['tps'] = is_array($dk) ? ($dk['tps'] ?? '01') : '01';
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
            $stmt = $pdo->prepare("SELECT id, nama, jalur, kecamatan, desa, input_by_user_name, DATE_FORMAT(created_at, '%d/%m/%Y') as tanggal FROM pendukung WHERE nik = :nik LIMIT 1");
            $stmt->execute(['nik' => $nik]);
            $ada = $stmt->fetch();
        } else {
            $data = Database::getJsonData();
            foreach ($data['pendukung'] ?? [] as $item) {
                if ($item['nik'] === $nik) {
                    $ada = $item;
                    $ada['tanggal'] = date('d/m/Y', strtotime($item['created_at'] ?? 'now'));
                    break;
                }
            }
        }
        json_response([
            'exists' => (bool)$ada,
            'data' => $ada
        ]);
        break;

    case 'update':
        $targetId = (int)($body['id'] ?? ($body['rowNumber'] ?? ($_GET['id'] ?? 0)));
        if (!$targetId) {
            json_response(['success' => false, 'message' => 'ID data pendukung tidak valid.'], 400);
        }

        $userRole = $user['role'] ?? '';
        $userKec = trim($user['kecamatan'] ?? '');
        $userDesa = trim($user['desa'] ?? '');
        $userId = (int)($user['id'] ?? 0);
        $userNama = $user['nama'] ?? '';

        $nama = trim($body['nama'] ?? '');
        if ($nama === '') {
            json_response(['success' => false, 'message' => 'Nama pendukung tidak boleh kosong.'], 400);
        }

        $nik = trim($body['nik'] ?? '');
        $hp = trim($body['hp'] ?? ($body['no_hp'] ?? ($body['telepon'] ?? '')));
        $jalur = strtoupper(trim($body['jalur'] ?? ''));
        $jabatan = trim($body['jabatan'] ?? '');
        $koordinator = trim($body['koordinator'] ?? '');
        $kecamatan = trim($body['kecamatan'] ?? '');
        $desa = trim($body['desa'] ?? '');
        $alamat = trim($body['alamat'] ?? '');
        $tps = trim($body['tps'] ?? '');

        $allowedJalur = ['DPC', 'DPRT', 'PIP', 'KIP', 'RELAWAN'];
        if ($jalur !== '' && !in_array($jalur, $allowedJalur)) {
            $jalur = 'RELAWAN';
        }

        $umur = !empty($nik) ? hitung_umur_nik($nik) : null;

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT * FROM pendukung WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $targetId]);
            $pRow = $stmt->fetch();
            if (!$pRow) {
                json_response(['success' => false, 'message' => 'Data pendukung tidak ditemukan.'], 404);
            }

            // Validasi hak akses teritorial & wewenang
            $canEdit = false;
            if ($userRole === 'Superadmin') {
                $canEdit = true;
            } else if ($userRole === 'Koordinator Kecamatan') {
                if ($userKec !== '' && strcasecmp($userKec, trim($pRow['kecamatan'] ?? '')) === 0) {
                    $canEdit = true;
                }
            } else if ($userRole === 'Koordinator Desa' || $userRole === 'Admin Ranting') {
                if ($userDesa !== '' && strcasecmp($userDesa, trim($pRow['desa'] ?? '')) === 0) {
                    $canEdit = true;
                }
            } else {
                if (!empty($pRow['input_by_user_name']) && $pRow['input_by_user_name'] === $userNama) {
                    $canEdit = true;
                }
            }

            if (!$canEdit) {
                json_response(['success' => false, 'message' => 'Akses ditolak: Anda tidak memiliki wewenang mengubah data di luar wilayah tugas Anda.'], 403);
            }

            // Validasi duplikasi NIK jika NIK diubah
            if (!empty($nik) && $nik !== ($pRow['nik'] ?? '')) {
                $stmtCheck = $pdo->prepare("SELECT id, nama FROM pendukung WHERE nik = :nik AND id != :id LIMIT 1");
                $stmtCheck->execute(['nik' => $nik, 'id' => $targetId]);
                $dup = $stmtCheck->fetch();
                if ($dup) {
                    json_response(['success' => false, 'message' => "NIK {$nik} sudah terdaftar pada pendukung lain atas nama '{$dup['nama']}'."], 400);
                }
            }

            // Perbarui data_khusus untuk TPS
            $dataKhusus = [];
            if (!empty($pRow['data_khusus'])) {
                $decoded = is_string($pRow['data_khusus']) ? json_decode($pRow['data_khusus'], true) : $pRow['data_khusus'];
                if (is_array($decoded)) $dataKhusus = $decoded;
            }
            if ($tps !== '') {
                $dataKhusus['tps'] = $tps;
            }

            $finalJalur = $jalur !== '' ? $jalur : ($pRow['jalur'] ?? 'RELAWAN');
            $finalKecamatan = $kecamatan !== '' ? $kecamatan : ($pRow['kecamatan'] ?? '');
            $finalDesa = $desa !== '' ? $desa : ($pRow['desa'] ?? '');
            $finalJabatan = $jabatan !== '' ? $jabatan : ($pRow['jabatan'] ?? '');
            $finalKoordinator = $koordinator !== '' ? $koordinator : ($pRow['koordinator'] ?? '');
            $finalAlamat = $alamat !== '' ? $alamat : ($pRow['alamat'] ?? '');

            $stmtUp = $pdo->prepare("UPDATE pendukung SET 
                nama = :nama,
                nik = :nik,
                hp = :hp,
                umur = :umur,
                jalur = :jalur,
                jabatan = :jabatan,
                koordinator = :koordinator,
                kecamatan = :kecamatan,
                desa = :desa,
                alamat = :alamat,
                data_khusus = :data_khusus,
                updated_at = NOW()
                WHERE id = :id");

            $stmtUp->execute([
                'nama' => $nama,
                'nik' => $nik,
                'hp' => $hp,
                'umur' => $umur,
                'jalur' => $finalJalur,
                'jabatan' => $finalJabatan,
                'koordinator' => $finalKoordinator,
                'kecamatan' => $finalKecamatan,
                'desa' => $finalDesa,
                'alamat' => $finalAlamat,
                'data_khusus' => !empty($dataKhusus) ? json_encode($dataKhusus, JSON_UNESCAPED_UNICODE) : null,
                'id' => $targetId
            ]);

            // Sinkronkan ke akun users jika pendukung ini terdaftar sebagai operator
            $stmtUpUser = $pdo->prepare("UPDATE users SET nama = :nama, hp = :hp, kecamatan = :kecamatan, desa = :desa WHERE pendukung_id = :id");
            $stmtUpUser->execute([
                'nama' => $nama,
                'hp' => $hp,
                'kecamatan' => $finalKecamatan,
                'desa' => $finalDesa,
                'id' => $targetId
            ]);
        } else {
            $data = Database::getJsonData();
            $foundIndex = -1;
            $pRow = null;
            foreach ($data['pendukung'] as $k => $p) {
                if ((int)$p['id'] === $targetId) {
                    $foundIndex = $k;
                    $pRow = $p;
                    break;
                }
            }
            if ($foundIndex === -1 || !$pRow) {
                json_response(['success' => false, 'message' => 'Data pendukung tidak ditemukan.'], 404);
            }

            // Validasi hak akses teritorial & wewenang
            $canEdit = false;
            if ($userRole === 'Superadmin') {
                $canEdit = true;
            } else if ($userRole === 'Koordinator Kecamatan') {
                if ($userKec !== '' && strcasecmp($userKec, trim($pRow['kecamatan'] ?? '')) === 0) {
                    $canEdit = true;
                }
            } else if ($userRole === 'Koordinator Desa' || $userRole === 'Admin Ranting') {
                if ($userDesa !== '' && strcasecmp($userDesa, trim($pRow['desa'] ?? '')) === 0) {
                    $canEdit = true;
                }
            } else {
                if (!empty($pRow['input_by_user_name']) && $pRow['input_by_user_name'] === $userNama) {
                    $canEdit = true;
                }
            }

            if (!$canEdit) {
                json_response(['success' => false, 'message' => 'Akses ditolak: Anda tidak memiliki wewenang mengubah data di luar wilayah tugas Anda.'], 403);
            }

            // Validasi duplikasi NIK jika NIK diubah
            if (!empty($nik) && $nik !== ($pRow['nik'] ?? '')) {
                foreach ($data['pendukung'] as $checkP) {
                    if ((int)$checkP['id'] !== $targetId && ($checkP['nik'] ?? '') === $nik) {
                        $dupNama = $checkP['nama'] ?? 'Lainnya';
                        json_response(['success' => false, 'message' => "NIK {$nik} sudah terdaftar pada pendukung lain atas nama '{$dupNama}'."], 400);
                    }
                }
            }

            $finalJalur = $jalur !== '' ? $jalur : ($pRow['jalur'] ?? 'RELAWAN');
            $finalKecamatan = $kecamatan !== '' ? $kecamatan : ($pRow['kecamatan'] ?? '');
            $finalDesa = $desa !== '' ? $desa : ($pRow['desa'] ?? '');
            $finalJabatan = $jabatan !== '' ? $jabatan : ($pRow['jabatan'] ?? '');
            $finalKoordinator = $koordinator !== '' ? $koordinator : ($pRow['koordinator'] ?? '');
            $finalAlamat = $alamat !== '' ? $alamat : ($pRow['alamat'] ?? '');

            $dataKhusus = is_array($pRow['data_khusus'] ?? null) ? $pRow['data_khusus'] : [];
            if ($tps !== '') {
                $dataKhusus['tps'] = $tps;
            }

            $data['pendukung'][$foundIndex]['nama'] = $nama;
            $data['pendukung'][$foundIndex]['nik'] = $nik;
            $data['pendukung'][$foundIndex]['hp'] = $hp;
            $data['pendukung'][$foundIndex]['umur'] = $umur;
            $data['pendukung'][$foundIndex]['jalur'] = $finalJalur;
            $data['pendukung'][$foundIndex]['jabatan'] = $finalJabatan;
            $data['pendukung'][$foundIndex]['koordinator'] = $finalKoordinator;
            $data['pendukung'][$foundIndex]['kecamatan'] = $finalKecamatan;
            $data['pendukung'][$foundIndex]['desa'] = $finalDesa;
            $data['pendukung'][$foundIndex]['alamat'] = $finalAlamat;
            $data['pendukung'][$foundIndex]['data_khusus'] = $dataKhusus;
            $data['pendukung'][$foundIndex]['tps'] = $tps !== '' ? $tps : ($pRow['tps'] ?? '01');
            $data['pendukung'][$foundIndex]['updated_at'] = date('Y-m-d H:i:s');

            foreach ($data['users'] as $uk => $u) {
                if ((int)($u['pendukung_id'] ?? 0) === $targetId) {
                    $data['users'][$uk]['nama'] = $nama;
                    $data['users'][$uk]['hp'] = $hp;
                    $data['users'][$uk]['kecamatan'] = $finalKecamatan;
                    $data['users'][$uk]['desa'] = $finalDesa;
                }
            }

            Database::saveJsonData($data);
        }

        log_activity($userId, $userNama, 'Edit Pendukung', (string)$targetId, "Memperbarui data pendukung: {$nama} ({$finalJalur})");

        json_response([
            'success' => true,
            'message' => "Data pendukung '{$nama}' berhasil diperbarui.",
            'data' => [
                'id' => $targetId,
                'nama' => $nama,
                'nik' => $nik,
                'hp' => $hp,
                'umur' => $umur,
                'jalur' => $finalJalur,
                'jabatan' => $finalJabatan,
                'kecamatan' => $finalKecamatan,
                'desa' => $finalDesa,
                'tps' => $tps !== '' ? $tps : '01',
                'alamat' => $finalAlamat
            ]
        ]);
        break;

    case 'delete':
        $targetId = (int)($body['id'] ?? ($body['rowNumber'] ?? ($_GET['id'] ?? 0)));
        if (!$targetId) {
            json_response(['success' => false, 'message' => 'ID data pendukung tidak valid.'], 400);
        }

        $userRole = $user['role'] ?? '';
        $userKec = trim($user['kecamatan'] ?? '');
        $userDesa = trim($user['desa'] ?? '');
        $userId = (int)($user['id'] ?? 0);
        $userNama = $user['nama'] ?? '';

        $deletedName = '';
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT id, nama, kecamatan, desa, input_by_user_name FROM pendukung WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $targetId]);
            $pRow = $stmt->fetch();
            if (!$pRow) {
                json_response(['success' => false, 'message' => 'Data pendukung tidak ditemukan.'], 404);
            }

            // Validasi hak akses teritorial & kepemilikan data
            $canDelete = false;
            if ($userRole === 'Superadmin') {
                $canDelete = true;
            } else if ($userRole === 'Koordinator Kecamatan') {
                if ($userKec !== '' && strcasecmp($userKec, trim($pRow['kecamatan'] ?? '')) === 0) {
                    $canDelete = true;
                }
            } else if ($userRole === 'Koordinator Desa' || $userRole === 'Admin Ranting') {
                if ($userDesa !== '' && strcasecmp($userDesa, trim($pRow['desa'] ?? '')) === 0) {
                    $canDelete = true;
                }
            } else {
                if (!empty($pRow['input_by_user_name']) && $pRow['input_by_user_name'] === $userNama) {
                    $canDelete = true;
                }
            }

            if (!$canDelete) {
                json_response(['success' => false, 'message' => 'Akses ditolak: Anda tidak memiliki wewenang menghapus data di luar wilayah tugas Anda.'], 403);
            }

            $deletedName = $pRow['nama'];

            $stmtDel = $pdo->prepare("DELETE FROM pendukung WHERE id = :id");
            $stmtDel->execute(['id' => $targetId]);

            // Hapus juga operator terkait jika ada pendukung_id
            $stmtOp = $pdo->prepare("DELETE FROM users WHERE pendukung_id = :id");
            $stmtOp->execute(['id' => $targetId]);
        } else {
            $data = Database::getJsonData();
            $foundIndex = -1;
            $pRow = null;
            foreach ($data['pendukung'] as $k => $p) {
                if ((int)$p['id'] === $targetId) {
                    $foundIndex = $k;
                    $pRow = $p;
                    break;
                }
            }
            if ($foundIndex === -1 || !$pRow) {
                json_response(['success' => false, 'message' => 'Data pendukung tidak ditemukan.'], 404);
            }

            // Validasi hak akses teritorial & kepemilikan data
            $canDelete = false;
            if ($userRole === 'Superadmin') {
                $canDelete = true;
            } else if ($userRole === 'Koordinator Kecamatan') {
                if ($userKec !== '' && strcasecmp($userKec, trim($pRow['kecamatan'] ?? '')) === 0) {
                    $canDelete = true;
                }
            } else if ($userRole === 'Koordinator Desa' || $userRole === 'Admin Ranting') {
                if ($userDesa !== '' && strcasecmp($userDesa, trim($pRow['desa'] ?? '')) === 0) {
                    $canDelete = true;
                }
            } else {
                if (!empty($pRow['input_by_user_name']) && $pRow['input_by_user_name'] === $userNama) {
                    $canDelete = true;
                }
            }

            if (!$canDelete) {
                json_response(['success' => false, 'message' => 'Akses ditolak: Anda tidak memiliki wewenang menghapus data di luar wilayah tugas Anda.'], 403);
            }

            $deletedName = $pRow['nama'] ?? "ID #{$targetId}";
            unset($data['pendukung'][$foundIndex]);
            $data['pendukung'] = array_values($data['pendukung']);

            foreach ($data['users'] as $uk => $u) {
                if ((int)($u['pendukung_id'] ?? 0) === $targetId) {
                    unset($data['users'][$uk]);
                }
            }
            $data['users'] = array_values($data['users']);
            Database::saveJsonData($data);
        }

        log_activity($userId, $userNama, 'Hapus Pendukung', (string)$targetId, "Menghapus data pendukung: {$deletedName}");

        json_response([
            'success' => true,
            'message' => "Data pendukung '{$deletedName}' berhasil dihapus permanen."
        ]);
        break;

    default:
        json_response(['error' => 'Aksi tidak dikenali'], 400);
}
