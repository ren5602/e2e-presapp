<?php

namespace Database\Seeders;

use App\Models\MahasiswaOrganisasiModel;
use App\Models\OrganisasiModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'organisasi_kode' => 'HMJ',
                'organisasi_nama' => 'Himpunan Mahasiswa Jurusan',
            ],
            [
                'organisasi_kode' => 'UKM',
                'organisasi_nama' => 'Unit Kegiatan Mahasiswa',
            ],
            [
                'organisasi_kode' => 'BEM',
                'organisasi_nama' => 'Badan Eksekutif Mahasiswa',
            ],
            [
                'organisasi_kode' => 'OLK',
                'organisasi_nama' => 'Organisasi Luar Kampus',
            ],
        ];
        OrganisasiModel::insert($data);

        MahasiswaOrganisasiModel::insert([
            [
                'organisasi_id' => 1,
                'mahasiswa_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organisasi_id' => 1,
                'mahasiswa_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organisasi_id' => 2,
                'mahasiswa_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organisasi_id' => 3,
                'mahasiswa_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organisasi_id' => 4,
                'mahasiswa_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
