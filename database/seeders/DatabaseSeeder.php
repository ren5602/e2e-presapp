<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            NegaraSeeder::class,
            WilayahSeeder::class,
            ProdiSeeder::class,
            KelasSeeder::class,
            LevelSeeder::class,
            MahasiswaSeeder::class,
            DosenSeeder::class,
            AdminSeeder::class,
            BidangKeahlianSeeder::class,
            PenyelenggaraSeeder::class,
            TingkatLombaSeeder::class,
            MinatMahasiswaSeeder::class,
            KeahlianMahasiswaSeeder::class,
            LombaSeeder::class,
            PrestasiSeeder::class,
            OrganisasiSeeder::class,
        ]);
    }
}
