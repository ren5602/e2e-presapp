<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBidangKeahlianModel extends Model
{
    use HasFactory;

    protected $table = "m_kategori_bidang_keahlian";
    protected $primaryKey = "kategori_bidang_keahlian_id";

    protected $fillable = [
        'kategori_bidang_keahlian_kode',
        'kategori_bidang_keahlian_nama',
    ];

    public $timestamps = false;

    public function bidangKeahlian()
    {
        return $this->hasMany(BidangKeahlianModel::class, 'kategori_bidang_keahlian_id', 'kategori_bidang_keahlian_id');
    }
}
