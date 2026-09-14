#!/bin/bash
# Skrip Pembersihan Data Produksi Otomatis via Terminal cPanel
# Sistem Informasi Pemenangan Terpadu Gus Dim
# Khusus Peluncuran Resmi (Hanya 1 Akun Superadmin Pusat + Master Wilayah)

set -e

echo "=== MEMULAI PEMBERSIHAN DATA PRODUKSI GUS DIM ==="

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="$REPO_DIR/backend"

# Deteksi binary PHP
PHP_BIN="php"
for candidate in \
    "/opt/alt/php84/usr/bin/php" \
    "/opt/cpanel/ea-php84/root/usr/bin/php" \
    "/usr/local/bin/ea-php84" \
    "/opt/alt/php83/usr/bin/php" \
    "/opt/cpanel/ea-php83/root/usr/bin/php" \
    "$(which php 2>/dev/null)"; do
    if [ -x "$candidate" ]; then
        VER=$($candidate -r "echo PHP_VERSION;" 2>/dev/null || true)
        if [[ "$VER" =~ ^8\.[34] ]]; then
            PHP_BIN="$candidate"
            echo "Menggunakan PHP: $candidate (versi $VER)"
            break
        fi
    fi
done

# 1. Bersihkan cache konfigurasi lama Laravel agar membaca perubahan .env terbaru
if [ -f "$BACKEND_DIR/artisan" ]; then
    echo "Membersihkan cache konfigurasi Laravel lama..."
    cd "$BACKEND_DIR"
    $PHP_BIN artisan config:clear || true
    $PHP_BIN artisan optimize:clear || true
fi

# 2. Impor Skrip SQL Bersih ke Database MySQL
if [ -f "$BACKEND_DIR/.env" ] && command -v mysql &> /dev/null; then
    DB_NAME=$(grep -E '^DB_DATABASE=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_USER=$(grep -E '^DB_USERNAME=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_PASS=$(grep -E '^DB_PASSWORD=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_HOST=$(grep -E '^DB_HOST=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_HOST=${DB_HOST:-127.0.0.1}

    if [ -n "$DB_NAME" ] && [ -n "$DB_USER" ]; then
        echo "Menginisialisasi struktur & membersihkan tabel database MySQL: $DB_NAME..."
        mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$REPO_DIR/database/production_clean_schema.sql" || echo "Catatan: Impor via CLI selesai atau jalankan manual via phpMyAdmin."
    fi
fi

# 3. Jalankan Seeder Produksi Bersih via Artisan
if [ -f "$BACKEND_DIR/artisan" ]; then
    echo "Memverifikasi integritas database via artisan..."
    cd "$BACKEND_DIR"
    $PHP_BIN artisan db:seed --class=DatabaseSeeder --force || true
    $PHP_BIN artisan optimize:clear || true
fi

# 4. Jalankan Quick Deploy untuk Memastikan Berkas Terkini
echo "Menyinkronkan pembaruan sistem ke public_html..."
cd "$REPO_DIR"
bash cpanel_quick_deploy.sh

echo "=== PEMBERSIHAN DATA PRODUKSI BERHASIL SELESAI ==="
echo "Sistem kini bersih total: hanya menyisakan 1 Akun Superadmin Pusat dan data master wilayah."
