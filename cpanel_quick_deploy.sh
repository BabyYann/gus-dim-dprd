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

# 2. Setup berkas .env
if [ ! -f "$BACKEND_DIR/.env" ]; then
    echo "Menyiapkan berkas konfigurasi .env..."
    cp "$BACKEND_DIR/.env.production.example" "$BACKEND_DIR/.env"
    echo "Catatan: Pastikan nama database, user, dan password disesuaikan di berkas backend/.env"
fi

# 3. Siapkan pustaka dependensi vendor
if [ ! -d "$BACKEND_DIR/vendor" ] || [ ! -f "$BACKEND_DIR/vendor/autoload.php" ]; then
    if [ -f "$BACKEND_DIR/vendor.zip" ]; then
        echo "Mengekstrak paket vendor siap pakai (tanpa butuh Composer)..."
        unzip -q -o "$BACKEND_DIR/vendor.zip" -d "$BACKEND_DIR/"
        echo "Pustaka vendor berhasil diekstrak."
    elif command -v composer &>/dev/null; then
        echo "Menginstal dependensi Composer..."
        cd "$BACKEND_DIR"
        composer install --no-dev --optimize-autoloader --ignore-platform-reqs || true
    fi
fi

# 4. Optimasi Laravel Storage dan Cache
cd "$BACKEND_DIR"
php artisan storage:link || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

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
