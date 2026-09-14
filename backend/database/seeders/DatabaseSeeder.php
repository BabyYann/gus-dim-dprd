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
        // 1. Akun Superadmin Tunggal Produksi
        User::where('username', '!=', 'superadmin')->delete();

        User::updateOrCreate(
            ['username' => 'superadmin'],
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
            ]
        );

        // 2. Referensi Wilayah Dapil Kraksaan Raya (Master Data Wajib)
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

        // 3. Kosongkan Data Transaksional Uji Coba
        Pendukung::truncate();
        AuditLog::truncate();

        // 4. Catatan Log Inisialisasi Produksi
        AuditLog::create([
            'user_id' => 1,
            'user_nama' => 'Superadmin Pusat',
            'aksi' => 'Inisialisasi Sistem Produksi',
            'id_referensi' => 'INIT_PRODUKSI',
            'keterangan' => 'Sistem Informasi Pemenangan Terpadu Gus Dim siap beroperasi penuh di server produksi',
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ]);
    }
}
