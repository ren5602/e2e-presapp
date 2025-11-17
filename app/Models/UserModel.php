<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ ini yang penting
use Illuminate\Database\Eloquent\Model;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'level_id',
    ];

    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function lomba()
    {
        return $this->hasMany(LombaModel::class, 'user_id', 'user_id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(MahasiswaModel::class, 'user_id', 'user_id');
    }

    public function dosen()
    {
        return $this->hasOne(DosenModel::class, 'user_id', 'user_id');
    }

    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'user_id', 'user_id');
    }

    public function mahasiswa_lomba()
    {
        return $this->hasMany(MahasiswaLombaModel::class, 'user_id', 'user_id');
    }

    public function getNamaAttribute()
    {
        return match ($this->level->level_kode) {
            'MHS' => $this->mahasiswa?->nama,
            'DOS' => $this->dosen?->nama,
            'ADM' => $this->admin?->nama,
            default => null,
        };
    }




    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }
    public function getRole(): string
    {
        return $this->level->level_kode;
    }
}
