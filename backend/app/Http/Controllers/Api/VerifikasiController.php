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

        if ($user->role === 'Koordinator Desa' || $user->role === 'Admin Ranting') {
            if (!empty($user->desa)) {
                $query->where('desa', $user->desa);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user->role === 'Koordinator Kecamatan') {
            if (!empty($user->kecamatan)) {
                $query->where('kecamatan', $user->kecamatan);
            } else {
                $query->whereRaw('1 = 0');
            }
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
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
        }

        $allowed = match ($user->role) {
            'Superadmin' => true,
            'Koordinator Kecamatan' => in_array($newStatus, ['Divalidasi Kecamatan', 'Final', 'Ditolak']),
            'Koordinator Desa' => in_array($newStatus, ['Diverifikasi Desa', 'Diinput', 'Ditolak']),
            default => false,
        };

        if (!$allowed) {
            return response()->json([
                'success' => false,
                'message' => "Role '{$user->role}' tidak berwenang mengubah status menjadi '{$newStatus}'."
            ], 403);
        }

        $pendukung = Pendukung::find($id);
        if (!$pendukung) {
            return response()->json(['success' => false, 'message' => 'Data pendukung tidak ditemukan.'], 404);
        }

        // Territorial Guard & Workflow Lock
        if ($user->role === 'Koordinator Desa' || $user->role === 'Admin Ranting') {
            if (in_array($pendukung->status, ['Divalidasi Kecamatan', 'Final'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Data sudah berstatus '{$pendukung->status}'. Hanya Koordinator Kecamatan atau Superadmin yang berwenang membuka kunci / merevisi data ini."
                ], 403);
            }
            $userDesa = trim($user->desa ?? '');
            $targetDesa = trim($pendukung->desa ?? '');
            if ($userDesa === '' || strcasecmp($userDesa, $targetDesa) !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => $userDesa === ''
                        ? "Akses Ditolak: Profil akun Anda belum terikat dengan Desa tugas."
                        : "Akses Ditolak: Anda bertugas di Desa '{$userDesa}', tidak berwenang memverifikasi data Desa '{$targetDesa}'."
                ], 403);
            }
        } elseif ($user->role === 'Koordinator Kecamatan') {
            $userKec = trim($user->kecamatan ?? '');
            $targetKec = trim($pendukung->kecamatan ?? '');
            if ($userKec === '' || strcasecmp($userKec, $targetKec) !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => $userKec === ''
                        ? "Akses Ditolak: Profil akun Anda belum terikat dengan Kecamatan tugas."
                        : "Akses Ditolak: Anda bertugas di Kecamatan '{$userKec}', tidak berwenang memvalidasi data Kecamatan '{$targetKec}'."
                ], 403);
            }
        }

        $targetName = "{$pendukung->nama} ({$pendukung->jalur})";
        $oldStatus = $pendukung->status;
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

        try {
            event(new \App\Events\PendukungStatusUpdated($pendukung, $oldStatus, $newStatus, $user->nama));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast PendukungStatusUpdated gagal: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diperbarui menjadi '{$newStatus}'."
        ]);
    }
}
