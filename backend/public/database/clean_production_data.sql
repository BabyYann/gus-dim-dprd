-- ==========================================================
-- SKRIP PEMBERSIHAN DATA PRODUKSI (BERSIH TOTAL)
-- Sistem Informasi Pemenangan Terpadu Gus Dim
-- Menyisakan: 1 Akun Superadmin Pusat & Master Wilayah Dapil
-- Menghapus: Seluruh Pendukung, Aspirasi, Log, Token & Akun Demo
-- ==========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Kosongkan Tabel Transaksional & Data Simulasi
TRUNCATE TABLE `pendukung`;
TRUNCATE TABLE `aspirasi`;
TRUNCATE TABLE `audit_logs`;
TRUNCATE TABLE `user_tokens`;
TRUNCATE TABLE `personal_access_tokens`;
TRUNCATE TABLE `reses_titik`;
TRUNCATE TABLE `reses_kehadiran`;
TRUNCATE TABLE `pokir_usulan`;

-- 2. Hapus Seluruh Akun Demo/Uji Coba Selain Superadmin
DELETE FROM `users` WHERE `username` != 'superadmin' AND `role` != 'Superadmin';

-- 3. Pastikan Akun Superadmin Pusat Aktif (Password: admin123)
INSERT INTO `users` (`id`, `username`, `nama`, `name`, `password`, `password_hash`, `role`, `status`, `created_at`, `updated_at`)
VALUES (1, 'superadmin', 'Superadmin Pusat', 'Superadmin Pusat', '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u', '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u', 'Superadmin', 'Aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
  `status` = 'Aktif',
  `role` = 'Superadmin',
  `nama` = 'Superadmin Pusat',
  `password` = VALUES(`password`),
  `password_hash` = VALUES(`password_hash`);

-- 4. Catat Log Awal Bersih
INSERT INTO `audit_logs` (`user_id`, `user_nama`, `aksi`, `id_referensi`, `keterangan`, `ip_address`, `created_at`)
VALUES (1, 'Superadmin Pusat', 'Pembersihan Data Produksi', 'CLEAN_SLATE', 'Seluruh data pengujian berhasil dikosongkan untuk persiapan peluncuran resmi', '127.0.0.1', NOW());

SET FOREIGN_KEY_CHECKS = 1;
