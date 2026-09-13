<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AuditLog;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'Superadmin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $users = User::orderBy('id', 'asc')->get()->map(function ($u) {
            return [
                'rowNumber' => $u->id,
                'id' => $u->id,
                'username' => $u->username,
                'nama' => $u->nama,
                'role' => $u->role,
                'kecamatan' => $u->kecamatan ?? '',
                'desa' => $u->desa ?? '',
                'ranting' => $u->ranting ?? '',
                'status' => $u->status,
            ];
        });

        return response()->json(['success' => true, 'rows' => $users]);
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();
        if ($currentUser->role !== 'Superadmin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $username = trim($request->input('username', ''));
        $password = $request->input('password', '');
        $nama = trim($request->input('nama', ''));
        $role = trim($request->input('role', 'Admin Ranting'));

        if (!$username || !$password || !$nama) {
            return response()->json(['success' => false, 'message' => 'Username, nama, dan password wajib diisi.']);
        }

        if (User::where('username', $username)->exists()) {
            return response()->json(['success' => false, 'message' => "Username '{$username}' sudah digunakan."]);
        }

        $newUser = User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'nama' => $nama,
            'name' => $nama,
            'role' => $role,
            'kecamatan' => trim($request->input('kecamatan', '')),
            'desa' => trim($request->input('desa', '')),
            'ranting' => trim($request->input('ranting', '')),
            'status' => 'Aktif',
        ]);

        AuditLog::create([
            'user_id' => $currentUser->id,
            'user_nama' => $currentUser->nama,
            'aksi' => 'Tambah Pengguna',
            'id_referensi' => (string)$newUser->id,
            'keterangan' => "Menambahkan pengguna baru: {$nama} ({$role})",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Pengguna baru berhasil ditambahkan.']);
    }

    public function toggleStatus(Request $request)
    {
        $currentUser = $request->user();
        if ($currentUser->role !== 'Superadmin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $targetId = (int)$request->input('rowNumber', $request->input('id', 0));
        $newStatus = $request->input('newStatus', 'Aktif') === 'Aktif' ? 'Aktif' : 'Nonaktif';

        if ($targetId === $currentUser->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        $targetUser = User::find($targetId);
        if (!$targetUser) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.']);
        }

        $targetUser->status = $newStatus;
        $targetUser->save();

        AuditLog::create([
            'user_id' => $currentUser->id,
            'user_nama' => $currentUser->nama,
            'aksi' => 'Ubah Status Pengguna',
            'id_referensi' => (string)$targetId,
            'keterangan' => "Mengubah status pengguna {$targetUser->nama} menjadi {$newStatus}",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status pengguna berhasil diubah menjadi {$newStatus}."
        ]);
    }

    public function resetPassword(Request $request)
    {
        $currentUser = $request->user();
        if ($currentUser->role !== 'Superadmin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $targetId = (int)$request->input('rowNumber', $request->input('id', 0));
        $newPassword = $request->input('newPassword', '');

        if (!$targetId || !$newPassword) {
            return response()->json(['success' => false, 'message' => 'Password baru tidak boleh kosong.']);
        }

        $targetUser = User::find($targetId);
        if (!$targetUser) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.']);
        }

        $targetUser->password = Hash::make($newPassword);
        $targetUser->save();

        AuditLog::create([
            'user_id' => $currentUser->id,
            'user_nama' => $currentUser->nama,
            'aksi' => 'Reset Password',
            'id_referensi' => (string)$targetId,
            'keterangan' => "Mereset password pengguna {$targetUser->nama}",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Password pengguna berhasil direset.']);
    }
}
