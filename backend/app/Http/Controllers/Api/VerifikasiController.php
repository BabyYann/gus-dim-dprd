<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendukung;
use App\Models\AuditLog;

class VerifikasiController extends Controller
{
    public function riwayat(Request $request)
    {
        $user = $request->user();
        $jalurKey = strtoupper($request->query('jalur', ''));
        $statusFilter = $request->query('status', '');
        $keyword = trim($request->query('keyword', ''));

        $query = Pendukung::query();

        if ($jalurKey && $jalurKey !== 'ALL') {
            $query->where('jalur', $jalurKey);
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('nik', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        if ($user->role === 'Koordinator Desa' && !empty($user->desa)) {
            $query->where('desa', $user->desa);
        } elseif ($user->role === 'Koordinator Kecamatan' && !empty($user->kecamatan)) {
            $query->where('kecamatan', $user->kecamatan);
        }

        $records = $query->orderBy('id', 'desc')->get();

        $rows = $records->map(function ($r) {
            $rawJalur = strtoupper($r->jalur);
            return [
                'rowNumber' => $r->id,
                'id' => $r->id,
                'jalurKey' => $rawJalur,
                'jalur' => Pendukung::getJalurLabel($rawJalur),
                'nik' => $r->nik,
                'nama' => $r->nama,
                'hp' => $r->hp ?? '',
                'alamat' => $r->alamat ?? '',
                'foto' => $r->foto_wajah ?? '',
                'kecamatan' => $r->kecamatan ?? '',
                'desa' => $r->desa ?? '',
                'koordinator' => $r->koordinator ?? '',
                'jabatan' => $r->jabatan ?? '',
                'userInput' => $r->input_by_user_name ?? '',
                'tanggal' => $r->created_at->format('d/m/Y H:i'),
                'status' => $r->status,
                'catatan' => $r->catatan ?? ''
            ];
        });

        return response()->json([
            'success' => true,
            'rows' => $rows,
            'userRole' => $user->role,
            'jalur' => $jalurKey
        ]);
    }

    public function updateStatus(Request $request)
    {
        $user = $request->user();
        $id = (int)$request->input('rowNumber', $request->input('id', 0));
        $newStatus = trim($request->input('newStatus', ''));
        $catatan = trim($request->input('catatan', ''));

        if (!$id || !$newStatus) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap.']);
        }

        $allowed = match ($user->role) {
            'Superadmin' => true,
            'Koordinator Kecamatan' => in_array($newStatus, ['Divalidasi Kecamatan', 'Final', 'Ditolak']),
            'Koordinator Desa' => in_array($newStatus, ['Diverifikasi Desa', 'Ditolak']),
            default => false,
        };

        if (!$allowed) {
            return response()->json([
                'success' => false,
                'message' => "Role '{$user->role}' tidak berwenang mengubah status menjadi '{$newStatus}'."
            ]);
        }

        $pendukung = Pendukung::find($id);
        if (!$pendukung) {
            return response()->json(['success' => false, 'message' => 'Data pendukung tidak ditemukan.']);
        }

        $targetName = "{$pendukung->nama} ({$pendukung->jalur})";
        $pendukung->status = $newStatus;
        if ($catatan) {
            $pendukung->catatan = $catatan;
        }
        $pendukung->save();

        AuditLog::create([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'aksi' => 'Update Status Verifikasi',
            'id_referensi' => (string)$id,
            'keterangan' => "Status '{$targetName}' diubah menjadi '{$newStatus}'" . ($catatan ? " (Catatan: {$catatan})" : ''),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diperbarui menjadi '{$newStatus}'."
        ]);
    }
}
