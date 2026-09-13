-- ==========================================================
-- SISTEM MANAJEMEN DATA PENDUKUNG & RELAWAN DPRD "GUS DIM"
-- Dapil Kraksaan Raya (Kraksaan, Besuk, Gading - Probolinggo)
-- Database Schema MySQL / MariaDB (InnoDB, UTF-8)
-- ==========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. TABEL PENGGUNA (USERS)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `role` ENUM('Superadmin', 'Koordinator Kecamatan', 'Koordinator Desa', 'Admin Ranting') NOT NULL DEFAULT 'Admin Ranting',
  `kecamatan` VARCHAR(50) DEFAULT NULL,
  `desa` VARCHAR(50) DEFAULT NULL,
  `ranting` VARCHAR(50) DEFAULT NULL,
  `foto_profil` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_username` (`username`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL TOKEN SESI (API TOKENS)
CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `token` VARCHAR(128) NOT NULL UNIQUE,
  `expires_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_token` (`token`),
  CONSTRAINT `fk_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL DATA PENDUKUNG & KONSTITUEN (PENDUKUNG)
CREATE TABLE IF NOT EXISTS `pendukung` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `jalur` ENUM('DPC', 'DPRT', 'PIP', 'KIP', 'RELAWAN') NOT NULL,
  `nik` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(150) NOT NULL,
  `hp` VARCHAR(25) DEFAULT NULL,
  `umur` INT DEFAULT NULL,
  `jabatan` VARCHAR(100) DEFAULT NULL,
  `koordinator` VARCHAR(100) DEFAULT NULL,
  `alamat` TEXT DEFAULT NULL,
  `kecamatan` VARCHAR(50) DEFAULT NULL,
  `desa` VARCHAR(50) DEFAULT NULL,
  `latitude` DECIMAL(10, 8) DEFAULT NULL,
  `longitude` DECIMAL(11, 8) DEFAULT NULL,
  `foto_wajah` VARCHAR(255) DEFAULT NULL,
  `foto_ktp` VARCHAR(255) DEFAULT NULL,
  `data_khusus` JSON DEFAULT NULL,
  `status` ENUM('Diinput', 'Diverifikasi Desa', 'Divalidasi Kecamatan', 'Final', 'Ditolak') NOT NULL DEFAULT 'Diinput',
  `catatan` TEXT DEFAULT NULL,
  `input_by_user_id` INT DEFAULT NULL,
  `input_by_user_name` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_nik` (`nik`),
  INDEX `idx_nama` (`nama`),
  INDEX `idx_wilayah` (`kecamatan`, `desa`),
  INDEX `idx_jalur` (`jalur`),
  INDEX `idx_status` (`status`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL LOG AUDIT AKTIVITAS
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `user_nama` VARCHAR(100) NOT NULL,
  `aksi` VARCHAR(100) NOT NULL,
  `id_referensi` VARCHAR(50) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_log_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL REFERENSI WILAYAH (DAPIL KRAKSAAN RAYA)
CREATE TABLE IF NOT EXISTS `wilayah_referensi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kecamatan` VARCHAR(50) NOT NULL,
  `desa` VARCHAR(50) NOT NULL,
  `lat_default` DECIMAL(10, 8) DEFAULT NULL,
  `lng_default` DECIMAL(11, 8) DEFAULT NULL,
  UNIQUE KEY `uk_kec_desa` (`kecamatan`, `desa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 6. TABEL ASPIRASI WARGA
CREATE TABLE IF NOT EXISTS `aspirasi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(150) NOT NULL,
  `hp` VARCHAR(25) DEFAULT NULL,
  `kecamatan` VARCHAR(50) DEFAULT NULL,
  `desa` VARCHAR(50) DEFAULT NULL,
  `jalur` VARCHAR(50) DEFAULT 'Relawan',
  `kategori` VARCHAR(50) DEFAULT 'Umum',
  `aspirasi` TEXT NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Baru',
  `tanggal` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_aspirasi_wilayah` (`kecamatan`, `desa`),
  INDEX `idx_aspirasi_status` (`status`),
  INDEX `idx_aspirasi_kategori` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
