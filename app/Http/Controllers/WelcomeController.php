<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $topMahasiswaPrestasi = MahasiswaModel::select('mahasiswa_id', 'nama', 'kelas_id')
            ->with([
                'kelas.prodi',
            ])
            ->withSum([
                'prestasi as total_poin' => function ($query) {
                    $query->where('status_verifikasi', 1);
                }
            ], 'poin')
            ->withCount([
                'prestasi as total_prestasi' => function ($query) {
                    $query->where('status_verifikasi', 1);
                }
            ])
            ->having('total_poin', '>', 0)
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

        $daftarLomba = LombaModel::where('status_verifikasi', 1)
            ->orderBy('created_at', 'desc') // berdasarkan waktu pendaftaran terbaru
            ->limit(3)
            ->get(['lomba_id', 'lomba_nama', 'tanggal_mulai', 'foto_pamflet']);


        return view('welcome', compact('topMahasiswaPrestasi', 'daftarLomba'));
    }
}
