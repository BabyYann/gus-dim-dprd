#!/bin/bash
# Skrip Otomatisasi Deployment Cepat via Git Terminal cPanel
# Sistem Gus Dim Dapil Kraksaan Raya - https://gusdim.com

set -e

echo "=== MEMULAI DEPLOYMENT OTOMATIS GUS DIM ==="

# 1. Deteksi direktori kerja
REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="$REPO_DIR/backend"
HOME_DIR="$(dirname "$REPO_DIR")"
PUBLIC_HTML_DIR="$HOME_DIR/public_html"

echo "Direktori Proyek: $REPO_DIR"

# Deteksi binary PHP terbaik di cPanel (prioritas 8.4 / 8.3)
PHP_BIN="php"
for candidate in \
    "/opt/cpanel/ea-php84/root/usr/bin/php" \
    "/usr/local/bin/ea-php84" \
    "/opt/alt/php84/usr/bin/php" \
    "/opt/cpanel/ea-php83/root/usr/bin/php" \
    "/usr/local/bin/ea-php83" \
    "/opt/alt/php83/usr/bin/php" \
    "$(which ea-php84 2>/dev/null)" \
    "$(which ea-php83 2>/dev/null)" \
    "$(which php 2>/dev/null)"; do
    if [ -x "$candidate" ]; then
        VER=$($candidate -r "echo PHP_VERSION;" 2>/dev/null || true)
        if [[ "$VER" =~ ^8\.[34] ]]; then
            PHP_BIN="$candidate"
            echo "Menggunakan PHP kompatibel: $candidate (versi $VER)"
            break
        fi
    fi
done

if [ "$PHP_BIN" = "php" ]; then
    echo "Menggunakan PHP default server: $(php -r 'echo PHP_VERSION;' 2>/dev/null || echo 'unknown')"
fi

# 2. Setup berkas .env
if [ ! -f "$BACKEND_DIR/.env" ]; then
    echo "Menyiapkan berkas konfigurasi .env..."
    cp "$BACKEND_DIR/.env.production.example" "$BACKEND_DIR/.env"
    echo "Catatan: Pastikan nama database, user, dan password disesuaikan di berkas backend/.env"
fi

# 3. Instal dependensi Composer jika vendor belum ada
if [ ! -d "$BACKEND_DIR/vendor" ]; then
    echo "Menginstal dependensi Composer..."
    cd "$BACKEND_DIR"
    $PHP_BIN $(which composer) install --no-dev --optimize-autoloader --ignore-platform-reqs
fi

# 4. Optimasi Laravel Storage dan Cache
cd "$BACKEND_DIR"
$PHP_BIN artisan storage:link || true
$PHP_BIN artisan config:cache || true
$PHP_BIN artisan route:cache || true
$PHP_BIN artisan view:cache || true

# 5. Hubungkan ke public_html
echo "Menyinkronkan aset publik ke public_html..."
mkdir -p "$PUBLIC_HTML_DIR"
cp -ru "$BACKEND_DIR/public/." "$PUBLIC_HTML_DIR/"

echo "=== DEPLOYMENT SELESAI DENGAN SUKSES ==="
echo "Langkah berikutnya:"
echo "1. Buat database MySQL di cPanel (MySQL Database Wizard)."
echo "2. Impor database dari berkas dist/production_clean_schema.sql di phpMyAdmin."
echo "3. Edit backend/.env untuk mencocokkan nama database dan password cPanel Anda."
echo "4. Buka https://gusdim.com pada peramban Anda."
