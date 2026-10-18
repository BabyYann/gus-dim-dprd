<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'nama',
        'name',
        'email',
        'password',
        'password_hash',
        'role',
        'kecamatan',
        'desa',
        'ranting',
        'foto_profil',
        'status',
    ];

    protected $hidden = [
        'password',
        'password_hash',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password ?: $this->password_hash;
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
