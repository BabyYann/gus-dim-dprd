-- ==========================================================
-- DUMP STRUKTUR PRODUKSI BERSIH SISTEM "GUS DIM"
-- Khusus Peluncuran Resmi (1 Akun Superadmin + Referensi Wilayah)
-- Seluruh Data Pendukung, Aspirasi, dan Log Masih Kosong Murni
-- Domain: https://gusdim.com
-- ==========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. TABEL USERS
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('Superadmin','Koordinator Kecamatan','Koordinator Desa','Admin Ranting') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin Ranting',
  `kecamatan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ranting` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Aktif','Nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL TOKENS SESI (USER TOKENS & SANCTUM)
DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE `user_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_tokens_token_unique` (`token`),
  KEY `user_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL PENDUKUNG (KONSTITUEN MULTI-JALUR)
DROP TABLE IF EXISTS `pendukung`;
CREATE TABLE `pendukung` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jalur` enum('DPC','DPRT','PIP','KIP','RELAWAN') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hp` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `umur` int DEFAULT NULL,
  `jabatan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `koordinator` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kecamatan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `foto_wajah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_khusus` json DEFAULT NULL,
  `status` enum('Diinput','Diverifikasi Desa','Divalidasi Kecamatan','Final','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diinput',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `input_by_user_id` bigint unsigned DEFAULT NULL,
  `input_by_user_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pendukung_jalur_index` (`jalur`),
  KEY `pendukung_nik_index` (`nik`),
  KEY `pendukung_nama_index` (`nama`),
  KEY `pendukung_kecamatan_index` (`kecamatan`),
  KEY `pendukung_desa_index` (`desa`),
  KEY `pendukung_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL AUDIT LOGS
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aksi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_referensi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL REFERENSI WILAYAH
DROP TABLE IF EXISTS `wilayah_referensi`;
CREATE TABLE `wilayah_referensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kecamatan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desa` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat_default` decimal(10,8) DEFAULT NULL,
  `lng_default` decimal(11,8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wilayah_referensi_kecamatan_desa_unique` (`kecamatan`,`desa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABEL ASPIRASI WARGA
DROP TABLE IF EXISTS `aspirasi`;
CREATE TABLE `aspirasi` (
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

-- 7. TABEL RESES TITIK
DROP TABLE IF EXISTS `reses_titik`;
CREATE TABLE `reses_titik` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `masa_sidang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Masa Sidang I 2026',
  `kecamatan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desa` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dusun` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_tuan_rumah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '13:30',
  `target_peserta` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Masyarakat Umum',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. TABEL RESES KEHADIRAN
DROP TABLE IF EXISTS `reses_kehadiran`;
CREATE TABLE `reses_kehadiran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reses_id` bigint unsigned NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dusun` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_peserta` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Konstituen Reses',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reses_kehadiran_reses_id_index` (`reses_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. TABEL POKIR USULAN
DROP TABLE IF EXISTS `pokir_usulan`;
CREATE TABLE `pokir_usulan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reses_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Infrastruktur',
  `kecamatan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desa` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dusun` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimasi_anggaran` decimal(15,2) DEFAULT '0.00',
  `nama_pengusul` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_pengusul` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `status_tahap` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Aspirasi Reses',
  `catatan_progres` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================================
-- DATA AWAL BERSIH (HANYA 1 SUPERADMIN & REFERENSI WILAYAH DAPIL)
-- ==========================================================

-- Akun Tunggal Superadmin (Password: admin123)
INSERT INTO `users` (`username`, `nama`, `name`, `password`, `password_hash`, `role`, `kecamatan`, `desa`, `ranting`, `status`, `created_at`, `updated_at`) VALUES
('superadmin', 'Superadmin Pusat', 'Superadmin Pusat', '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u', '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u', 'Superadmin', NULL, NULL, NULL, 'Aktif', NOW(), NOW());

-- Master Referensi Wilayah Dapil Kraksaan Raya
INSERT INTO `wilayah_referensi` (`kecamatan`, `desa`, `lat_default`, `lng_default`) VALUES
('Kraksaan', 'Kraksaan Wetan', -7.7580, 113.4150),
('Kraksaan', 'Kraksaan Kulon', -7.7550, 113.4090),
('Kraksaan', 'Semampir', -7.7480, 113.4210),
('Kraksaan', 'Kalibuntu', -7.7320, 113.4100),
('Kraksaan', 'Sidopekso', -7.7650, 113.4250),
('Kraksaan', 'Patokan', -7.7620, 113.4120),
('Kraksaan', 'Kandangjati Kulon', -7.7690, 113.4040),
('Kraksaan', 'Kandangjati Wetan', -7.7710, 113.4160),
('Kraksaan', 'Bulubranti', -7.7780, 113.4300),
('Kraksaan', 'Asembagus', -7.7850, 113.4180),
('Kraksaan', 'Rondokuning', -7.7900, 113.4050),
('Kraksaan', 'Kebonagung', -7.7950, 113.4120),
('Besuk', 'Besuk Kidul', -7.8020, 113.4420),
('Besuk', 'Besuk Agung', -7.8100, 113.4510),
('Besuk', 'Alaskandang', -7.8150, 113.4350),
('Besuk', 'Bago', -7.8200, 113.4480),
('Besuk', 'Klampokan', -7.8250, 113.4600),
('Besuk', 'Randujati', -7.8300, 113.4400),
('Besuk', 'Sindetlami', -7.8350, 113.4550),
('Besuk', 'Sumbersuko', -7.8400, 113.4300),
('Gading', 'Gading Wetan', -7.8500, 113.4600),
('Gading', 'Gading Kulon', -7.8550, 113.4500),
('Gading', 'Randujalak', -7.8600, 113.4700),
('Gading', 'Mojolegi', -7.8650, 113.4550),
('Gading', 'Kalisat', -7.8700, 113.4650),
('Gading', 'Prasi', -7.8750, 113.4800),
('Gading', 'Sentul', -7.8800, 113.4600),
('Gading', 'Batur', -7.8850, 113.4750)
ON DUPLICATE KEY UPDATE `lat_default` = VALUES(`lat_default`);

SET FOREIGN_KEY_CHECKS = 1;