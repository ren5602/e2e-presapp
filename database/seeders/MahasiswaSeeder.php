<?php

namespace Database\Seeders;

use App\Models\KelasModel;
use App\Models\LevelModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil seluruh kelas_id berdasarkan kelas_kode
        $kelas = KelasModel::all()->pluck('kelas_id', 'kelas_kode');

        // $mahasiswaData = [];

        // Membuat data mahasiswa random
        $prefixNim = '234172';

        for ($i = 1; $i <= 100; $i++) {
            $kelasKode = array_rand($kelas->toArray());
            $kelasId = $kelas[$kelasKode];
            $angkatan = '';

            if (str_contains($kelasKode, '1')) {
                $angkatan = '2024';
            } elseif (str_contains($kelasKode, '2')) {
                $angkatan = '2023';
            } else if (str_contains($kelasKode, '3')) {
                $angkatan = '2022';
            } else if (str_contains($kelasKode, '4')) {
                $angkatan = '2021';
            } else {
                $angkatan = '2020';
            }

            // Format: 234172 + 3 digit increment, leading zero
            $nim = $prefixNim . str_pad($i, 3, '0', STR_PAD_LEFT); // e.g., 234172001, ..., 234172100

            $userId = UserModel::create([
                'username' => $nim,
                'password' => 'mahasiswa123',
                'level_id' => LevelModel::where('level_kode', 'MHS')->first()->level_id,
            ]);

            MahasiswaModel::create([
                'user_id' => $userId->user_id,
                'tahun_angkatan' => $angkatan,
                'nim' => $nim,
                'nama' => $faker->firstName . ' ' . $faker->lastName,
                'kelas_id' => $kelasId,
                'no_tlp' => $faker->unique()->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'alamat' => $faker->address,
                'ipk' => $faker->randomFloat(2, 0, 4),
                'foto_profile' => null,
            ]);
        }
    }
}
