#!/bin/bash
# Skrip Membuka Kembali Sistem dari Mode Pemeliharaan (Normal Mode)
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
BACKEND_DIR="$ROOT_DIR/backend"

echo "=== MEMBUKA KEMBALI SISTEM DARI MODE PEMELIHARAAN ==="

# 1. Hapus file maintenance.lock
rm -f "$ROOT_DIR/maintenance.lock"
rm -f "$BACKEND_DIR/maintenance.lock"
rm -f "$BACKEND_DIR/public/maintenance.lock"

# 2. Hapus di public_html jika ada
if [ -d "$ROOT_DIR/../public_html" ]; then
    rm -f "$ROOT_DIR/../public_html/maintenance.lock" 2>/dev/null || true
fi
if [[ "$ROOT_DIR" == *"/public_html"* ]]; then
    PUBLIC_HTML_DIR="$(echo "$ROOT_DIR" | sed 's|\(/public_html\).*|\1|')"
    rm -f "$PUBLIC_HTML_DIR/maintenance.lock" 2>/dev/null || true
fi

# 3. Jalankan artisan up
if [ -d "$BACKEND_DIR" ]; then
    cd "$BACKEND_DIR"
    php artisan up 2>/dev/null || true
    php artisan optimize:clear 2>/dev/null || true
fi

echo "Sistem berhasil DIBUKA kembali ke status Operasional Normal."
