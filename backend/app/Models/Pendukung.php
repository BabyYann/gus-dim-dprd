<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendukung extends Model
{
    use HasFactory;

    protected $table = 'pendukung';

    protected $fillable = [
        'jalur',
        'nik',
        'nama',
        'hp',
        'umur',
        'jabatan',
        'koordinator',
        'alamat',
        'kecamatan',
        'desa',
        'latitude',
        'longitude',
        'foto_wajah',
        'foto_ktp',
        'data_khusus',
        'status',
        'catatan',
        'input_by_user_id',
        'input_by_user_name',
    ];

    protected function casts(): array
    {
        return [
            'data_khusus' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
            'umur' => 'integer',
        ];
    }

    public static function getJalurLabel(string $jalur): string
    {
        return match (strtoupper($jalur)) {
            'DPC' => 'DPC Kecamatan',
            'DPRT' => 'DPRT Desa',
            'PIP' => 'PIP',
            'KIP' => 'KIP',
            'RELAWAN' => 'Relawan',
            default => $jalur,
        };
    }
}
