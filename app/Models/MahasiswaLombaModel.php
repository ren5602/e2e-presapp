<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Guard;

class MahasiswaLombaModel extends Model
{
    use HasFactory;

    protected $table = 'r_mahasiswa_lomba';
    protected $primaryKey = 'mahasiswa_lomba_id';

    protected $guarded = ['mahasiswa_lomba_id'];

    public function lomba()
    {
        return $this->belongsTo(LombaModel::class, 'lomba_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
