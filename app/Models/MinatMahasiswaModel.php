<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinatMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'r_minat_mahasiswa';
    protected $primaryKey = 'minat_mahasiswa_id';
    protected $guarded = ['minat_mahasiswa_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function bidang_keahlian()
    {
        return $this->belongsTo(BidangKeahlianModel::class, 'bidang_keahlian_id');
    }
}
