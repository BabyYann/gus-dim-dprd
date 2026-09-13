<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\WilayahReferensi;
use App\Models\Pendukung;
use App\Models\AuditLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna
        $users = [
            [
                'username' => 'superadmin',
                'nama' => 'Superadmin Pusat',
                'name' => 'Superadmin Pusat',
                'password' => Hash::make('admin123'),
                'role' => 'Superadmin',
                'kecamatan' => null,
                'desa' => null,
                'ranting' => null,
                'status' => 'Aktif',
            ],
            [
                'username' => 'korcam_kraksaan',
                'nama' => 'H. Mansyur (Korcam)',
                'name' => 'H. Mansyur (Korcam)',
                'password' => Hash::make('password123'),
                'role' => 'Koordinator Kecamatan',
                'kecamatan' => 'Kraksaan',
                'desa' => null,
                'ranting' => null,
                'status' => 'Aktif',
            ],
            [
                'username' => 'kordes_wetan',
                'nama' => 'Ust. Bahri (Kordes)',
                'name' => 'Ust. Bahri (Kordes)',
                'password' => Hash::make('password123'),
                'role' => 'Koordinator Desa',
                'kecamatan' => 'Kraksaan',
                'desa' => 'Kraksaan Wetan',
                'ranting' => null,
                'status' => 'Aktif',
            ],
            [
                'username' => 'ranting_kraksaan',
                'nama' => 'Admin Ranting Kraksaan Kota',
                'name' => 'Admin Ranting Kraksaan Kota',
                'password' => Hash::make('password123'),
                'role' => 'Admin Ranting',
                'kecamatan' => 'Kraksaan',
                'desa' => 'Kraksaan Wetan',
                'ranting' => 'Ranting Kraksaan Kota',
                'status' => 'Aktif',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['username' => $u['username']], $u);
        }

        // 2. Referensi Wilayah Dapil Kraksaan Raya
        $wilayah = [
            // Kraksaan
            ['kecamatan' => 'Kraksaan', 'desa' => 'Kraksaan Wetan', 'lat_default' => -7.7580, 'lng_default' => 113.4150],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Kraksaan Kulon', 'lat_default' => -7.7550, 'lng_default' => 113.4090],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Semampir', 'lat_default' => -7.7480, 'lng_default' => 113.4210],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Kalibuntu', 'lat_default' => -7.7320, 'lng_default' => 113.4100],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Sidopekso', 'lat_default' => -7.7650, 'lng_default' => 113.4250],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Patokan', 'lat_default' => -7.7620, 'lng_default' => 113.4120],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Kandangjati Kulon', 'lat_default' => -7.7690, 'lng_default' => 113.4040],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Kandangjati Wetan', 'lat_default' => -7.7710, 'lng_default' => 113.4160],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Bulubranti', 'lat_default' => -7.7780, 'lng_default' => 113.4300],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Asembagus', 'lat_default' => -7.7850, 'lng_default' => 113.4180],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Rondokuning', 'lat_default' => -7.7900, 'lng_default' => 113.4050],
            ['kecamatan' => 'Kraksaan', 'desa' => 'Kebonagung', 'lat_default' => -7.7950, 'lng_default' => 113.4120],

            // Besuk
            ['kecamatan' => 'Besuk', 'desa' => 'Besuk Kidul', 'lat_default' => -7.8020, 'lng_default' => 113.4420],
            ['kecamatan' => 'Besuk', 'desa' => 'Besuk Agung', 'lat_default' => -7.8100, 'lng_default' => 113.4510],
            ['kecamatan' => 'Besuk', 'desa' => 'Alaskandang', 'lat_default' => -7.8150, 'lng_default' => 113.4350],
            ['kecamatan' => 'Besuk', 'desa' => 'Bago', 'lat_default' => -7.8200, 'lng_default' => 113.4480],
            ['kecamatan' => 'Besuk', 'desa' => 'Klampokan', 'lat_default' => -7.8250, 'lng_default' => 113.4600],
            ['kecamatan' => 'Besuk', 'desa' => 'Randujati', 'lat_default' => -7.8300, 'lng_default' => 113.4400],
            ['kecamatan' => 'Besuk', 'desa' => 'Sindetlami', 'lat_default' => -7.8350, 'lng_default' => 113.4550],
            ['kecamatan' => 'Besuk', 'desa' => 'Sumbersuko', 'lat_default' => -7.8400, 'lng_default' => 113.4300],

            // Gading
            ['kecamatan' => 'Gading', 'desa' => 'Gading Wetan', 'lat_default' => -7.8500, 'lng_default' => 113.4600],
            ['kecamatan' => 'Gading', 'desa' => 'Gading Kulon', 'lat_default' => -7.8550, 'lng_default' => 113.4500],
            ['kecamatan' => 'Gading', 'desa' => 'Randujalak', 'lat_default' => -7.8600, 'lng_default' => 113.4700],
            ['kecamatan' => 'Gading', 'desa' => 'Mojolegi', 'lat_default' => -7.8650, 'lng_default' => 113.4550],
            ['kecamatan' => 'Gading', 'desa' => 'Kalisat', 'lat_default' => -7.8700, 'lng_default' => 113.4650],
            ['kecamatan' => 'Gading', 'desa' => 'Prasi', 'lat_default' => -7.8750, 'lng_default' => 113.4800],
            ['kecamatan' => 'Gading', 'desa' => 'Sentul', 'lat_default' => -7.8800, 'lng_default' => 113.4600],
            ['kecamatan' => 'Gading', 'desa' => 'Batur', 'lat_default' => -7.8850, 'lng_default' => 113.4750],
        ];

        foreach ($wilayah as $w) {
            WilayahReferensi::updateOrCreate(
                ['kecamatan' => $w['kecamatan'], 'desa' => $w['desa']],
                $w
            );
        }

        // 3. Contoh Data Pendukung Awal
        $pendukung = [
            [
                'jalur' => 'DPC',
                'nik' => '3513121508820001',
                'nama' => 'Ahmad Fauzi, S.Pd',
                'hp' => '081234567890',
                'umur' => 44,
                'jabatan' => 'Ketua DPC',
                'alamat' => 'Jl. Panglima Sudirman No. 45',
                'kecamatan' => 'Kraksaan',
                'desa' => 'Kraksaan Wetan',
                'latitude' => -7.7580,
                'longitude' => 113.4150,
                'status' => 'Final',
                'catatan' => 'Tokoh masyarakat Kraksaan',
                'input_by_user_id' => 1,
                'input_by_user_name' => 'Superadmin Pusat',
            ],
            [
                'jalur' => 'DPRT',
                'nik' => '3513125203890002',
                'nama' => 'Siti Aminah',
                'hp' => '085233445566',
                'umur' => 37,
                'jabatan' => 'Sekretaris DPRT',
                'alamat' => 'Dusun Krajan RT 02/RW 01',
                'kecamatan' => 'Kraksaan',
                'desa' => 'Kraksaan Kulon',
                'latitude' => -7.7550,
                'longitude' => 113.4090,
                'status' => 'Divalidasi Kecamatan',
                'catatan' => 'Koordinator ibu-ibu pengajian',
                'input_by_user_id' => 4,
                'input_by_user_name' => 'Admin Ranting Kraksaan Kota',
            ],
            [
                'jalur' => 'PIP',
                'nik' => '3513120101080003',
                'nama' => 'Rizky Ramadhan',
                'hp' => '087811223344',
                'umur' => 18,
                'alamat' => 'Jl. Ikan Paus RT 03/RW 02',
                'kecamatan' => 'Kraksaan',
                'desa' => 'Semampir',
                'latitude' => -7.7480,
                'longitude' => 113.4210,
                'data_khusus' => ['namaSekolah' => 'SMAN 1 Kraksaan'],
                'status' => 'Diverifikasi Desa',
                'catatan' => 'Siswa SMAN 1 Kraksaan berprestasi',
                'input_by_user_id' => 4,
                'input_by_user_name' => 'Admin Ranting Kraksaan Kota',
            ],
            [
                'jalur' => 'KIP',
                'nik' => '3513134511030004',
                'nama' => 'Putri Ayu Lestari',
                'hp' => '089677889900',
                'umur' => 23,
                'alamat' => 'Dusun Timur RT 01/RW 04',
                'kecamatan' => 'Besuk',
                'desa' => 'Besuk Kidul',
                'latitude' => -7.8020,
                'longitude' => 113.4420,
                'data_khusus' => ['namaKampus' => 'Universitas Nurul Jadid'],
                'status' => 'Diinput',
                'catatan' => 'Mahasiswi Universitas Nurul Jadid',
                'input_by_user_id' => 2,
                'input_by_user_name' => 'H. Mansyur (Korcam)',
            ],
            [
                'jalur' => 'RELAWAN',
                'nik' => '3513141006950005',
                'nama' => 'Bambang Sutrisno',
                'hp' => '082199887766',
                'umur' => 31,
                'jabatan' => 'Anggota',
                'koordinator' => 'Relawan Sayap Muda Gus Dim',
                'alamat' => 'Jl. Raya Gading No. 12',
                'kecamatan' => 'Gading',
                'desa' => 'Gading Wetan',
                'latitude' => -7.8500,
                'longitude' => 113.4600,
                'status' => 'Final',
                'catatan' => 'Koordinator pemuda Gading',
                'input_by_user_id' => 1,
                'input_by_user_name' => 'Superadmin Pusat',
            ],
        ];

        foreach ($pendukung as $p) {
            Pendukung::updateOrCreate(['nik' => $p['nik']], $p);
        }

        // 4. Log Audit Awal
        AuditLog::create([
            'user_id' => 1,
            'user_nama' => 'Superadmin Pusat',
            'aksi' => 'Inisialisasi Laravel',
            'id_referensi' => 'INIT_LARAVEL',
            'keterangan' => 'Inisialisasi sistem Gus Dim dengan Laravel 11/12 berhasil dilakukan',
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ]);
    }
}
