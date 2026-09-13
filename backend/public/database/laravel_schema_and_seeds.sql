-- ==========================================================
-- DUMP STRUKTUR & DATA AWAL LARAVEL SISTEM "GUS DIM"
-- Untuk Import Langsung via phpMyAdmin cPanel (Tanpa Butuh SSH)
-- ==========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. TABEL USERS (DENGAN ROLE & WILAYAH)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- 2. TABEL PERSONAL ACCESS TOKENS (SANCTUM)
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

-- ==========================================================
-- SEED DATA AWAL
-- ==========================================================

-- Data Pengguna Default
-- Password superadmin: admin123
-- Password role lainnya: password123
INSERT INTO `users` (`username`, `nama`, `name`, `password`, `role`, `kecamatan`, `desa`, `ranting`, `status`, `created_at`, `updated_at`) VALUES
('superadmin', 'Superadmin Pusat', 'Superadmin Pusat', '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u', 'Superadmin', NULL, NULL, NULL, 'Aktif', NOW(), NOW()),
('korcam_kraksaan', 'H. Mansyur (Korcam)', 'H. Mansyur (Korcam)', '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne', 'Koordinator Kecamatan', 'Kraksaan', NULL, NULL, 'Aktif', NOW(), NOW()),
('kordes_wetan', 'Ust. Bahri (Kordes)', 'Ust. Bahri (Kordes)', '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne', 'Koordinator Desa', 'Kraksaan', 'Kraksaan Wetan', NULL, 'Aktif', NOW(), NOW()),
('ranting_kraksaan', 'Admin Ranting Kraksaan Kota', 'Admin Ranting Kraksaan Kota', '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne', 'Admin Ranting', 'Kraksaan', 'Kraksaan Wetan', 'Ranting Kraksaan Kota', 'Aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

-- Referensi Wilayah Dapil Kraksaan Raya
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

-- Contoh Pendukung Awal
INSERT INTO `pendukung` (`jalur`, `nik`, `nama`, `hp`, `umur`, `jabatan`, `koordinator`, `alamat`, `kecamatan`, `desa`, `latitude`, `longitude`, `status`, `catatan`, `input_by_user_id`, `input_by_user_name`, `created_at`, `updated_at`) VALUES
('DPC', '3513121508820001', 'Ahmad Fauzi, S.Pd', '081234567890', 44, 'Ketua DPC', NULL, 'Jl. Panglima Sudirman No. 45', 'Kraksaan', 'Kraksaan Wetan', -7.7580, 113.4150, 'Final', 'Tokoh masyarakat Kraksaan', 1, 'Superadmin Pusat', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
('DPRT', '3513125203890002', 'Siti Aminah', '085233445566', 37, 'Sekretaris DPRT', NULL, 'Dusun Krajan RT 02/RW 01', 'Kraksaan', 'Kraksaan Kulon', -7.7550, 113.4090, 'Divalidasi Kecamatan', 'Koordinator ibu-ibu pengajian', 4, 'Admin Ranting Kraksaan Kota', DATE_SUB(NOW(), INTERVAL 4 DAY), NOW()),
('PIP', '3513120101080003', 'Rizky Ramadhan', '087811223344', 18, NULL, NULL, 'Jl. Ikan Paus RT 03/RW 02', 'Kraksaan', 'Semampir', -7.7480, 113.4210, 'Diverifikasi Desa', 'Siswa SMAN 1 Kraksaan berprestasi', 4, 'Admin Ranting Kraksaan Kota', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
('KIP', '3513134511030004', 'Putri Ayu Lestari', '089677889900', 23, NULL, NULL, 'Dusun Timur RT 01/RW 04', 'Besuk', 'Besuk Kidul', -7.8020, 113.4420, 'Diinput', 'Mahasiswi Universitas Nurul Jadid', 2, 'H. Mansyur (Korcam)', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()),
('RELAWAN', '3513141006950005', 'Bambang Sutrisno', '082199887766', 31, 'Anggota', 'Relawan Sayap Muda Gus Dim', 'Jl. Raya Gading No. 12', 'Gading', 'Gading Wetan', -7.8500, 113.4600, 'Final', 'Koordinator pemuda Gading', 1, 'Superadmin Pusat', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW());

-- Log Audit Awal
INSERT INTO `audit_logs` (`user_id`, `user_nama`, `aksi`, `id_referensi`, `keterangan`, `ip_address`, `created_at`) VALUES
(1, 'Superadmin Pusat', 'Inisialisasi Sistem Laravel', 'INIT_LARAVEL', 'Inisialisasi database dan instalasi framework Laravel 11 berhasil dilakukan', '127.0.0.1', NOW());


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
