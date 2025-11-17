<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiModel extends Model
{
    use HasFactory;
    protected $table = 't_prestasi';
    protected $primaryKey = 'prestasi_id';
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'prestasi_nama',
        'lomba_id',
        'juara',
        'nama_juara',
        'tanggal_perolehan',
        'file_sertifikat',
        'file_bukti_foto',
        'file_surat_tugas',
        'file_surat_undangan',
        'file_proposal',
        'poin',
        'status_verifikasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
    }

    public function lomba()
    {
        return $this->belongsTo(LombaModel::class, 'lomba_id');
    }
}
