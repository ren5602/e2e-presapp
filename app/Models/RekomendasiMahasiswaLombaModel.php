<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomendasiMahasiswaLombaModel extends Model
{
    use HasFactory;

    protected $table = 'r_rekomendasi_mahasiswa_lomba';

    protected $fillable = [
        'mahasiswa_id',
        'lomba_id',
        'rank',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function lomba()
    {
        return $this->belongsTo(LombaModel::class, 'lomba_id');
    }
}
