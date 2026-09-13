<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pendukung;
use App\Models\AuditLog;
use DateTime;

class PendukungController extends Controller
{
    private function hitungUmurNik(?string $nik): ?int
    {
        if (!$nik || strlen($nik) < 12) return null;
        $dd = (int)substr($nik, 6, 2);
        $mm = (int)substr($nik, 8, 2);
        $yy = (int)substr($nik, 10, 2);
        if ($dd > 40) $dd -= 40;
        if ($dd < 1 || $dd > 31 || $mm < 1 || $mm > 12) return null;

        $currentYY = (int)date('y');
        $fullYear = ($yy <= $currentYY) ? (2000 + $yy) : (1900 + $yy);
        $lahir = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $fullYear, $mm, $dd));
        if (!$lahir) return null;

        $diff = (new DateTime())->diff($lahir);
        return ($diff->y >= 0 && $diff->y < 120) ? $diff->y : null;
    }

    private function saveBase64Image(?string $base64, string $subfolder): ?string
    {
        if (!$base64) return null;
        if (str_contains($base64, ',')) {
            $base64 = explode(',', $base64)[1];
        }
        $decoded = base64_decode($base64);
        if (!$decoded) return null;

        $filename = $subfolder . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.jpg';
        Storage::disk('public')->put($subfolder . '/' . $filename, $decoded);
        return 'storage/' . $subfolder . '/' . $filename;
    }

    public function checkNik(Request $request)
    {
        $nik = trim($request->query('nik', ''));
        if (!$nik) {
            return response()->json(['exists' => false]);
        }

        $ada = Pendukung::where('nik', $nik)->first(['id', 'nama', 'jalur', 'kecamatan', 'desa']);
        return response()->json([
            'exists' => (bool)$ada,
            'data' => $ada
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $jalur = strtoupper(trim($request->input('jalur', '')));
        $allowed = ['DPC', 'DPRT', 'PIP', 'KIP', 'RELAWAN'];

        if (!in_array($jalur, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Jalur data tidak valid.']);
        }

        $nama = '';
        $nik = '';
        $hp = '';
        $jabatan = '';
        $koordinator = '';
        $kecamatan = '';
        $desa = '';
        $alamat = '';
        $dataKhusus = null;

        if ($jalur === 'DPC') {
            $nama = trim($request->input('nama', ''));
            $nik = trim($request->input('nik', ''));
            $hp = trim($request->input('hp', ''));
            $jabatan = trim($request->input('jabatan', ''));
            $kecamatan = trim($request->input('kecamatan', ''));
            $alamat = trim($request->input('alamat', ''));
        } elseif ($jalur === 'DPRT') {
            $nama = trim($request->input('nama', ''));
            $nik = trim($request->input('nik', ''));
            $hp = trim($request->input('hp', ''));
            $jabatan = trim($request->input('jabatan', ''));
            $kecamatan = trim($request->input('kecamatan', ''));
            $desa = trim($request->input('desa', ''));
            $alamat = trim($request->input('alamat', ''));
        } elseif ($jalur === 'PIP') {
            $nama = trim($request->input('namaAnak', ''));
            $nik = trim($request->input('nikAnak', ''));
            $hp = trim($request->input('hpAnak', ''));
            $kecamatan = trim($request->input('kecamatan', ''));
            $desa = trim($request->input('desa', ''));
            $alamat = trim($request->input('alamatKeluarga', ''));
            $dataKhusus = [
                'namaSekolah' => trim($request->input('namaSekolah', '')),
                'alamatSekolah' => trim($request->input('alamatSekolah', '')),
                'namaAyah' => trim($request->input('namaAyah', '')),
                'nikAyah' => trim($request->input('nikAyah', '')),
                'hpAyah' => trim($request->input('hpAyah', '')),
                'namaIbu' => trim($request->input('namaIbu', '')),
                'nikIbu' => trim($request->input('nikIbu', '')),
                'hpIbu' => trim($request->input('hpIbu', '')),
                'jumlahSaudara' => trim($request->input('jumlahSaudara', '')),
                'namaSaudara' => trim($request->input('namaSaudara', '')),
                'nikSaudara' => trim($request->input('nikSaudara', '')),
            ];
        } elseif ($jalur === 'KIP') {
            $nama = trim($request->input('namaAnak', ''));
            $nik = trim($request->input('nikAnak', ''));
            $hp = trim($request->input('hpAnak', ''));
            $kecamatan = trim($request->input('kecamatan', ''));
            $desa = trim($request->input('desa', ''));
            $alamat = trim($request->input('alamatKeluarga', ''));
            $dataKhusus = [
                'namaKampus' => trim($request->input('namaKampus', '')),
                'alamatKampus' => trim($request->input('alamatKampus', '')),
                'namaAyah' => trim($request->input('namaAyah', '')),
                'nikAyah' => trim($request->input('nikAyah', '')),
                'hpAyah' => trim($request->input('hpAyah', '')),
                'namaIbu' => trim($request->input('namaIbu', '')),
                'nikIbu' => trim($request->input('nikIbu', '')),
                'hpIbu' => trim($request->input('hpIbu', '')),
                'jumlahSaudara' => trim($request->input('jumlahSaudara', '')),
                'namaSaudara' => trim($request->input('namaSaudara', '')),
                'nikSaudara' => trim($request->input('nikSaudara', '')),
            ];
        } elseif ($jalur === 'RELAWAN') {
            $nama = trim($request->input('namaAnggota', ''));
            $nik = trim($request->input('nikAnggota', ''));
            $hp = trim($request->input('hpAnggota', ''));
            $jabatan = 'Anggota';
            $koordinator = trim($request->input('namaKoordinator', ''));
            $kecamatan = trim($request->input('kecamatan', ''));
            $desa = trim($request->input('desa', ''));
            $alamat = trim($request->input('alamatAnggota', ''));
        }

        if (!$nama || !$nik) {
            return response()->json(['success' => false, 'message' => 'Nama dan NIK wajib diisi.']);
        }

        // Anti-Duplikasi NIK
        $ada = Pendukung::where('nik', $nik)->first();
        if ($ada) {
            return response()->json([
                'success' => false,
                'message' => "NIK {$nik} sudah terdaftar sebelumnya atas nama '{$ada->nama}' (Jalur: {$ada->jalur})."
            ]);
        }

        $fotoUrl = $this->saveBase64Image($request->input('fotoBase64'), 'foto');
        $ktpUrl = $this->saveBase64Image($request->input('ktpBase64'), 'ktp');
        $umur = $this->hitungUmurNik($nik);

        $lat = $request->input('lat');
        $lng = $request->input('lng');
        if (!$lat || !$lng) {
            $defaultCoords = [
                'Kraksaan' => [-7.7580, 113.4150],
                'Besuk' => [-7.8020, 113.4420],
                'Gading' => [-7.8500, 113.4600]
            ];
            if (isset($defaultCoords[$kecamatan])) {
                $lat = $defaultCoords[$kecamatan][0] + (mt_rand(-50, 50) / 10000);
                $lng = $defaultCoords[$kecamatan][1] + (mt_rand(-50, 50) / 10000);
            }
        }

        $pendukung = Pendukung::create([
            'jalur' => $jalur,
            'nik' => $nik,
            'nama' => $nama,
            'hp' => $hp,
            'umur' => $umur,
            'jabatan' => $jabatan,
            'koordinator' => $koordinator,
            'alamat' => $alamat,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
            'latitude' => $lat,
            'longitude' => $lng,
            'foto_wajah' => $fotoUrl,
            'foto_ktp' => $ktpUrl,
            'data_khusus' => $dataKhusus,
            'status' => 'Diinput',
            'catatan' => '',
            'input_by_user_id' => $user->id,
            'input_by_user_name' => $user->nama,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'aksi' => "Input Data {$jalur}",
            'id_referensi' => $jalur . $pendukung->id,
            'keterangan' => "Input data pendukung: {$nama} ({$jalur})",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Data {$jalur} berhasil disimpan dan masuk dalam antrean verifikasi.",
            'id' => $pendukung->id
        ]);
    }
}
