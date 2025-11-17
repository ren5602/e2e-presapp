<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'r_keahlian_mahasiswa';
    protected $primaryKey = 'keahlian_mahasiswa_id';
    protected $fillable = ['mahasiswa_id', 'bidang_keahlian_id', 'file_sertifikat'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function bidang_keahlian()
    {
        return $this->belongsTo(BidangKeahlianModel::class, 'bidang_keahlian_id');
    }
}
