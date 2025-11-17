<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LombaModel extends Model
{
    use HasFactory;

    protected $table = 'm_lomba';

    protected $primaryKey = 'lomba_id';

    protected $fillable = [
        'lomba_kode',
        'lomba_nama',
        'lomba_deskripsi',
        'link_website',
        'tingkat_lomba_id',
        'bidang_keahlian_id',
        'penyelenggara_id',
        'jumlah_anggota',
        'tanggal_mulai',
        'tanggal_selesai',
        'foto_pamflet',
        'status_verifikasi',
        'user_id'
    ];

    public function penyelenggara()
    {
        return $this->belongsTo(PenyelenggaraModel::class, 'penyelenggara_id', 'penyelenggara_id');
    }

    public function tingkat()
    {
        return $this->belongsTo(TingkatLombaModel::class, 'tingkat_lomba_id', 'tingkat_lomba_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangKeahlianModel::class, 'bidang_keahlian_id', 'bidang_keahlian_id');
    }

    public function prestasi()
    {
        return $this->hasMany(PrestasiModel::class, 'lomba_id', 'lomba_id');
    }

    public function rekomendasi()
    {
        return $this->hasMany(RekomendasiMahasiswaLombaModel::class, 'lomba_id', 'lomba_id');
    }

    public function mahasiswa_terdaftar()
    {
        return $this->hasMany(MahasiswaLombaModel::class, 'lomba_id', 'lomba_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
