<?php

namespace Database\Seeders;

use App\Models\KelasModel;
use App\Models\ProdiModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil prodi_id berdasarkan prodi_kode
        $prodis = ProdiModel::whereIn('prodi_kode', ['D4TIF', 'D4SIB', 'D4PLS'])
            ->pluck('prodi_id', 'prodi_kode');

        $kelasData = [];

        // Aturan jumlah huruf kelas
        $kelasHuruf = [
            'D4TIF' => range('A', 'I'),
            'D4SIB' => range('A', 'H'),
            'D4PLS' => range('A', 'H'),
        ];

        // Mapping singkatan nama
        $namaSingkat = [
            'D4TIF' => 'TI',
            'D4SIB' => 'SIB',
            'D4PLS' => 'PLS',
        ];

        foreach ($kelasHuruf as $kodeProdi => $hurufs) {
            $prodiId = $prodis[$kodeProdi] ?? null;

            if ($prodiId) {
                for ($tingkat = 1; $tingkat <= 4; $tingkat++) {
                    foreach ($hurufs as $huruf) {
                        $kodeKelas = "{$kodeProdi}-{$tingkat}{$huruf}";
                        $singkatan = $namaSingkat[$kodeProdi] ?? $kodeProdi;
                        $namaKelas = "{$singkatan} - {$tingkat}{$huruf}";

                        $kelasData[] = [
                            'kelas_kode' => $kodeKelas,
                            'kelas_nama' => $namaKelas,
                            'prodi_id' => $prodiId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        KelasModel::insert($kelasData);
    }
}
