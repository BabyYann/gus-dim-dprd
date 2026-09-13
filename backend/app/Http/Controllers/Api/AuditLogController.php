<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::orderBy('id', 'desc')->limit(200)->get()->map(function ($l) {
            return [
                'waktu' => $l->created_at ? $l->created_at->format('d/m/Y H:i') : '',
                'user' => $l->user_nama,
                'aksi' => $l->aksi,
                'keterangan' => $l->keterangan ?? '',
            ];
        });

        return response()->json(['success' => true, 'rows' => $logs]);
    }
}
