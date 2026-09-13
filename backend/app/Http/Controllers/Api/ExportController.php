<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendukung;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function export(Request $request): StreamedResponse
    {
        $jalur = $request->query('jalur', 'ALL');
        $kecamatan = $request->query('kecamatan', '');

        $query = Pendukung::query();

        if ($jalur && $jalur !== 'ALL') {
            $query->where('jalur', $jalur);
        }
        if ($kecamatan) {
            $query->where('kecamatan', $kecamatan);
        }

        $records = $query->orderBy('id', 'asc')->get();
        $filename = "Rekap_GusDim_" . ($jalur !== 'ALL' ? $jalur . '_' : '') . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($records) {
            $output = fopen('php://output', 'w');
            // UTF-8 BOM agar rapi di Microsoft Excel
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($output, [
                'No', 'ID', 'Jalur', 'NIK', 'Nama Lengkap', 'No. HP', 'Umur',
                'Jabatan / Koordinator', 'Alamat', 'Kecamatan', 'Desa',
                'Status Verifikasi', 'Penginput', 'Tanggal Input'
            ]);

            $no = 1;
            foreach ($records as $r) {
                fputcsv($output, [
                    $no++,
                    $r->id,
                    Pendukung::getJalurLabel($r->jalur),
                    "'" . $r->nik,
                    $r->nama,
                    "'" . ($r->hp ?? ''),
                    $r->umur ?? '',
                    $r->jabatan ?: $r->koordinator,
                    $r->alamat ?? '',
                    $r->kecamatan ?? '',
                    $r->desa ?? '',
                    $r->status,
                    $r->input_by_user_name ?? '',
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
                ]);
            }

            fclose($output);
        }, 200, $headers);
    }
}
