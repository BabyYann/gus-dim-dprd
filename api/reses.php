<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$action = $_GET['action'] ?? 'list_all';
$body = get_request_body();

// Ensure user is authenticated
$user = get_auth_user();
if (!$user) {
    json_response(['success' => false, 'message' => 'Sesi tidak valid atau telah kedaluwarsa. Silakan login kembali.'], 401);
}

switch ($action) {
    case 'list_all':
        $events = [];
        $pokirList = [];
        $kehadiranMap = [];

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            try {
                $stmt = $pdo->query("SELECT * FROM reses_titik ORDER BY tanggal DESC, waktu DESC");
                $events = $stmt->fetchAll();

                $stmtPokir = $pdo->query("SELECT * FROM pokir_usulan ORDER BY id DESC");
                $pokirList = $stmtPokir->fetchAll();

                $stmtHadir = $pdo->query("SELECT * FROM reses_kehadiran ORDER BY id DESC");
                $allHadir = $stmtHadir->fetchAll();
                foreach ($allHadir as $h) {
                    $rid = $h['reses_id'] ?? 0;
                    if (!isset($kehadiranMap[$rid])) $kehadiranMap[$rid] = [];
                    $kehadiranMap[$rid][] = $h;
                }
            } catch (\Exception $e) {
                // Table might not exist yet, fallback to JSON
                $data = Database::getJsonData();
                $events = $data['reses_events'] ?? [];
                $pokirList = $data['pokir_usulan'] ?? [];
            }
        } else {
            $data = Database::getJsonData();
            $events = $data['reses_events'] ?? [];
            $pokirList = $data['pokir_usulan'] ?? [];
        }

        // Normalize output with full aliases
        $normalizedEvents = [];
        $totalHadirGlobal = 0;
        foreach ($events as $ev) {
            $eId = $ev['id'] ?? null;
            $hadirList = $kehadiranMap[$eId] ?? ($ev['kehadiran_warga'] ?? []);
            $hadirCount = count($hadirList);
            $totalHadirGlobal += $hadirCount;

            $normalizedEvents[] = [
                'id' => $eId,
                'nama' => $ev['nama'] ?? $ev['nama_acara'] ?? '',
                'nama_acara' => $ev['nama_acara'] ?? $ev['nama'] ?? '',
                'masa_sidang' => $ev['masa_sidang'] ?? $ev['masaSidang'] ?? 'Masa Sidang I 2026',
                'masaSidang' => $ev['masaSidang'] ?? $ev['masa_sidang'] ?? 'Masa Sidang I 2026',
                'kecamatan' => $ev['kecamatan'] ?? '',
                'desa' => $ev['desa'] ?? '',
                'dusun' => $ev['dusun'] ?? '',
                'lokasi' => $ev['lokasi_tuan_rumah'] ?? $ev['lokasi_detail'] ?? $ev['lokasi'] ?? '',
                'lokasi_detail' => $ev['lokasi_detail'] ?? $ev['lokasi_tuan_rumah'] ?? $ev['lokasi'] ?? '',
                'tanggal' => $ev['tanggal'] ?? '',
                'waktu' => $ev['waktu'] ?? '',
                'target_kelompok' => $ev['target_peserta'] ?? $ev['target_kelompok'] ?? $ev['target'] ?? 'Masyarakat Umum',
                'target' => $ev['target_peserta'] ?? $ev['target_kelompok'] ?? $ev['target'] ?? 'Masyarakat Umum',
                'catatan' => $ev['catatan'] ?? '',
                'status' => $ev['status'] ?? (strtotime($ev['tanggal'] ?? '') < strtotime(date('Y-m-d')) ? 'Selesai' : 'Terjadwal'),
                'total_hadir' => $hadirCount,
                'kehadiran_warga' => $hadirList
            ];
        }

        $normalizedPokir = [];
        $totalAnggaranApbd = 0;
        $realisasiCount = 0;
        foreach ($pokirList as $pk) {
            $tahap = $pk['status_tahap'] ?? $pk['status'] ?? 'Aspirasi Reses';
            $anggaran = (float)($pk['estimasi_anggaran'] ?? $pk['estimasiAnggaran'] ?? 0);
            
            if (stripos($tahap, 'APBD') !== false || stripos($tahap, 'Realisasi') !== false) {
                $totalAnggaranApbd += $anggaran;
            }
            if (stripos($tahap, 'Realisasi') !== false) {
                $realisasiCount++;
            }

            $normalizedPokir[] = [
                'id' => $pk['id'] ?? null,
                'reses_id' => $pk['reses_id'] ?? $pk['resesId'] ?? null,
                'judul' => $pk['judul'] ?? $pk['judul_usulan'] ?? '',
                'judul_usulan' => $pk['judul_usulan'] ?? $pk['judul'] ?? '',
                'kategori' => $pk['kategori'] ?? 'Infrastruktur',
                'kecamatan' => $pk['kecamatan'] ?? '',
                'desa' => $pk['desa'] ?? '',
                'dusun' => $pk['dusun'] ?? '',
                'estimasi_anggaran' => $anggaran,
                'estimasiAnggaran' => $anggaran,
                'nama_pengusul' => $pk['pengusul_nama'] ?? $pk['nama_pengusul'] ?? '',
                'pengusul_nama' => $pk['pengusul_nama'] ?? $pk['nama_pengusul'] ?? '',
                'kontak_pengusul' => $pk['pengusul_hp'] ?? $pk['kontak_pengusul'] ?? $pk['no_hp'] ?? '',
                'pengusul_hp' => $pk['pengusul_hp'] ?? $pk['kontak_pengusul'] ?? $pk['no_hp'] ?? '',
                'deskripsi' => $pk['deskripsi'] ?? '',
                'status_tahap' => $tahap,
                'catatan_progres' => $pk['catatan_progres'] ?? $pk['catatan'] ?? '',
                'created_at' => $pk['created_at'] ?? date('Y-m-d H:i:s')
            ];
        }

        json_response([
            'success' => true,
            'data' => [
                'events' => $normalizedEvents,
                'pokir' => $normalizedPokir,
                'stats' => [
                    'total_titik' => count($normalizedEvents),
                    'total_warga' => $totalHadirGlobal,
                    'total_pokir' => count($normalizedPokir),
                    'total_realisasi' => $realisasiCount,
                    'total_anggaran_apbd' => $totalAnggaranApbd
                ]
            ]
        ]);
        break;

    case 'add_event':
        $nama = trim($body['nama'] ?? $body['nama_acara'] ?? '');
        $masaSidang = trim($body['masaSidang'] ?? $body['masa_sidang'] ?? 'Masa Sidang I 2026');
        $kecamatan = trim($body['kecamatan'] ?? '');
        $desa = trim($body['desa'] ?? '');
        $dusun = trim($body['dusun'] ?? '');
        $lokasi = trim($body['lokasi'] ?? $body['lokasi_detail'] ?? '');
        $tanggal = trim($body['tanggal'] ?? date('Y-m-d'));
        $waktu = trim($body['waktu'] ?? '13:30');
        $target = trim($body['target'] ?? $body['target_kelompok'] ?? 'Masyarakat Umum');
        $catatan = trim($body['catatan'] ?? '');

        if (!$nama || !$kecamatan || !$desa) {
            json_response(['success' => false, 'message' => 'Nama kegiatan, kecamatan, dan desa wajib diisi.']);
        }

        $now = date('Y-m-d H:i:s');
        $newId = null;

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("INSERT INTO reses_titik 
                (nama, masa_sidang, kecamatan, desa, dusun, lokasi_tuan_rumah, tanggal, waktu, target_peserta, catatan, created_by, created_at)
                VALUES (:nama, :sidang, :kec, :desa, :dusun, :lokasi, :tgl, :wkt, :target, :catatan, :uid, :cat)");
            $stmt->execute([
                'nama' => $nama,
                'sidang' => $masaSidang,
                'kec' => $kecamatan,
                'desa' => $desa,
                'dusun' => $dusun,
                'lokasi' => $lokasi,
                'tgl' => $tanggal,
                'wkt' => $waktu,
                'target' => $target,
                'catatan' => $catatan,
                'uid' => $user['id'],
                'cat' => $now
            ]);
            $newId = (int)$pdo->lastInsertId();
        } else {
            $data = Database::getJsonData();
            $events = $data['reses_events'] ?? [];
            $newId = count($events) > 0 ? (max(array_column($events, 'id')) + 1) : 1;
            $newEvent = [
                'id' => $newId,
                'nama' => $nama,
                'nama_acara' => $nama,
                'masa_sidang' => $masaSidang,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'dusun' => $dusun,
                'lokasi_detail' => $lokasi,
                'tanggal' => $tanggal,
                'waktu' => $waktu,
                'target_kelompok' => $target,
                'catatan' => $catatan,
                'status' => 'Terjadwal',
                'created_by' => $user['id'],
                'created_at' => $now,
                'kehadiran_warga' => []
            ];
            $events[] = $newEvent;
            $data['reses_events'] = $events;
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Jadwalkan Reses', 'Reses #' . $newId, "Menjadwalkan reses di Desa $desa, Kec. $kecamatan");

        json_response([
            'success' => true,
            'message' => 'Titik agenda reses berhasil ditambahkan!',
            'data' => ['id' => $newId]
        ]);
        break;

    case 'add_kehadiran':
        $resesId = $body['resesId'] ?? $body['reses_id'] ?? null;
        $nama = trim($body['nama'] ?? '');
        $nik = trim($body['nik'] ?? '');
        $hp = trim($body['noHp'] ?? $body['no_hp'] ?? $body['hp'] ?? '');
        $kecamatan = trim($body['kecamatan'] ?? '');
        $desa = trim($body['desa'] ?? '');
        $dusun = trim($body['dusun'] ?? '');
        $kategori = trim($body['kategori'] ?? $body['kategori_peserta'] ?? 'Konstituen Reses');

        if (!$nama || !$resesId) {
            json_response(['success' => false, 'message' => 'Nama warga dan kegiatan reses wajib dipilih.']);
        }

        $now = date('Y-m-d H:i:s');
        $newId = null;

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("INSERT INTO reses_kehadiran 
                (reses_id, nama, nik, no_hp, kecamatan, desa, dusun, kategori_peserta, created_by, created_at)
                VALUES (:rid, :nama, :nik, :hp, :kec, :desa, :dusun, :kat, :uid, :cat)");
            $stmt->execute([
                'rid' => $resesId,
                'nama' => $nama,
                'nik' => $nik,
                'hp' => $hp,
                'kec' => $kecamatan,
                'desa' => $desa,
                'dusun' => $dusun,
                'kat' => $kategori,
                'uid' => $user['id'],
                'cat' => $now
            ]);
            $newId = (int)$pdo->lastInsertId();
        } else {
            $data = Database::getJsonData();
            $events = $data['reses_events'] ?? [];
            foreach ($events as &$ev) {
                if ($ev['id'] == $resesId) {
                    if (!isset($ev['kehadiran_warga'])) $ev['kehadiran_warga'] = [];
                    $newId = count($ev['kehadiran_warga']) + 1;
                    $ev['kehadiran_warga'][] = [
                        'id' => $newId,
                        'nama' => $nama,
                        'nik' => $nik,
                        'no_hp' => $hp,
                        'kecamatan' => $kecamatan,
                        'desa' => $desa,
                        'dusun' => $dusun,
                        'kategori_peserta' => $kategori,
                        'waktu_hadir' => $now
                    ];
                    break;
                }
            }
            $data['reses_events'] = $events;
            Database::saveJsonData($data);
        }

        // Auto-enroll into voters / pendukung if NIK or HP provided
        if (!empty($nik) || !empty($hp)) {
            if (Database::isMysql()) {
                $pdo = Database::getPdo();
                try {
                    $check = $pdo->prepare("SELECT id FROM pendukung WHERE (nik = :nik AND nik != '') OR (no_hp = :hp AND no_hp != '') LIMIT 1");
                    $check->execute(['nik' => $nik, 'hp' => $hp]);
                    if (!$check->fetch()) {
                        $ins = $pdo->prepare("INSERT INTO pendukung 
                            (jalur, nama, nik, no_hp, kecamatan, desa, dusun, status_verifikasi, input_by, created_at)
                            VALUES ('RELAWAN', :nama, :nik, :hp, :kec, :desa, :dusun, 'Terverifikasi', :uid, :cat)");
                        $ins->execute([
                            'nama' => $nama,
                            'nik' => $nik,
                            'hp' => $hp,
                            'kec' => $kecamatan,
                            'desa' => $desa,
                            'dusun' => $dusun,
                            'uid' => $user['id'],
                            'cat' => $now
                        ]);
                    }
                } catch (\Exception $e) {}
            } else {
                $data = Database::getJsonData();
                $pendukungList = $data['pendukung'] ?? [];
                $exists = false;
                foreach ($pendukungList as $p) {
                    if ((!empty($nik) && ($p['nik'] ?? '') === $nik) || (!empty($hp) && ($p['no_hp'] ?? '') === $hp)) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $newPId = count($pendukungList) > 0 ? (max(array_column($pendukungList, 'id')) + 1) : 1;
                    $pendukungList[] = [
                        'id' => $newPId,
                        'jalur' => 'RELAWAN',
                        'nama' => $nama,
                        'nik' => $nik,
                        'no_hp' => $hp,
                        'kecamatan' => $kecamatan,
                        'desa' => $desa,
                        'dusun' => $dusun,
                        'status_verifikasi' => 'Terverifikasi',
                        'catatan' => 'Penjaringan Otomatis dari Presensi Reses',
                        'input_by' => $user['id'],
                        'created_at' => $now
                    ];
                    $data['pendukung'] = $pendukungList;
                    Database::saveJsonData($data);
                }
            }
        }

        log_activity($user['id'], $user['nama'], 'Presensi Reses', 'Reses #' . $resesId, "Mencatat presensi warga: $nama ($desa, $kecamatan)");

        json_response(['success' => true, 'message' => 'Presensi warga berhasil dicatat dan disinkronkan!', 'id' => $newId]);
        break;

    case 'add_pokir':
        $judul = trim($body['judul'] ?? $body['judul_usulan'] ?? '');
        $kategori = trim($body['kategori'] ?? 'Infrastruktur');
        $kecamatan = trim($body['kecamatan'] ?? '');
        $desa = trim($body['desa'] ?? '');
        $dusun = trim($body['dusun'] ?? '');
        $estimasi = (float)($body['estimasiAnggaran'] ?? $body['estimasi_anggaran'] ?? $body['estimasi'] ?? 0);
        $pengusulNama = trim($body['pengusulNama'] ?? $body['nama_pengusul'] ?? $body['pengusul'] ?? '');
        $pengusulHp = trim($body['pengusulHp'] ?? $body['kontak_pengusul'] ?? $body['hp'] ?? '');
        $deskripsi = trim($body['deskripsi'] ?? '');
        $resesId = !empty($body['resesId'] ?? $body['reses_id']) ? (int)($body['resesId'] ?? $body['reses_id']) : null;

        if (!$judul || !$pengusulNama) {
            json_response(['success' => false, 'message' => 'Judul usulan dan nama pengusul wajib diisi.']);
        }

        $now = date('Y-m-d H:i:s');
        $statusTahap = 'Aspirasi Reses';
        $newId = null;

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("INSERT INTO pokir_usulan 
                (reses_id, judul, kategori, kecamatan, desa, dusun, estimasi_anggaran, pengusul_nama, pengusul_hp, deskripsi, status_tahap, input_by, created_at)
                VALUES (:rid, :judul, :kat, :kec, :desa, :dusun, :est, :pnama, :php, :desk, :status, :uid, :cat)");
            $stmt->execute([
                'rid' => $resesId,
                'judul' => $judul,
                'kat' => $kategori,
                'kec' => $kecamatan,
                'desa' => $desa,
                'dusun' => $dusun,
                'est' => $estimasi,
                'pnama' => $pengusulNama,
                'php' => $pengusulHp,
                'desk' => $deskripsi,
                'status' => $statusTahap,
                'uid' => $user['id'],
                'cat' => $now
            ]);
            $newId = (int)$pdo->lastInsertId();
        } else {
            $data = Database::getJsonData();
            $pokir = $data['pokir_usulan'] ?? [];
            $newId = count($pokir) > 0 ? (max(array_column($pokir, 'id')) + 1) : 1;
            $newPokir = [
                'id' => $newId,
                'reses_id' => $resesId,
                'judul_usulan' => $judul,
                'kategori' => $kategori,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'dusun' => $dusun,
                'estimasi_anggaran' => $estimasi,
                'nama_pengusul' => $pengusulNama,
                'kontak_pengusul' => $pengusulHp,
                'deskripsi' => $deskripsi,
                'status_tahap' => $statusTahap,
                'catatan_progres' => 'Aspirasi baru diserap dari konstituen reses',
                'input_by' => $user['id'],
                'created_at' => $now
            ];
            $pokir[] = $newPokir;
            $data['pokir_usulan'] = $pokir;
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Input Usulan Pokir', 'Pokir #' . $newId, "Menginput usulan: $judul di Desa $desa");

        json_response([
            'success' => true,
            'message' => 'Usulan Pokir berhasil dicatat ke bank aspirasi!',
            'data' => ['id' => $newId]
        ]);
        break;

    case 'update_pokir_status':
        $id = (int)($body['id'] ?? $body['pokirId'] ?? 0);
        $statusTahap = trim($body['statusTahap'] ?? $body['status_tahap'] ?? $body['status'] ?? '');
        $catatan = trim($body['catatan'] ?? $body['catatan_progres'] ?? '');

        if (!$id || !$statusTahap) {
            json_response(['success' => false, 'message' => 'ID usulan dan status tahap wajib diisi.']);
        }

        $now = date('Y-m-d H:i:s');

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("UPDATE pokir_usulan SET status_tahap = :st, catatan_progres = :cat, updated_at = :uat WHERE id = :id");
            $stmt->execute(['st' => $statusTahap, 'cat' => $catatan, 'uat' => $now, 'id' => $id]);
        } else {
            $data = Database::getJsonData();
            $pokir = $data['pokir_usulan'] ?? [];
            foreach ($pokir as &$pk) {
                if ($pk['id'] == $id) {
                    $pk['status_tahap'] = $statusTahap;
                    $pk['catatan_progres'] = $catatan;
                    $pk['updated_at'] = $now;
                    break;
                }
            }
            $data['pokir_usulan'] = $pokir;
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Update Pokir', 'Pokir #' . $id, "Memperbarui status Pokir #$id menjadi '$statusTahap'");

        json_response(['success' => true, 'message' => 'Tahapan usulan Pokir berhasil diperbarui!']);
        break;

    default:
        json_response(['success' => false, 'message' => "Aksi '$action' tidak dikenali."], 400);
        break;
}