<?php
/**
 * Web Installer Sistem Manajemen Gus Dim untuk cPanel & Server
 */
$configFile = __DIR__ . '/config/config.php';
$schemaFile = __DIR__ . '/database/schema.sql';
$seedsFile = __DIR__ . '/database/seeds.sql';

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_install'])) {
    $dbHost = trim($_POST['db_host'] ?? 'localhost');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = trim($_POST['db_pass'] ?? '');

    if (!$dbName || !$dbUser) {
        $message = 'Nama database dan username database wajib diisi.';
        $status = 'error';
    } else {
        try {
            $dsn = "mysql:host={$dbHost};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            
            // Buat database jika belum ada
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$dbName}`");

            // Eksekusi Schema
            if (file_exists($schemaFile)) {
                $sqlSchema = file_get_contents($schemaFile);
                $pdo->exec($sqlSchema);
            }

            // Eksekusi Seeds
            if (file_exists($seedsFile)) {
                $sqlSeeds = file_get_contents($seedsFile);
                $pdo->exec($sqlSeeds);
            }

            // Update config/config.php
            $configContent = file_get_contents($configFile);
            $configContent = preg_replace("/define\('DB_HOST',\s*'[^']*'\);/", "define('DB_HOST', '{$dbHost}');", $configContent);
            $configContent = preg_replace("/define\('DB_NAME',\s*'[^']*'\);/", "define('DB_NAME', '{$dbName}');", $configContent);
            $configContent = preg_replace("/define\('DB_USER',\s*'[^']*'\);/", "define('DB_USER', '{$dbUser}');", $configContent);
            $configContent = preg_replace("/define\('DB_PASS',\s*'[^']*'\);/", "define('DB_PASS', '{$dbPass}');", $configContent);
            file_put_contents($configFile, $configContent);

            $message = 'Selamat! Instalasi database MySQL dan konfigurasi cPanel berhasil diselesaikan.';
            $status = 'success';
        } catch (Exception $e) {
            $message = 'Gagal menginstal database: ' . $e->getMessage();
            $status = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instalasi Sistem GUS DIM (cPanel Ready)</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --biru-tua: #16225e;
      --biru-terang: #2f5fd6;
      --kuning: #ffb703;
      --bg: #f4f7ff;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: #1e293b;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }
    .install-box {
      background: #fff;
      padding: 35px;
      border-radius: 16px;
      width: 100%;
      max-width: 520px;
      box-shadow: 0 10px 40px rgba(22,34,94,0.12);
      border-top: 6px solid var(--kuning);
    }
    h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 22px;
      color: var(--biru-tua);
      margin-top: 0;
      margin-bottom: 6px;
    }
    p.sub {
      color: #64748b;
      font-size: 13px;
      margin-bottom: 24px;
    }
    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 20px;
    }
    .alert-error {
      background: #fee2e2;
      color: #b91c1c;
      border: 1px solid #f87171;
    }
    .alert-success {
      background: #dcfce7;
      color: #15803d;
      border: 1px solid #86efac;
    }
    .form-group {
      margin-bottom: 16px;
    }
    label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 6px;
    }
    input {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 14px;
      transition: all .2s;
    }
    input:focus {
      outline: none;
      border-color: var(--biru-terang);
      box-shadow: 0 0 0 3px rgba(47,95,214,0.15);
    }
    .btn {
      width: 100%;
      padding: 12px;
      background: var(--biru-tua);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
    }
    .btn:hover {
      background: var(--biru-terang);
    }
    .btn-green {
      background: #16a34a;
    }
    .btn-green:hover {
      background: #15803d;
    }
    .info-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      padding: 14px;
      border-radius: 8px;
      font-size: 13px;
      margin-top: 18px;
      color: #334155;
    }
  </style>
</head>
<body>
  <div class="install-box">
    <h1>Instalasi Sistem GUS DIM</h1>
    <p class="sub">Setup otomatis Database MySQL untuk Server cPanel & Domain Anda</p>

    <?php if ($message): ?>
      <div class="alert alert-<?= $status ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($status === 'success'): ?>
      <div class="info-card">
        <b>Akun Superadmin Default:</b><br>
        Username: <code>superadmin</code><br>
        Password: <code>admin123</code><br><br>
        <small style="color:#ef4444;">*Demi keamanan, silakan hapus file <code>install.php</code> ini setelah selesai.</small>
      </div>
      <a href="index.html" style="text-decoration:none;"><button type="button" class="btn btn-green">Buka Aplikasi Sekarang &rarr;</button></a>
    <?php else: ?>
      <form method="POST">
        <input type="hidden" name="do_install" value="1">
        <div class="form-group">
          <label>Host Database (Biasanya 'localhost' di cPanel)</label>
          <input type="text" name="db_host" value="localhost" required>
        </div>
        <div class="form-group">
          <label>Nama Database MySQL (contoh: cpaneluser_gusdim)</label>
          <input type="text" name="db_name" placeholder="Masukkan nama database" required>
        </div>
        <div class="form-group">
          <label>Username Database MySQL (contoh: cpaneluser_admin)</label>
          <input type="text" name="db_user" placeholder="Masukkan username database" required>
        </div>
        <div class="form-group">
          <label>Password Database MySQL</label>
          <input type="password" name="db_pass" placeholder="Masukkan password database">
        </div>
        <button type="submit" class="btn">Mulai Instalasi Otomatis</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
