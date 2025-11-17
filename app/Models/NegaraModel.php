<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegaraModel extends Model
{
    use HasFactory;

    protected $table = 'm_negara';
    protected $primaryKey = 'negara_id';

    protected $fillable = ['negara_kode', 'negara_nama'];
}
