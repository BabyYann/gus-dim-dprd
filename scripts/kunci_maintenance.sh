#!/bin/bash
# Skrip Mengunci Sistem ke Mode Pemeliharaan (Maintenance Mode)
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
BACKEND_DIR="$ROOT_DIR/backend"

echo "=== MENGUNCI SISTEM KE MODE PEMELIHARAAN ==="

# 1. Buat file maintenance.lock
cat << 'EOF' > "$ROOT_DIR/maintenance.lock"
{
  "active": true,
  "locked_at": "$(date -Iseconds)",
  "reason": "Pemeliharaan sistem berkala dan sinkronisasi basis data Dapil Kraksaan Raya",
  "bypass_key": "gusdim2025"
}
EOF
cp -f "$ROOT_DIR/maintenance.lock" "$BACKEND_DIR/maintenance.lock" 2>/dev/null || true

# 2. Jika di cPanel, copy juga ke public_html
if [ -d "$ROOT_DIR/../public_html" ]; then
    cp -f "$ROOT_DIR/maintenance.lock" "$ROOT_DIR/../public_html/maintenance.lock" 2>/dev/null || true
    if [ -f "$BACKEND_DIR/public/503.html" ]; then
        cp -f "$BACKEND_DIR/public/503.html" "$ROOT_DIR/../public_html/503.html" 2>/dev/null || true
    fi
fi
if [[ "$ROOT_DIR" == *"/public_html"* ]]; then
    PUBLIC_HTML_DIR="$(echo "$ROOT_DIR" | sed 's|\(/public_html\).*|\1|')"
    cp -f "$ROOT_DIR/maintenance.lock" "$PUBLIC_HTML_DIR/maintenance.lock" 2>/dev/null || true
    if [ -f "$BACKEND_DIR/public/503.html" ]; then
        cp -f "$BACKEND_DIR/public/503.html" "$PUBLIC_HTML_DIR/503.html" 2>/dev/null || true
    fi
fi

# 3. Jalankan artisan down
if [ -d "$BACKEND_DIR" ]; then
    cd "$BACKEND_DIR"
    php artisan down --render="errors::503" --secret="gusdim2025" 2>/dev/null || php artisan down 2>/dev/null || true
fi

echo "Sistem berhasil DIKUNCI ke Mode Pemeliharaan."
echo "Untuk akses khusus admin: https://domainanda.com/gusdim2025 atau tambahkan ?bypass=gusdim2025"
