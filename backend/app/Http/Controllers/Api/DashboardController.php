<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendukung;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $records = Pendukung::orderBy('created_at', 'desc')->get();

        $byJalur = [
            'DPC Kecamatan' => 0,
            'DPRT Desa' => 0,
            'PIP' => 0,
            'KIP' => 0,
            'Relawan' => 0
        ];

        $byKecamatan = [];
        $mapPoints = [];
        $formattedRecords = [];

        foreach ($records as $r) {
            $rawJalur = strtoupper($r->jalur);
            $labelJalur = Pendukung::getJalurLabel($rawJalur);

            if (isset($byJalur[$labelJalur])) {
                $byJalur[$labelJalur]++;
            } else {
                $byJalur[$labelJalur] = ($byJalur[$labelJalur] ?? 0) + 1;
            }

            $kec = $r->kecamatan ?: 'Lainnya';
            $byKecamatan[$kec] = ($byKecamatan[$kec] ?? 0) + 1;

            if ($r->latitude && $r->longitude) {
                $mapPoints[] = [
                    'nama' => $r->nama,
                    'jalur' => $labelJalur,
                    'kecamatan' => $r->kecamatan ?? '',
                    'desa' => $r->desa ?? '',
                    'alamat' => $r->alamat ?? '',
                    'lat' => (float)$r->latitude,
                    'lng' => (float)$r->longitude,
                    'foto' => $r->foto_wajah ?? ''
                ];
            }

            $formattedRecords[] = [
                'id' => $r->id,
                'jalurKey' => $rawJalur,
                'jalur' => $labelJalur,
                'nik' => $r->nik,
                'nama' => $r->nama,
                'hp' => $r->hp,
                'umur' => $r->umur,
                'jabatan' => $r->jabatan,
                'koordinator' => $r->koordinator,
                'alamat' => $r->alamat,
                'kecamatan' => $r->kecamatan,
                'desa' => $r->desa,
                'lat' => $r->latitude,
                'lng' => $r->longitude,
                'foto' => $r->foto_wajah,
                'fotoKtp' => $r->foto_ktp,
                'status' => $r->status,
                'catatan' => $r->catatan,
                'userInput' => $r->input_by_user_name,
                'tanggalRaw' => $r->created_at->toISOString(),
                'tanggal' => $r->created_at->format('d/m/Y H:i'),
            ];
        }

        return response()->json([
            'success' => true,
            'total' => count($formattedRecords),
            'byJalur' => $byJalur,
            'byKecamatan' => $byKecamatan,
            'mapPoints' => $mapPoints,
            'recent' => array_slice($formattedRecords, 0, 10),
            'allRecords' => $formattedRecords
        ]);
    }
}
