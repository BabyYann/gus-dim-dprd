<?php
/**
 * Uji Coba Port 6001 di Firewall Hosting Gus Dim
 * Jalankan di terminal cPanel:
 *   cd ~/public_html/gusdim_project/backend
 *   php test_port_6001.php
 */

$port = 6001;
$host = '0.0.0.0';

$sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$sock) {
    echo "Gagal membuat socket: " . socket_strerror(socket_last_error()) . "\n";
    exit(1);
}

socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);

if (!@socket_bind($sock, $host, $port)) {
    echo "Gagal bind ke {$host}:{$port}: " . socket_strerror(socket_last_error($sock)) . "\n";
    @socket_close($sock);
    exit(1);
}

if (!@socket_listen($sock, 5)) {
    echo "Gagal listen socket: " . socket_strerror(socket_last_error($sock)) . "\n";
    @socket_close($sock);
    exit(1);
}

echo "=======================================================================\n";
echo "  UJI AKSES PORT {$port} DARI INTERNET LUAR (FIREWALL TEST)\n";
echo "=======================================================================\n";
echo "Server socket aktif mendengarkan di port {$port}...\n";
echo "Waktu tunggu: 60 detik.\n\n";
echo "SEKARANG, buka tautan ini di browser HP atau Laptop Anda:\n";
echo ">>> http://gusdim.com:{$port} <<<\n\n";
echo "Menunggu respons dari perangkat Anda...\n";

socket_set_nonblock($sock);
$startTime = time();
$connected = false;

while (time() - $startTime < 60) {
    $client = @socket_accept($sock);
    if ($client) {
        socket_getpeername($client, $remoteIp);
        echo "\n[SUKSES] Koneksi masuk berhasil diterima dari IP: {$remoteIp}!\n";
        echo "KESIMPULAN: Port {$port} TERBUKA di Firewall cPanel! Browser bisa konek langsung ke Reverb!\n";
        
        $msg = "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\nConnection: close\r\n\r\nPORT_6001_FIREWALL_OPEN - Server Gus Dim siap untuk WebSocket Reverb!";
        socket_write($client, $msg, strlen($msg));
        @socket_close($client);
        $connected = true;
        break;
    }
    usleep(200000); // 0.2 detik
}

if (!$connected) {
    echo "\n[TIMEOUT 60 DETIK] Tidak ada koneksi yang berhasil masuk dari luar.\n";
    echo "KESIMPULAN: Port {$port} DIBLOKIR oleh Firewall Hosting dari akses publik langsung.\n";
    echo "SOLUSI: Kita akan menggunakan teknik REVERSE PROXY APACHE (.htaccess) via Port 443 (SSL).\n";
    echo "Dengan cara ini, WebSocket tetap jalan lewat https://gusdim.com/reverb tanpa butuh port terbuka.\n";
}

@socket_close($sock);
echo "=======================================================================\n";
