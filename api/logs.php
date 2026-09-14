<?php
require_once dirname(__DIR__) . '/config/helpers.php';

$user = get_auth_user();

$logs = [];
if (Database::isMysql()) {
    $pdo = Database::getPdo();
    $stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as waktu, 
                                user_nama as user, aksi, keterangan 
                         FROM audit_logs 
                         ORDER BY id DESC LIMIT 200");
    $logs = $stmt->fetchAll();
} else {
    $data = Database::getJsonData();
    $raw = $data['logs'] ?? [];
    usort($raw, fn($a, $b) => strtotime($b['created_at'] ?? 'now') - strtotime($a['created_at'] ?? 'now'));
    foreach (array_slice($raw, 0, 200) as $l) {
        $logs[] = [
            'waktu' => date('d/m/Y H:i', strtotime($l['created_at'] ?? 'now')),
            'user' => $l['user_nama'] ?? 'Sistem',
            'aksi' => $l['aksi'] ?? '',
            'keterangan' => $l['keterangan'] ?? ''
        ];
    }
}

json_response(['success' => true, 'rows' => $logs]);
