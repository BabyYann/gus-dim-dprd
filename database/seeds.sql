-- ==========================================================
-- SEED DATA BERSIH: SISTEM GUS DIM (HANYA SUPERADMIN & WILAYAH)
-- ==========================================================

SET NAMES utf8mb4;

-- 1. SEED PENGGUNA DEFAULT (SUPERADMIN TUNGGAL)
-- Password superadmin: admin123
INSERT INTO `users` (`username`, `password_hash`, `nama`, `role`, `status`) VALUES
('superadmin', '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u', 'Superadmin Pusat', 'Superadmin', 'Aktif')
ON DUPLICATE KEY UPDATE `status` = 'Aktif';

-- 2. SEED REFERENSI WILAYAH (DAPIL KRAKSAAN RAYA)
INSERT INTO `wilayah_referensi` (`kecamatan`, `desa`, `lat_default`, `lng_default`) VALUES
-- Kecamatan Kraksaan
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

-- Kecamatan Besuk
('Besuk', 'Besuk Kidul', -7.8020, 113.4420),
('Besuk', 'Besuk Agung', -7.8100, 113.4510),
('Besuk', 'Alaskandang', -7.8150, 113.4350),
('Besuk', 'Bago', -7.8200, 113.4480),
('Besuk', 'Klampokan', -7.8250, 113.4600),
('Besuk', 'Randujati', -7.8300, 113.4400),
('Besuk', 'Sindetlami', -7.8350, 113.4550),
('Besuk', 'Sumbersuko', -7.8400, 113.4300),

-- Kecamatan Gading
('Gading', 'Gading Wetan', -7.8500, 113.4600),
('Gading', 'Gading Kulon', -7.8550, 113.4500),
('Gading', 'Randujalak', -7.8600, 113.4700),
('Gading', 'Mojolegi', -7.8650, 113.4550),
('Gading', 'Kalisat', -7.8700, 113.4650),
('Gading', 'Prasi', -7.8750, 113.4800),
('Gading', 'Sentul', -7.8800, 113.4600),
('Gading', 'Batur', -7.8850, 113.4750)
ON DUPLICATE KEY UPDATE `lat_default` = VALUES(`lat_default`);
