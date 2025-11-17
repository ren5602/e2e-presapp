<?php

namespace Database\Seeders;

use App\Models\ProdiModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProdiModel::insert([
            [
                'prodi_kode' => 'D4TIF',
                'prodi_nama' => 'D4 - Teknik Informatika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'prodi_kode' => 'D4SIB',
                'prodi_nama' => 'D4 - Sistem Informasi Bisnis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'prodi_kode' => 'D2PLS',
                'prodi_nama' => 'D2 - Piranti Lunak Situs',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
