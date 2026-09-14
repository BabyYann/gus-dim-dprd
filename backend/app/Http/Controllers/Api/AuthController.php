<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $username = trim($request->input('username', ''));
        $password = $request->input('password', '');

        if (!$username || !$password) {
            return response()->json(['success' => false, 'message' => 'Username dan password wajib diisi.']);
        }

        $user = User::where('username', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Username atau password salah.']);
        }

        if ($user->status !== 'Aktif') {
            return response()->json(['success' => false, 'message' => 'Akun Anda dinonaktifkan. Silakan hubungi Superadmin.']);
        }

        // Generate Sanctum Token
        $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;

        AuditLog::create([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'aksi' => 'Login',
            'id_referensi' => (string)$user->id,
            'keterangan' => 'Berhasil login ke aplikasi (Laravel Backend)',
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'role' => $user->role,
                'kecamatan' => $user->kecamatan,
                'desa' => $user->desa,
                'ranting' => $user->ranting,
                'fotoProfil' => $user->foto_profil,
            ]
        ]);
    }

    public function check(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return response()->json([
                'valid' => true,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'nama' => $user->nama,
                    'role' => $user->role,
                    'kecamatan' => $user->kecamatan,
                    'desa' => $user->desa,
                    'ranting' => $user->ranting,
                    'fotoProfil' => $user->foto_profil,
                ]
            ]);
        }

        return response()->json(['valid' => false]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'user_nama' => $user->nama,
                'aksi' => 'Logout',
                'id_referensi' => (string)$user->id,
                'keterangan' => 'Keluar dari aplikasi',
                'ip_address' => $request->ip(),
                'created_at' => now(),
            ]);
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['success' => true]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $namaBaru = trim($request->input('nama', ''));

        if (!$namaBaru) {
            return response()->json(['success' => false, 'message' => 'Nama tidak boleh kosong.']);
        }

        $user->nama = $namaBaru;
        $user->name = $namaBaru;
        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'aksi' => 'Update Profil',
            'id_referensi' => (string)$user->id,
            'keterangan' => "Memperbarui nama profil menjadi: {$namaBaru}",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'role' => $user->role,
                'kecamatan' => $user->kecamatan,
                'desa' => $user->desa,
                'ranting' => $user->ranting,
                'fotoProfil' => $user->foto_profil,
            ]
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $user = $request->user();
        $base64 = $request->input('fotoBase64', '');

        if (!$base64) {
            return response()->json(['success' => false, 'message' => 'Data gambar tidak ditemukan.']);
        }

        if (str_contains($base64, ',')) {
            $base64 = explode(',', $base64)[1];
        }

        $decoded = base64_decode($base64);
        if (!$decoded) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.']);
        }

        $filename = 'avatar_' . $user->id . '_' . time() . '.jpg';
        Storage::disk('public')->put('avatars/' . $filename, $decoded);
        $fotoUrl = 'storage/avatars/' . $filename;

        $user->foto_profil = $fotoUrl;
        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'aksi' => 'Ganti Foto Profil',
            'id_referensi' => (string)$user->id,
            'keterangan' => 'Memperbarui foto profil pengguna',
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui.',
            'fotoUrl' => $fotoUrl,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'role' => $user->role,
                'kecamatan' => $user->kecamatan,
                'desa' => $user->desa,
                'ranting' => $user->ranting,
                'fotoProfil' => $user->foto_profil,
            ]
        ]);
    }
    public function changePassword(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid.'], 401);
        }

        $password = $request->input('password', $request->input('newPassword', ''));
        if (strlen($password) < 6) {
            return response()->json(['success' => false, 'message' => 'Kata sandi baru minimal 6 karakter.']);
        }

        $user->password = Hash::make($password);
        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'aksi' => 'Ganti Password',
            'id_referensi' => (string)$user->id,
            'keterangan' => 'Memperbarui kata sandi akun sendiri',
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Kata sandi berhasil diperbarui.']);
    }
}
