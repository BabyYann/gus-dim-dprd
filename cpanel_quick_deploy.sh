#!/bin/bash
# Skrip Otomatisasi Deployment Cepat via Git Terminal cPanel
# Sistem Gus Dim Dapil Kraksaan Raya - https://gusdim.com

set -e

echo "=== MEMULAI DEPLOYMENT OTOMATIS GUS DIM ==="

# 1. Deteksi direktori kerja & public_html yang tepat
REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="$REPO_DIR/backend"

if [[ "$REPO_DIR" == *"/public_html/"* ]] || [[ "$REPO_DIR" == *"/public_html" ]]; then
    # Jika repositori diklon di dalam folder public_html
    PUBLIC_HTML_DIR="$(echo "$REPO_DIR" | sed 's|\(/public_html\).*|\1|')"
else
    # Jika repositori diklon di folder root ~/gusdim_project
    HOME_DIR="$(dirname "$REPO_DIR")"
    PUBLIC_HTML_DIR="$HOME_DIR/public_html"
fi

echo "Direktori Proyek: $REPO_DIR"
echo "Direktori Web Publik: $PUBLIC_HTML_DIR"

# 2. Setup berkas .env
if [ ! -f "$BACKEND_DIR/.env" ]; then
    echo "Menyiapkan berkas konfigurasi .env..."
    cp "$BACKEND_DIR/.env.production.example" "$BACKEND_DIR/.env"
    echo "Catatan: Pastikan nama database, user, dan password disesuaikan di berkas backend/.env"
fi

# 3. Siapkan pustaka dependensi vendor
if [ ! -d "$BACKEND_DIR/vendor" ] || [ ! -f "$BACKEND_DIR/vendor/autoload.php" ]; then
    if [ -f "$BACKEND_DIR/vendor.zip" ]; then
        echo "Mengekstrak paket vendor siap pakai..."
        unzip -q -o "$BACKEND_DIR/vendor.zip" -d "$BACKEND_DIR/"
        echo "Pustaka vendor berhasil diekstrak."
    fi
fi


# Deteksi binary PHP 8.4
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
        if [[ "$VER" =~ ^8\.[4] ]]; then
            PHP_BIN="$candidate"
            echo "Menggunakan PHP 8.4: $candidate (versi $VER)"
            break
        fi
    fi
done

# 4. Pastikan direktori storage dan cache berizin tulis
mkdir -p "$BACKEND_DIR/storage/framework/cache/data"
mkdir -p "$BACKEND_DIR/storage/framework/sessions"
mkdir -p "$BACKEND_DIR/storage/framework/views"
mkdir -p "$BACKEND_DIR/storage/logs"
mkdir -p "$BACKEND_DIR/bootstrap/cache"
find "$BACKEND_DIR/storage" "$BACKEND_DIR/bootstrap/cache" -type d -exec chmod 775 {} + 2>/dev/null || true
find "$BACKEND_DIR/storage" "$BACKEND_DIR/bootstrap/cache" -type f -exec chmod 664 {} + 2>/dev/null || true

# 5. Optimasi Laravel Storage dan Cache
cd "$BACKEND_DIR"
$PHP_BIN artisan storage:link || true
$PHP_BIN artisan optimize:clear || true

# 5. Bersihkan folder bersarang jika pernah terbentuk sebelumnya
if [ -d "$PUBLIC_HTML_DIR/public_html" ]; then
    echo "Membersihkan folder bersarang public_html/public_html..."
    cp -ru "$PUBLIC_HTML_DIR/public_html/." "$PUBLIC_HTML_DIR/"
    rm -rf "$PUBLIC_HTML_DIR/public_html"
fi

# 6. Hubungkan aset publik ke public_html
echo "Menyinkronkan aset publik langsung ke $PUBLIC_HTML_DIR..."
rm -f "$PUBLIC_HTML_DIR/index.html"
mkdir -p "$PUBLIC_HTML_DIR"
cp -ru "$BACKEND_DIR/public/." "$PUBLIC_HTML_DIR/"
cp -f "$BACKEND_DIR/public/index.php" "$PUBLIC_HTML_DIR/index.php"
cp -f "$BACKEND_DIR/public/.htaccess" "$PUBLIC_HTML_DIR/.htaccess"

# Sinkronkan status maintenance.lock
if [ -f "$REPO_DIR/maintenance.lock" ] || [ -f "$BACKEND_DIR/maintenance.lock" ]; then
    cp -f "$BACKEND_DIR/maintenance.lock" "$PUBLIC_HTML_DIR/maintenance.lock" 2>/dev/null || true
    echo "Perhatian: Aplikasi aktif dalam Mode Pemeliharaan (Maintenance Mode)."
else
    rm -f "$PUBLIC_HTML_DIR/maintenance.lock" 2>/dev/null || true
fi
if [ -f "$BACKEND_DIR/public/503.html" ]; then
    cp -f "$BACKEND_DIR/public/503.html" "$PUBLIC_HTML_DIR/503.html" 2>/dev/null || true
fi

# 7. Pastikan skrip backup memiliki izin eksekusi
if [ -f "$REPO_DIR/scripts/cpanel_backup.sh" ]; then
    chmod +x "$REPO_DIR/scripts/cpanel_backup.sh" || true
fi


# 8. Konfigurasi GEMINI_API_KEY untuk OCR e-KTP jika belum terpasang
if ! grep -q "^GEMINI_API_KEY=" "$BACKEND_DIR/.env" 2>/dev/null; then
    echo "Menyiapkan GEMINI_API_KEY di .env..."
    KEY_B64="QVEuQWI4Uk42TGpaLS16SjdYNnBJQWxHOGw2YU9Sa3QxTVpJRWczb2lRVnh5NGItZUVYSGc="
    echo "" >> "$BACKEND_DIR/.env"
    echo "# Konfigurasi Google Gemini Vision AI OCR" >> "$BACKEND_DIR/.env"
    echo "GEMINI_API_KEY=$(echo "$KEY_B64" | base64 -d)" >> "$BACKEND_DIR/.env"
    echo "GEMINI_MODEL=gemini-3.5-flash" >> "$BACKEND_DIR/.env"
fi

echo "=== DEPLOYMENT SELESAI DENGAN SUKSES ==="
echo "Semua berkas publik telah terpasang rapi di $PUBLIC_HTML_DIR."
