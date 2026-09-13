<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = require_auth();
$action = $_GET['action'] ?? 'list';
$body = get_request_body();

switch ($action) {
    case 'submit':
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
            $nama = trim($body['nama'] ?? '');
            $nik = trim($body['nik'] ?? '');
            $hp = trim($body['hp'] ?? '');
            $jabatan = trim($body['jabatan'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $alamat = trim($body['alamat'] ?? '');
        } else if ($jalur === 'DPRT') {
            $nama = trim($body['nama'] ?? '');
            $nik = trim($body['nik'] ?? '');
            $hp = trim($body['hp'] ?? '');
            $jabatan = trim($body['jabatan'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamat'] ?? '');
        } else if ($jalur === 'PIP') {
            $nama = trim($body['namaAnak'] ?? '');
            $nik = trim($body['nikAnak'] ?? '');
            $hp = trim($body['hpAnak'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamatKeluarga'] ?? '');
            $dataKhusus = [
                'namaSekolah' => trim($body['namaSekolah'] ?? ''),
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
            $nama = trim($body['namaAnak'] ?? '');
            $nik = trim($body['nikAnak'] ?? '');
            $hp = trim($body['hpAnak'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamatKeluarga'] ?? '');
            $dataKhusus = [
                'namaKampus' => trim($body['namaKampus'] ?? ''),
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
            $nama = trim($body['namaAnggota'] ?? '');
            $nik = trim($body['nikAnggota'] ?? '');
            $hp = trim($body['hpAnggota'] ?? '');
            $jabatan = 'Anggota';
            $koordinator = trim($body['namaKoordinator'] ?? '');
            $kecamatan = trim($body['kecamatan'] ?? '');
            $desa = trim($body['desa'] ?? '');
            $alamat = trim($body['alamatAnggota'] ?? '');
        }

        if (!$nama || !$nik) {
            json_response(['success' => false, 'message' => 'Nama dan NIK wajib diisi.']);
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
