#!/bin/bash
# =======================================================================
# REVERB DAEMON KEEP-ALIVE SCRIPT UNTUK CPANEL
# =======================================================================
# Menjaga server WebSocket Laravel Reverb tetap berjalan 24/7 di cPanel.
# Jadwalkan skrip ini di cPanel Cron Job (Interval: Setiap 1 Menit):
# * * * * * /bin/bash /home/gusdimco/public_html/gusdim_project/backend/reverb_keepalive.sh > /dev/null 2>&1

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# 1. Deteksi binary PHP 8.4 cPanel
PHP_BIN="php"
for candidate in \
    "/opt/alt/php84/usr/bin/php" \
    "/opt/cpanel/ea-php84/root/usr/bin/php" \
    "/usr/local/bin/ea-php84" \
    "/opt/alt/php83/usr/bin/php" \
    "$(which php 2>/dev/null)"; do
    if [ -x "$candidate" ]; then
        VER=$($candidate -r "echo PHP_VERSION;" 2>/dev/null || true)
        if [[ "$VER" =~ ^8\.[2-9] ]]; then
            PHP_BIN="$candidate"
            break
        fi
    fi
done

# 2. Buat folder log jika belum ada
mkdir -p "$SCRIPT_DIR/storage/logs"

# 3. Periksa apakah proses reverb:start sudah berjalan
if ! pgrep -f "artisan reverb:start" > /dev/null 2>&1; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Reverb sedang tidak aktif. Menjalankan ulang server WebSocket..." >> "$SCRIPT_DIR/storage/logs/reverb_daemon.log"
    nohup $PHP_BIN artisan reverb:start --host=0.0.0.0 --port=6001 > "$SCRIPT_DIR/storage/logs/reverb.log" 2>&1 &
fi
