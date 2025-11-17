<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisasiModel extends Model
{
    use HasFactory;

    protected $table = 'm_organisasi';
    protected $primaryKey = 'id_organisasi';
    public $timestamps = false;

    protected $fillable = [
        'organisasi_kode',
        'organisasi_nama',
    ];
}
