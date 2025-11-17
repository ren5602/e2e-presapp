<?php

namespace Database\Seeders;

use App\Models\TingkatLombaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TingkatLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TingkatLombaModel::insert([
            [
                'tingkat_lomba_kode' => 'INT',
                'tingkat_lomba_nama' => 'Internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tingkat_lomba_kode' => 'NAS',
                'tingkat_lomba_nama' => 'Nasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tingkat_lomba_kode' => 'PRO',
                'tingkat_lomba_nama' => 'Provinsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tingkat_lomba_kode' => 'KAB',
                'tingkat_lomba_nama' => 'Kabupaten/Kota',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
