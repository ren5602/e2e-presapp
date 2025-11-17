<?php

namespace Database\Seeders;

use App\Models\LevelModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $level = [
            [
                'level_kode' => 'MHS',
                'level_nama' => 'Mahasiswa',
            ],
            [
                'level_kode' => 'DOS',
                'level_nama' => 'Dosen',
            ],
            [
                'level_kode' => 'ADM',
                'level_nama' => 'Admin',
            ]

        ];

        LevelModel::insert($level);
    }
}
