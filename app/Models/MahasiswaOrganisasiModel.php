<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaOrganisasiModel extends Model
{
    use HasFactory;

    protected $table = 'r_mahasiswa_organisasi';
    protected $primaryKey = 'mahasiswa_organisasi_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'mahasiswa_id',
        'organisasi_id',
    ];

    public function organisasi()
    {
        return $this->belongsTo(OrganisasiModel::class, 'organisasi_id', 'organisasi_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
