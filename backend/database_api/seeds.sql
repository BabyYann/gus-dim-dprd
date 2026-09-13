-- ==========================================================
-- SEED DATA AWAL: SISTEM GUS DIM
-- ==========================================================

SET NAMES utf8mb4;

-- 1. SEED PENGGUNA DEFAULT
-- Password superadmin: admin123
-- Password role lainnya: password123
INSERT INTO `users` (`username`, `password_hash`, `nama`, `role`, `kecamatan`, `desa`, `ranting`, `status`) VALUES
('superadmin', '$2y$12$RHrimOtniIZQc8mOO7WdaegtkVChq5D8WcK8WWhJ2CoAindeAOs0u', 'Superadmin Pusat', 'Superadmin', NULL, NULL, NULL, 'Aktif'),
('korcam_kraksaan', '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne', 'H. Mansyur (Korcam)', 'Koordinator Kecamatan', 'Kraksaan', NULL, NULL, 'Aktif'),
('kordes_wetan', '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne', 'Ust. Bahri (Kordes)', 'Koordinator Desa', 'Kraksaan', 'Kraksaan Wetan', NULL, 'Aktif'),
('ranting_kraksaan', '$2y$12$cWtQjSrwcYAJrQUQVjJ5CuPtbnQoHG.PZe4VTskvAt17c/yUNG3Ne', 'Admin Ranting Kraksaan Kota', 'Admin Ranting', 'Kraksaan', 'Kraksaan Wetan', 'Ranting Kraksaan Kota', 'Aktif')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`);

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

-- 3. SEED CONTOH DATA PENDUKUNG REALISTIS
INSERT INTO `pendukung` (`jalur`, `nik`, `nama`, `hp`, `umur`, `jabatan`, `koordinator`, `alamat`, `kecamatan`, `desa`, `latitude`, `longitude`, `status`, `catatan`, `input_by_user_id`, `input_by_user_name`, `created_at`) VALUES
('DPC', '3513121508820001', 'Ahmad Fauzi, S.Pd', '081234567890', 44, 'Ketua DPC', NULL, 'Jl. Panglima Sudirman No. 45', 'Kraksaan', 'Kraksaan Wetan', -7.7580, 113.4150, 'Final', 'Tokoh masyarakat Kraksaan', 1, 'Superadmin Pusat', DATE_SUB(NOW(), INTERVAL 5 DAY)),
('DPRT', '3513125203890002', 'Siti Aminah', '085233445566', 37, 'Sekretaris DPRT', NULL, 'Dusun Krajan RT 02/RW 01', 'Kraksaan', 'Kraksaan Kulon', -7.7550, 113.4090, 'Divalidasi Kecamatan', 'Koordinator ibu-ibu pengajian', 4, 'Admin Ranting Kraksaan Kota', DATE_SUB(NOW(), INTERVAL 4 DAY)),
('PIP', '3513120101080003', 'Rizky Ramadhan', '087811223344', 18, NULL, NULL, 'Jl. Ikan Paus RT 03/RW 02', 'Kraksaan', 'Semampir', -7.7480, 113.4210, 'Diverifikasi Desa', 'Siswa SMAN 1 Kraksaan berprestasi', 4, 'Admin Ranting Kraksaan Kota', DATE_SUB(NOW(), INTERVAL 3 DAY)),
('KIP', '3513134511030004', 'Putri Ayu Lestari', '089677889900', 23, NULL, NULL, 'Dusun Timur RT 01/RW 04', 'Besuk', 'Besuk Kidul', -7.8020, 113.4420, 'Diinput', 'Mahasiswi Universitas Nurul Jadid', 2, 'H. Mansyur (Korcam)', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('RELAWAN', '3513141006950005', 'Bambang Sutrisno', '082199887766', 31, 'Anggota', 'Relawan Sayap Muda Gus Dim', 'Jl. Raya Gading No. 12', 'Gading', 'Gading Wetan', -7.8500, 113.4600, 'Final', 'Koordinator pemuda Gading', 1, 'Superadmin Pusat', DATE_SUB(NOW(), INTERVAL 1 DAY));

-- 4. SEED LOG AKTIVITAS AWAL
INSERT INTO `audit_logs` (`user_id`, `user_nama`, `aksi`, `id_referensi`, `keterangan`, `ip_address`) VALUES
(1, 'Superadmin Pusat', 'Inisialisasi Sistem', 'INIT', 'Inisialisasi database dan instalasi sistem cPanel berhasil dilakukan', '127.0.0.1'),
(1, 'Superadmin Pusat', 'Input Data DPC', 'DPC1', 'Input data DPC Kecamatan: Ahmad Fauzi, S.Pd (Ketua DPC)', '127.0.0.1'),
(4, 'Admin Ranting Kraksaan Kota', 'Input Data PIP', 'PIP3', 'Input data penerima PIP: Rizky Ramadhan (Semampir)', '127.0.0.1');
