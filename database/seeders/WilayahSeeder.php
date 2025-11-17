<?php

namespace Database\Seeders;

use App\Models\KotaModel;
use App\Models\NegaraModel;
use App\Models\ProvinsiModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indonesia = NegaraModel::where('negara_kode', 'ID')->first();

        if (!$indonesia) {
            $this->command->warn("Negara Indonesia (ID) belum tersedia. Lewati WilayahSeeder.");
            return;
        }

        try {
            $provinsiResponse = Http::timeout(5)
                ->withoutVerifying()
                ->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

            if (!$provinsiResponse->successful()) {
                $this->command->warn('Gagal mengambil data provinsi dari API Emsifa.');
                return;
            }

            $provinsis = $provinsiResponse->json();

            foreach ($provinsis as $provinsiData) {
                $provinsi = ProvinsiModel::firstOrCreate([
                    'provinsi_nama' => ucwords(strtolower($provinsiData['name'])),
                    'negara_id' => $indonesia->negara_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                try {
                    $kotaResponse = Http::timeout(5)
                        ->withoutVerifying()
                        ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinsiData['id']}.json");

                    if (!$kotaResponse->successful()) {
                        $this->command->warn("Gagal mengambil kota dari Provinsi ID: {$provinsiData['id']}");
                        continue;
                    }

                    $kotas = $kotaResponse->json();

                    foreach ($kotas as $kotaData) {
                        KotaModel::firstOrCreate([
                            'kota_nama' => ucwords(strtolower($kotaData['name'])),
                            'provinsi_id' => $provinsi->provinsi_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Error mengambil kota untuk provinsi {$provinsiData['name']}: {$e->getMessage()}");
                    Log::error("WilayahSeeder: Kota error: " . $e->getMessage());
                }
            }

            $response = Http::get('https://restcountries.com/v3.1/all?fields=name,cca2,capital');

            if ($response->successful()) {
                $countries = $response->json();

                foreach ($countries as $country) {
                    if ($country['cca2'] === 'ID') {
                        continue;
                    }

                    $negara = NegaraModel::where('negara_kode', $country['cca2'])->first();

                    if ($negara) {
                        $provNegara = ProvinsiModel::firstOrCreate(
                            [
                                'provinsi_nama' => ucwords(strtolower($country['name']['common'])),
                                'negara_id' => $negara->negara_id,
                            ],
                            [
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        // Ambil ibu kota jika tersedia
                        $capitalName = isset($country['capital'][0]) ? ucwords(strtolower($country['capital'][0])) : null;

                        if ($capitalName) {
                            KotaModel::firstOrCreate(
                                [
                                    'kota_nama' => $capitalName,
                                    'provinsi_id' => $provNegara->provinsi_id,
                                ],
                                [
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]
                            );
                        }
                    }
                }
            } else {
                $this->command->error('Gagal mengambil data negara dari API.');
            }

            $this->command->info('WilayahSeeder selesai.');
        } catch (\Exception $e) {
            $this->command->warn('WilayahSeeder gagal: ' . $e->getMessage());
            Log::error('WilayahSeeder error: ' . $e->getMessage());
        }
    }
}