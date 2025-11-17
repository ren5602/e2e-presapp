<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyelenggaraModel extends Model
{
    use HasFactory;

    protected $table = 'm_penyelenggara';
    protected $primaryKey = 'penyelenggara_id';
    protected $fillable = ['penyelenggara_nama', 'kota_id', 'negara_id'];

    public function kota()
    {
        return $this->belongsTo(KotaModel::class, 'kota_id', 'kota_id');
    }

    public function negara()
    {
        return $this->belongsTo(NegaraModel::class, 'negara_id', 'negara_id');
    }

    public function lomba()
    {
        return $this->hasMany(LombaModel::class, 'penyelenggara_id', 'penyelenggara_id');
    }
}
