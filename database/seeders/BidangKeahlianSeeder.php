<?php

namespace Database\Seeders;

use App\Models\BidangKeahlianModel;
use App\Models\KategoriBidangKeahlianModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangKeahlianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Insert kategori bidang keahlian
        $kategoriList = [
            'IT' => 'Teknologi Informasi',
            'ELK' => 'Elektro',
            'SCI' => 'Ilmu Pengetahuan',
            'DSN' => 'Desain',
            'BSN' => 'Bisnis',
            'MMT' => 'Multimedia',
        ];

        $kategoriIdMap = [];

        foreach ($kategoriList as $kode => $nama) {
            $kategori = KategoriBidangKeahlianModel::create([
                'kategori_bidang_keahlian_kode' => $kode,
                'kategori_bidang_keahlian_nama' => $nama,
            ]);
            $kategoriIdMap[$kode] = $kategori->kategori_bidang_keahlian_id;
        }

        // Step 2: Insert bidang keahlian yang terkait dengan kategori
        $bidangList = [
            ['kode' => 'PRO', 'nama' => 'Programming', 'kategori' => 'IT'],
            ['kode' => 'WEB', 'nama' => 'Web Development', 'kategori' => 'IT'],
            ['kode' => 'GAM', 'nama' => 'Game Development', 'kategori' => 'IT'],
            ['kode' => 'NET', 'nama' => 'Networking', 'kategori' => 'IT'],
            ['kode' => 'SEC', 'nama' => 'Cyber Security', 'kategori' => 'IT'],
            ['kode' => 'DBA', 'nama' => 'Database Administration', 'kategori' => 'IT'],
            ['kode' => 'MLA', 'nama' => 'Machine Learning', 'kategori' => 'IT'],
            ['kode' => 'AIK', 'nama' => 'Artificial Intelligence', 'kategori' => 'IT'],
            ['kode' => 'CLD', 'nama' => 'Cloud Computing', 'kategori' => 'IT'],
            ['kode' => 'MOB', 'nama' => 'Mobile Development', 'kategori' => 'IT'],
            ['kode' => 'IOT', 'nama' => 'Internet of Things', 'kategori' => 'ELK'],
            ['kode' => 'ROB', 'nama' => 'Robotics', 'kategori' => 'ELK'],
            ['kode' => 'EMS', 'nama' => 'Embedded Systems', 'kategori' => 'ELK'],
            ['kode' => 'DSC', 'nama' => 'Data Science', 'kategori' => 'SCI'],
            ['kode' => 'BDA', 'nama' => 'Big Data Analytics', 'kategori' => 'SCI'],
            ['kode' => 'SWE', 'nama' => 'Software Engineering', 'kategori' => 'IT'],
            ['kode' => 'TST', 'nama' => 'Software Testing', 'kategori' => 'IT'],
            ['kode' => 'VRR', 'nama' => 'Virtual Reality', 'kategori' => 'MMT'],
            ['kode' => 'AUR', 'nama' => 'Augmented Reality', 'kategori' => 'MMT'],
            ['kode' => 'UXD', 'nama' => 'UI/UX Design', 'kategori' => 'DSN'],
            ['kode' => 'DES', 'nama' => 'Graphic Design', 'kategori' => 'DSN'],
            ['kode' => 'DVO', 'nama' => 'DevOps', 'kategori' => 'IT'],
            ['kode' => 'ITS', 'nama' => 'IT Support', 'kategori' => 'IT'],
            ['kode' => 'BIS', 'nama' => 'Business Intelligence', 'kategori' => 'BSN'],
            ['kode' => 'CVN', 'nama' => 'Computer Vision', 'kategori' => 'SCI'],
            ['kode' => 'BLC', 'nama' => 'Blockchain', 'kategori' => 'SCI'],
        ];

        foreach ($bidangList as $bidang) {
            BidangKeahlianModel::create([
                'bidang_keahlian_kode' => $bidang['kode'],
                'bidang_keahlian_nama' => $bidang['nama'],
                'kategori_bidang_keahlian_id' => $kategoriIdMap[$bidang['kategori']],
            ]);
        }

    }
}
