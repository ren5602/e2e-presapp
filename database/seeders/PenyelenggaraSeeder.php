<?php

namespace Database\Seeders;

use App\Models\KotaModel;
use App\Models\NegaraModel;
use App\Models\PenyelenggaraModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenyelenggaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari tabel referensi (gunakan yang pertama jika ada)
        // $kota = DB::table('m_kota')->pluck('kota_id')->toArray();
        // $negara = DB::table('m_negara')->pluck('negara_id')->toArray();

        PenyelenggaraModel::insert([
            [
                'penyelenggara_nama' => 'Kementerian Kominfo',
                'kota_id' => KotaModel::where('kota_nama', 'like', '%Jakarta%')->value('kota_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penyelenggara_nama' => 'Universitas Teknologi Digital',
                'kota_id' => KotaModel::where('kota_nama', 'like', '%Jakarta%')->value('kota_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penyelenggara_nama' => 'Asosiasi IT Nasional',
                'kota_id' => KotaModel::where('kota_nama', 'like', '%Surabaya%')->value('kota_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penyelenggara_nama' => 'Digital Startup Center',
                'kota_id' => KotaModel::where('kota_nama', 'like', '%Surabaya%')->value('kota_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'penyelenggara_nama' => 'Global Tech Conference',
                'kota_id' => KotaModel::where('kota_nama', 'like', '%Surabaya%')->value('kota_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
