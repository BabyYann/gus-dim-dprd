<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahReferensi extends Model
{
    protected $table = 'wilayah_referensi';
    public $timestamps = false;

    protected $fillable = [
        'kecamatan',
        'desa',
        'lat_default',
        'lng_default',
    ];

    protected function casts(): array
    {
        return [
            'lat_default' => 'float',
            'lng_default' => 'float',
        ];
    }
}
