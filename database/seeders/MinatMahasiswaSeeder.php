<?php

namespace Database\Seeders;

use App\Models\BidangKeahlianModel;
use App\Models\MahasiswaModel;
use App\Models\MinatMahasiswaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MinatMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil 5 mahasiswa pertama berdasarkan ID
        $mahasiswas = MahasiswaModel::orderBy('mahasiswa_id')->limit(5)->get();

        // Ambil semua kode bidang keahlian
        $bidangKeahlian = BidangKeahlianModel::pluck('bidang_keahlian_kode')->toArray();

        // Jumlah minat tiap mahasiswa (pertama 5, terakhir 1)
        $jumlahMinat = [5, 4, 3, 2, 1];

        foreach ($mahasiswas as $index => $mahasiswa) {
            $jumlah = $jumlahMinat[$index];

            // Pilih $jumlah bidang secara acak dan unik
            $minatTerpilih = collect($bidangKeahlian)->shuffle()->take($jumlah);

            foreach ($minatTerpilih as $kodeBidang) {
                MinatMahasiswaModel::create([
                    'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                    'bidang_keahlian_id' => BidangKeahlianModel::where('bidang_keahlian_kode', $kodeBidang)->first()->bidang_keahlian_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
