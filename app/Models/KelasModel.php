<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasModel extends Model
{
    use HasFactory;

    protected $table = 'm_kelas';
    protected $primaryKey = 'kelas_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kelas_kode',
        'kelas_nama',
        'prodi_id',
    ];

    // Relasi ke Prodi
    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'prodi_id', 'prodi_id');
    }
}
