<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdiModel extends Model
{
    use HasFactory;
    protected $table = 'm_prodi'; // Nama tabel
    protected $primaryKey = 'prodi_id'; // Primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'prodi_kode',
        'prodi_nama',
    ];
}
