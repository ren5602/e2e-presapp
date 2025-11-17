<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use App\Models\LevelModel;
use App\Models\UserModel;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = UserModel::create([
            'username' => 'admin',
            'password' => 'admin123',
            'level_id' => LevelModel::where('level_kode', 'ADM')->first()->level_id,
        ]);
        
        AdminModel::create([
            // 'username' => 'admin',
            // 'password' => 'admin123',
            'user_id' => $userId->user_id,
            'nama' => 'Michelle Dorani',
            'email' => 'admin@example.com',
            'no_tlp' => '081234567890',
            'foto_profile' => null,
        ]);
    }
}
