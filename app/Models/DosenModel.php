<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class DosenModel extends Model
{
    use HasFactory;
    // Nama tabel yang digunakan
    protected $table = 'm_dosen';

    // Nama primary key
    protected $primaryKey = 'dosen_id';

    // Apakah primary key bertipe auto-increment
    public $incrementing = true;

    // Tipe data primary key
    protected $keyType = 'int';

    // Kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'nidn',
        // 'password',
        'nama',
        'email',
        'no_tlp',
        'foto_profile',
    ];

    // Apakah timestamps digunakan (created_at & updated_at)
    public $timestamps = true;

    // protected $hidden = ['password'];
    // protected $casts = ['password' => 'hashed'];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function prestasi(){
        return $this->hasMany(PrestasiModel::class, 'dosen_id', 'dosen_id');
    }
}
