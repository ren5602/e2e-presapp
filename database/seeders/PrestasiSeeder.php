<?php

namespace Database\Seeders;

use App\Models\DosenModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\PrestasiModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswa = MahasiswaModel::pluck('mahasiswa_id');
        $dosen = DosenModel::pluck('dosen_id');
        $lomba = LombaModel::pluck('lomba_id');

        $prestasi = [
            [
                'mahasiswa_id' => $mahasiswa[0],
                'dosen_id' => $dosen[0],
                'prestasi_nama' => 'Juara 1 Lomba Desain UI/UX Nasional',
                'lomba_id' => $lomba[0],
                'juara' => '1',
                'nama_juara' => 'Juara 1 Nasional',
                'tanggal_perolehan' => '2024-01-12',
                'file_sertifikat' => 'sertifikat1.pdf',
                'file_bukti_foto' => 'foto1.jpg',
                'file_surat_tugas' => 'tugas1.pdf',
                'file_surat_undangan' => 'undangan1.pdf',
                'file_proposal' => 'proposal1.pdf',
                'poin' => 10,
                'status_verifikasi' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => $mahasiswa[1],
                'dosen_id' => $dosen[1],
                'prestasi_nama' => 'Juara 2 Hackathon Regional',
                'lomba_id' => $lomba[1],
                'juara' => '2',
                'nama_juara' => 'Juara 2 Regional',
                'tanggal_perolehan' => '2024-02-06',
                'file_sertifikat' => 'sertifikat2.pdf',
                'file_bukti_foto' => 'foto2.jpg',
                'file_surat_tugas' => 'tugas2.pdf',
                'file_surat_undangan' => 'undangan2.pdf',
                'file_proposal' => null,
                'poin' => 8,
                'status_verifikasi' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => $mahasiswa[2],
                'dosen_id' => $dosen[2],
                'prestasi_nama' => 'Juara Harapan 1 CTF Competition',
                'lomba_id' => $lomba[2],
                'juara' => '4',
                'nama_juara' => 'Harapan 1',
                'tanggal_perolehan' => '2024-03-17',
                'file_sertifikat' => 'sertifikat3.pdf',
                'file_bukti_foto' => 'foto3.jpg',
                'file_surat_tugas' => 'tugas3.pdf',
                'file_surat_undangan' => 'undangan3.pdf',
                'file_proposal' => 'proposal3.pdf',
                'poin' => 6,
                'status_verifikasi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => $mahasiswa[3],
                'dosen_id' => $dosen[1],
                'prestasi_nama' => 'Finalis Web Development Competition',
                'lomba_id' => $lomba[3],
                'juara' => 4,
                'nama_juara' => 'Harapan 2',
                'tanggal_perolehan' => '2024-04-22',
                'file_sertifikat' => 'sertifikat4.pdf',
                'file_bukti_foto' => 'foto4.jpg',
                'file_surat_tugas' => 'tugas4.pdf',
                'file_surat_undangan' => 'undangan4.pdf',
                'file_proposal' => null,
                'poin' => 4,
                'status_verifikasi' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => $mahasiswa[4],
                'dosen_id' => $dosen[0],
                'prestasi_nama' => 'Juara 3 Data Science Competition',
                'lomba_id' => $lomba[4],
                'juara' => '3',
                'nama_juara' => 'Juara 3 Nasional',
                'tanggal_perolehan' => '2024-05-12',
                'file_sertifikat' => 'sertifikat5.pdf',
                'file_bukti_foto' => 'foto5.jpg',
                'file_surat_tugas' => 'tugas5.pdf',
                'file_surat_undangan' => 'undangan5.pdf',
                'file_proposal' => 'proposal5.pdf',
                'poin' => 7,
                'status_verifikasi' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        PrestasiModel::insert($prestasi);
    }
}
