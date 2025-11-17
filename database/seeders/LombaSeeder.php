<?php

namespace Database\Seeders;

use App\Models\BidangKeahlianModel;
use App\Models\LombaModel;
use App\Models\PenyelenggaraModel;
use App\Models\TingkatLombaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tingkatLomba = TingkatLombaModel::pluck('tingkat_lomba_id')->values();
        $bidangKeahlian = BidangKeahlianModel::pluck('bidang_keahlian_id')->values();
        $penyelenggara = PenyelenggaraModel::pluck('penyelenggara_id')->values();

        $lombaData = [
            [
                'lomba_kode' => 'LMB001',
                'lomba_nama' => 'Hackathon Nasional',
                'lomba_deskripsi' => 'Kompetisi pengembangan solusi digital dalam waktu terbatas.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[1],
                'bidang_keahlian_id' => $bidangKeahlian[1],
                'penyelenggara_id' => $penyelenggara[0],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 1,
                'tanggal_mulai' => '2025-03-01',
                'tanggal_selesai' => '2025-03-03',
            ],
            [
                'lomba_kode' => 'LMB002',
                'lomba_nama' => 'UI/UX Challenge',
                'lomba_deskripsi' => 'Kompetisi merancang antarmuka dan pengalaman pengguna yang menarik.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[0],
                'bidang_keahlian_id' => $bidangKeahlian[0],
                'penyelenggara_id' => $penyelenggara[1],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 101,
                'tanggal_mulai' => '2025-04-05',
                'tanggal_selesai' => '2025-04-06',
            ],
            [
                'lomba_kode' => 'LMB003',
                'lomba_nama' => 'Web Development Competition',
                'lomba_deskripsi' => 'Lomba membangun aplikasi web dengan teknologi terkini.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[1],
                'bidang_keahlian_id' => $bidangKeahlian[2],
                'penyelenggara_id' => $penyelenggara[2],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 1,
                'tanggal_mulai' => '2025-02-10',
                'tanggal_selesai' => '2025-02-12',
            ],
            [
                'lomba_kode' => 'LMB004',
                'lomba_nama' => 'Mobile App Contest',
                'lomba_deskripsi' => 'Kompetisi pengembangan aplikasi mobile yang inovatif.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[1],
                'bidang_keahlian_id' => $bidangKeahlian[1],
                'penyelenggara_id' => $penyelenggara[0],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 101,
                'tanggal_mulai' => '2025-05-01',
                'tanggal_selesai' => '2025-05-03',
            ],
            [
                'lomba_kode' => 'LMB005',
                'lomba_nama' => 'IT Project Management Cup',
                'lomba_deskripsi' => 'Ajang kompetisi manajemen proyek teknologi informasi.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[2],
                'bidang_keahlian_id' => $bidangKeahlian[4],
                'penyelenggara_id' => $penyelenggara[1],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 1,
                'tanggal_mulai' => '2025-06-10',
                'tanggal_selesai' => '2025-06-12',
            ],
            [
                'lomba_kode' => 'LMB006',
                'lomba_nama' => 'Cybersecurity Championship',
                'lomba_deskripsi' => 'Kompetisi pengujian dan pertahanan keamanan sistem informasi.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[1],
                'bidang_keahlian_id' => $bidangKeahlian[3],
                'penyelenggara_id' => $penyelenggara[2],
                'tanggal_mulai' => '2025-07-20',
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 121,
                'tanggal_selesai' => '2025-07-22',
            ],
            [
                'lomba_kode' => 'LMB007',
                'lomba_nama' => 'AI Innovation Contest',
                'lomba_deskripsi' => 'Kompetisi inovasi dalam bidang kecerdasan buatan.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[0],
                'bidang_keahlian_id' => $bidangKeahlian[5],
                'penyelenggara_id' => $penyelenggara[0],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 1,
                'tanggal_mulai' => '2025-08-15',
                'tanggal_selesai' => '2025-08-16',
            ],
            [
                'lomba_kode' => 'LMB008',
                'lomba_nama' => 'Game Development Festival',
                'lomba_deskripsi' => 'Festival pengembangan game untuk semua platform.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[2],
                'bidang_keahlian_id' => $bidangKeahlian[3],
                'penyelenggara_id' => $penyelenggara[1],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 121,
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2025-09-04',
            ],
            [
                'lomba_kode' => 'LMB009',
                'lomba_nama' => 'Data Science Competition',
                'lomba_deskripsi' => 'Kompetisi analisis data dan machine learning.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[1],
                'bidang_keahlian_id' => $bidangKeahlian[6],
                'penyelenggara_id' => $penyelenggara[2],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 1,
                'tanggal_mulai' => '2025-10-10',
                'tanggal_selesai' => '2025-10-12',
            ],
            [
                'lomba_kode' => 'LMB010',
                'lomba_nama' => 'Robotic Engineering Challenge',
                'lomba_deskripsi' => 'Tantangan membangun dan memprogram robot untuk menyelesaikan tugas.',
                'link_website' => 'https://example.com',
                'tingkat_lomba_id' => $tingkatLomba[0],
                'bidang_keahlian_id' => $bidangKeahlian[7],
                'penyelenggara_id' => $penyelenggara[0],
                'foto_pamflet' => 'images/pamflet.jpg',
                'user_id' => 101,
                'tanggal_mulai' => '2025-11-20',
                'tanggal_selesai' => '2025-11-22',
            ],
        ];


        foreach ($lombaData as &$data) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
            $data['status_verifikasi'] = true;
        }

        LombaModel::insert($lombaData);
    }
}
