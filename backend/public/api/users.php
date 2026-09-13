<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = require_auth();
$action = $_GET['action'] ?? 'list';
$body = get_request_body();

// Hanya Superadmin yang boleh mengakses fitur manajemen user
if ($user['role'] !== 'Superadmin') {
    json_response(['success' => false, 'message' => 'Hanya Superadmin yang memiliki akses ke Pengaturan Pengguna.'], 403);
}

switch ($action) {
    case 'list':
        $users = [];
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->query("SELECT id as rowNumber, id, username, nama, role, kecamatan, desa, ranting, status 
                                 FROM users ORDER BY id ASC");
            $users = $stmt->fetchAll();
        } else {
            $data = Database::getJsonData();
            foreach ($data['users'] as $u) {
                $users[] = [
                    'rowNumber' => $u['id'],
                    'id' => $u['id'],
                    'username' => $u['username'],
                    'nama' => $u['nama'],
                    'role' => $u['role'],
                    'kecamatan' => $u['kecamatan'] ?? '',
                    'desa' => $u['desa'] ?? '',
                    'ranting' => $u['ranting'] ?? '',
                    'status' => $u['status'] ?? 'Aktif'
                ];
            }
        }
        json_response(['success' => true, 'rows' => $users]);
        break;

    case 'add':
        $username = trim($body['username'] ?? '');
        $password = $body['password'] ?? '';
        $nama = trim($body['nama'] ?? '');
        $role = trim($body['role'] ?? 'Admin Ranting');
        $kecamatan = trim($body['kecamatan'] ?? '');
        $desa = trim($body['desa'] ?? '');
        $ranting = trim($body['ranting'] ?? '');

        if (!$username || !$password || !$nama) {
            json_response(['success' => false, 'message' => 'Username, nama, dan password wajib diisi.']);
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            // Cek duplikasi username
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
            $stmt->execute(['u' => $username]);
            if ($stmt->fetch()) {
                json_response(['success' => false, 'message' => "Username '{$username}' sudah digunakan."]);
            }

            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, nama, role, kecamatan, desa, ranting, status) 
                                   VALUES (:u, :p, :n, :r, :k, :d, :rt, 'Aktif')");
            $stmt->execute([
                'u' => $username,
                'p' => $hash,
                'n' => $nama,
                'r' => $role,
                'k' => $kecamatan,
                'd' => $desa,
                'rt' => $ranting
            ]);
            $newId = $pdo->lastInsertId();
        } else {
            $data = Database::getJsonData();
            foreach ($data['users'] as $u) {
                if (strtolower($u['username']) === strtolower($username)) {
                    json_response(['success' => false, 'message' => "Username '{$username}' sudah digunakan."]);
                }
            }
            $newId = count($data['users']) > 0 ? (max(array_column($data['users'], 'id')) + 1) : 1;
            $data['users'][] = [
                'id' => $newId,
                'username' => $username,
                'password_hash' => $hash,
                'nama' => $nama,
                'role' => $role,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'ranting' => $ranting,
                'foto_profil' => '',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s')
            ];
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Tambah Pengguna', (string)$newId, "Menambahkan pengguna baru: {$nama} ({$role})");

        json_response(['success' => true, 'message' => 'Pengguna baru berhasil ditambahkan.']);
        break;

    case 'toggle-status':
        $targetId = (int)($body['rowNumber'] ?? ($body['id'] ?? 0));
        $newStatus = ($body['newStatus'] ?? 'Aktif') === 'Aktif' ? 'Aktif' : 'Nonaktif';

        if ($targetId === $user['id']) {
            json_response(['success' => false, 'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("UPDATE users SET status = :s WHERE id = :id");
            $stmt->execute(['s' => $newStatus, 'id' => $targetId]);
        } else {
            $data = Database::getJsonData();
            foreach ($data['users'] as &$u) {
                if ($u['id'] === $targetId) {
                    $u['status'] = $newStatus;
                    break;
                }
            }
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Ubah Status Pengguna', (string)$targetId, "Mengubah status pengguna #{$targetId} menjadi {$newStatus}");

        json_response(['success' => true, 'message' => "Status pengguna berhasil diubah menjadi {$newStatus}."]);
        break;

    case 'reset-password':
        $targetId = (int)($body['rowNumber'] ?? ($body['id'] ?? 0));
        $newPassword = $body['newPassword'] ?? '';

        if (!$targetId || !$newPassword) {
            json_response(['success' => false, 'message' => 'Password baru tidak boleh kosong.']);
        }

        $hash = password_hash($newPassword, PASSWORD_BCRYPT);

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("UPDATE users SET password_hash = :p WHERE id = :id");
            $stmt->execute(['p' => $hash, 'id' => $targetId]);
        } else {
            $data = Database::getJsonData();
            foreach ($data['users'] as &$u) {
                if ($u['id'] === $targetId) {
                    $u['password_hash'] = $hash;
                    break;
                }
            }
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Reset Password', (string)$targetId, "Mereset password pengguna #{$targetId}");

        json_response(['success' => true, 'message' => 'Password pengguna berhasil direset.']);
        break;

    case 'create-from-pendukung':
        $pendukungId = (int)($body['pendukung_id'] ?? ($body['id'] ?? 0));
        if (!$pendukungId) {
            json_response(['success' => false, 'message' => 'ID pendukung wajib disertakan.'], 400);
        }

        $pendukung = null;
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("SELECT * FROM pendukung WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $pendukungId]);
            $pendukung = $stmt->fetch();
        } else {
            $data = Database::getJsonData();
            foreach ($data['pendukung'] as $p) {
                if ((int)$p['id'] === $pendukungId) {
                    $pendukung = $p;
                    break;
                }
            }
        }

        if (!$pendukung) {
            json_response(['success' => false, 'message' => 'Data pendukung tidak ditemukan.'], 404);
        }

        $nama = trim($body['nama'] ?? $pendukung['nama'] ?? '');
        $kecamatan = trim($body['kecamatan'] ?? $pendukung['kecamatan'] ?? '');
        $desa = trim($body['desa'] ?? $pendukung['desa'] ?? '');
        $ranting = trim($body['ranting'] ?? $pendukung['desa'] ?? '');
        $jalur = strtoupper($pendukung['jalur'] ?? '');

        $role = trim($body['role'] ?? '');
        if (!$role) {
            if ($jalur === 'DPC') {
                $role = 'Koordinator Kecamatan';
            } else if ($jalur === 'DPRT') {
                $role = 'Koordinator Desa';
            } else {
                $role = 'Admin Ranting';
            }
        }

        $username = trim($body['username'] ?? '');
        if (!$username) {
            $cleanHp = preg_replace('/[^0-9]/', '', $pendukung['hp'] ?? '');
            if (strlen($cleanHp) >= 9) {
                $username = $cleanHp;
            } else {
                $slugNama = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));
                if (strlen($slugNama) < 3) $slugNama = 'operator';
                $username = substr($slugNama, 0, 10) . '_' . $pendukungId;
            }
        }

        $baseUsername = $username;
        $counter = 1;
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            while (true) {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
                $stmt->execute(['u' => $username]);
                if (!$stmt->fetch()) {
                    break;
                }
                $username = $baseUsername . '_' . $counter;
                $counter++;
            }
        } else {
            $data = Database::getJsonData();
            while (true) {
                $found = false;
                foreach ($data['users'] as $u) {
                    if (strtolower($u['username']) === strtolower($username)) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) break;
                $username = $baseUsername . '_' . $counter;
                $counter++;
            }
        }

        $passwordPlain = trim($body['password'] ?? '');
        if (!$passwordPlain) {
            $nik = preg_replace('/[^0-9]/', '', $pendukung['nik'] ?? '');
            if (strlen($nik) >= 6) {
                $passwordPlain = substr($nik, -6);
            } else {
                $passwordPlain = '123456';
            }
        }

        $hash = password_hash($passwordPlain, PASSWORD_BCRYPT);

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("INSERT INTO users (pendukung_id, username, password_hash, nama, role, kecamatan, desa, ranting, status) 
                                   VALUES (:pid, :u, :p, :n, :r, :k, :d, :rt, 'Aktif')");
            $stmt->execute([
                'pid' => $pendukungId,
                'u' => $username,
                'p' => $hash,
                'n' => $nama,
                'r' => $role,
                'k' => $kecamatan,
                'd' => $desa,
                'rt' => $ranting
            ]);
            $newId = $pdo->lastInsertId();
        } else {
            $data = Database::getJsonData();
            $newId = count($data['users']) > 0 ? (max(array_column($data['users'], 'id')) + 1) : 1;
            $data['users'][] = [
                'id' => $newId,
                'pendukung_id' => $pendukungId,
                'username' => $username,
                'password_hash' => $hash,
                'nama' => $nama,
                'role' => $role,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'ranting' => $ranting,
                'foto_profil' => '',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s')
            ];
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Jadikan Akun Operator', (string)$newId, "Membuat akun operator dari pendukung #{$pendukungId}: {$nama} ({$role}) - Wilayah: {$kecamatan}/{$desa}");

        json_response([
            'success' => true,
            'message' => "Akun operator untuk '{$nama}' berhasil dibuat.",
            'user' => [
                'id' => $newId,
                'username' => $username,
                'password' => $passwordPlain,
                'nama' => $nama,
                'role' => $role,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'ranting' => $ranting,
                'hp' => $pendukung['hp'] ?? ''
            ]
        ]);
        break;

    case 'reset-password-operator':
        $pendukungId = (int)($body['pendukung_id'] ?? ($body['id'] ?? 0));
        $userId = (int)($body['user_id'] ?? 0);

        if (!$pendukungId && !$userId) {
            json_response(['success' => false, 'message' => 'ID tidak valid.'], 400);
        }

        $targetUser = null;
        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            if ($userId) {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
                $stmt->execute(['id' => $userId]);
                $targetUser = $stmt->fetch();
            } else {
                $stmt = $pdo->prepare("SELECT u.*, p.hp as pendukung_hp, p.nik as pendukung_nik FROM users u 
                                       JOIN pendukung p ON (u.pendukung_id = p.id OR (p.hp != '' AND (u.username = p.hp OR u.username = REPLACE(REPLACE(p.hp, '-', ''), ' ', ''))))
                                       WHERE p.id = :pid LIMIT 1");
                $stmt->execute(['pid' => $pendukungId]);
                $targetUser = $stmt->fetch();
            }
        } else {
            $data = Database::getJsonData();
            foreach ($data['users'] as $u) {
                if ($userId && (int)$u['id'] === $userId) {
                    $targetUser = $u;
                    break;
                }
                if ($pendukungId && ((int)($u['pendukung_id'] ?? 0) === $pendukungId)) {
                    $targetUser = $u;
                    break;
                }
            }
        }

        if (!$targetUser) {
            json_response(['success' => false, 'message' => 'Akun operator tidak ditemukan.'], 404);
        }

        $newPassword = trim($body['newPassword'] ?? '');
        if (!$newPassword) {
            $cleanNik = preg_replace('/[^0-9]/', '', $targetUser['pendukung_nik'] ?? '');
            if (strlen($cleanNik) >= 6) {
                $newPassword = substr($cleanNik, -6);
            } else {
                $newPassword = 'p' . rand(100000, 999999);
            }
        }

        $hash = password_hash($newPassword, PASSWORD_BCRYPT);

        if (Database::isMysql()) {
            $pdo = Database::getPdo();
            $stmt = $pdo->prepare("UPDATE users SET password_hash = :p WHERE id = :id");
            $stmt->execute(['p' => $hash, 'id' => $targetUser['id']]);
        } else {
            foreach ($data['users'] as &$u) {
                if ((int)$u['id'] === (int)$targetUser['id']) {
                    $u['password_hash'] = $hash;
                    break;
                }
            }
            Database::saveJsonData($data);
        }

        log_activity($user['id'], $user['nama'], 'Reset Password Operator', (string)$targetUser['id'], "Mereset password operator #{$targetUser['id']} ({$targetUser['nama']})");

        json_response([
            'success' => true,
            'message' => "Password operator untuk '{$targetUser['nama']}' berhasil direset.",
            'user' => [
                'id' => $targetUser['id'],
                'username' => $targetUser['username'],
                'password' => $newPassword,
                'nama' => $targetUser['nama'],
                'role' => $targetUser['role'],
                'kecamatan' => $targetUser['kecamatan'],
                'desa' => $targetUser['desa'],
                'hp' => $targetUser['pendukung_hp'] ?? ($targetUser['hp'] ?? '')
            ]
        ]);
        break;

    default:
        json_response(['error' => 'Aksi tidak valid'], 400);
}
