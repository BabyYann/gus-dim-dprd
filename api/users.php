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

            $stmt = $pdo->prepare("INSERT INTO users (username, password, password_hash, nama, name, role, kecamatan, desa, ranting, status) 
                                   VALUES (:u, :p, :p, :n, :n, :r, :k, :d, :rt, 'Aktif')");
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
            $stmt = $pdo->prepare("UPDATE users SET password_hash = :p, password = :p WHERE id = :id");
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

    default:
        json_response(['error' => 'Aksi tidak valid'], 400);
}
