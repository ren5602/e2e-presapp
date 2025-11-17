<?php

namespace Database\Seeders;

use App\Models\NegaraModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NegaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get('https://restcountries.com/v3.1/all?fields=name,cca2');

            if ($response->successful()) {
                $countries = $response->json();

                foreach ($countries as $country) {
                    NegaraModel::updateOrCreate(
                        ['negara_kode' => $country['cca2'] ?? null],
                        [
                            'negara_nama' => $country['name']['common'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                $this->command->info('NegaraSeeder berhasil.');
            } else {
                $this->command->warn('NegaraSeeder: Gagal mengambil data dari API. Status: ' . $response->status());
                Log::warning('NegaraSeeder: API response failed', ['status' => $response->status()]);
            }
        } catch (\Exception $e) {
            $this->command->warn('NegaraSeeder: Terjadi error saat fetch data negara.');
            Log::error('NegaraSeeder error: ' . $e->getMessage());
        }
    }
}