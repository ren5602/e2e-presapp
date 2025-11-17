<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatLombaModel extends Model
{
    use HasFactory;

    protected $table = 'm_tingkat_lomba';
    protected $primaryKey = 'tingkat_lomba_id';
    protected $guarded = [];
    protected $fillable = ['tingkat_lomba_kode', 'tingkat_lomba_nama'];

    public function lomba()
    {
        return $this->hasMany(LombaModel::class, 'tingkat_lomba_id', 'tingkat_lomba_id');
    }
}
