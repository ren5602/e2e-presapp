<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinsiModel extends Model
{
    use HasFactory;

    protected $table = 'm_provinsi';
    protected $primaryKey = 'provinsi_id';

    protected $fillable = [
        'provinsi_nama',
        'negara_id'
    ];

    public function negara()
    {
        return $this->belongsTo(NegaraModel::class, 'negara_id', 'negara_id');
    }
}
