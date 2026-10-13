#!/bin/bash
# Skrip Pencadangan Database MySQL Otomatis - Sistem Informasi Gus Dim
# Disimpan di folder privat ~/backups_gusdim/ (di luar public_html)
# Retensi: Menghapus cadangan yang lebih tua dari 7 hari

set -e

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKEND_DIR="$REPO_DIR/backend"
BACKUP_DIR="$HOME/backups_gusdim"

mkdir -p "$BACKUP_DIR"

if [ ! -f "$BACKEND_DIR/.env" ]; then
    echo "Berkas .env tidak ditemukan di $BACKEND_DIR"
    exit 1
fi

DB_NAME=$(grep -E '^DB_DATABASE=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
DB_USER=$(grep -E '^DB_USERNAME=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
DB_PASS=$(grep -E '^DB_PASSWORD=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
DB_HOST=$(grep -E '^DB_HOST=' "$BACKEND_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
DB_HOST=${DB_HOST:-127.0.0.1}

if [ -z "$DB_NAME" ] || [ -z "$DB_USER" ]; then
    echo "Kredensial database tidak lengkap pada .env"
    exit 1
fi

TIMESTAMP=$(date +"%Y-%m-%d_%H%M%S")
BACKUP_FILE="$BACKUP_DIR/backup_gusdim_${TIMESTAMP}.sql.gz"

echo "Membuat cadangan database $DB_NAME..."
mysqldump -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip > "$BACKUP_FILE"

echo "Cadangan berhasil disimpan: $BACKUP_FILE"

# Hapus arsip yang lebih dari 7 hari
find "$BACKUP_DIR" -type f -name "backup_gusdim_*.sql.gz" -mtime +7 -delete

echo "Pencadangan dan pembersihan berkas usang selesai."
