<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\PrestasiModel;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('loginError', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $levelKode = $user->level->level_kode ?? null;

        switch ($levelKode) {
            case 'MHS':
                $data = $this->getDashboardData();
                return view('mahasiswa.dashboard', $data);
            case 'DOS':
                $data = $this->getDashboardData();
                return view('dosen.dashboard', $data);
            case 'ADM':
                $data = $this->getDashboardData();
                return view('admin.dashboard', $data);
            default:
                return redirect('/login')->with('loginError', 'Level tidak dikenali. Silakan login kembali.');
        }
    }

    public function getDashboardData()
    {
        // Set lokal bahasa Indonesia
        Carbon::setLocale('id');
        App::setLocale('id');

        // Statistik Lomba
        $totalLomba = LombaModel::count();
        $lombaVerifikasi = LombaModel::where('status_verifikasi', 1)->count(); // Terverifikasi
        $lombaPending = LombaModel::where('status_verifikasi', null)->count();    // Pending (Menunggu)
        $lombaDitolak = LombaModel::where('status_verifikasi', 0)->count();    // Ditolak

        // Statistik Prestasi
        $totalPrestasi = PrestasiModel::count();
        $prestasiVerifikasi = PrestasiModel::where('status_verifikasi', 1)->count(); // Terverifikasi
        $prestasiPending = PrestasiModel::where('status_verifikasi', null)->count(); // Menunggu
        $prestasiDitolak = PrestasiModel::where('status_verifikasi', 0)->count();    // Ditolak

        // Chart Prestasi per tingkat lomba
        $prestasiPerTingkat = DB::table('m_tingkat_lomba as tingkat')
            ->leftJoin('m_lomba as lomba', function ($join) {
                $join->on('tingkat.tingkat_lomba_id', '=', 'lomba.tingkat_lomba_id')
                    ->where('lomba.status_verifikasi', 1);
            })
            ->leftJoin('t_prestasi as prestasi', function ($join) {
                $join->on('lomba.lomba_id', '=', 'prestasi.lomba_id')
                    ->where('prestasi.status_verifikasi', 1);
            })
            ->select('tingkat.tingkat_lomba_id', 'tingkat.tingkat_lomba_nama', DB::raw('COUNT(prestasi.prestasi_id) as total_prestasi'))
            ->groupBy('tingkat.tingkat_lomba_id', 'tingkat.tingkat_lomba_nama')
            ->get();

        // Chart Lomba per tingkat
        $lombaPerTingkat = DB::table('m_tingkat_lomba as tingkat')
            ->leftJoin('m_lomba as lomba', function ($join) {
                $join->on('tingkat.tingkat_lomba_id', '=', 'lomba.tingkat_lomba_id')    
                    ->where('lomba.status_verifikasi', 1);
            })
            ->select('tingkat.tingkat_lomba_id', 'tingkat.tingkat_lomba_nama', DB::raw('COUNT(lomba.lomba_id) as total_lomba'))
            ->groupBy('tingkat.tingkat_lomba_id', 'tingkat.tingkat_lomba_nama')
            ->get();


        // Jumlah lomba per bulan
        $jadwalLombaPerBulan = DB::table('m_lomba')
            ->where('m_lomba.status_verifikasi', 1) // Hanya ambil prestasi terverifikasi
            ->selectRaw("YEAR(tanggal_mulai) as tahun, MONTH(tanggal_mulai) as bulan_angka, COUNT(*) as total")
            ->whereNotNull('tanggal_mulai')
            ->groupBy('tahun', 'bulan_angka')
            ->orderBy('tahun')
            ->orderBy('bulan_angka')
            ->limit(12)
            ->get()
            ->map(function ($item) {
                $bulanFormat = Carbon::createFromDate($item->tahun, $item->bulan_angka, 1)->translatedFormat('F Y');
                return (object)[
                    'bulan_format' => $bulanFormat,
                    'total' => $item->total,
                ];
            });

        // Top mahasiswa dengan prestasi terbanyak
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
            ->limit(3)
            ->get();

        // Ambil daftar lomba 
        $daftarLomba = LombaModel::where('status_verifikasi', 1)
            ->orderBy('tanggal_mulai', 'desc')
            ->limit(8)
            ->get(['lomba_id', 'lomba_nama', 'tanggal_mulai', 'foto_pamflet']);

        // Ambil data prestasi
        $prestasiSaya = PrestasiModel::with('lomba')
            ->where('mahasiswa_id', auth()->user()->mahasiswa->mahasiswa_id ?? null)
            ->where('status_verifikasi', 1)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        // Ambil data mahasiswa bimbingan
        $dosenId = auth()->user()->dosen->dosen_id ?? null;
        $mahasiswaBimbingan = collect();

        if ($dosenId) {
            $mahasiswaBimbingan = MahasiswaModel::whereHas('prestasi', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get(['mahasiswa_id', 'nim', 'nama', 'kelas_id', 'foto_profile']);
        }

        return [
            'mahasiswaBimbingan' => $mahasiswaBimbingan,
            'prestasiSaya' => $prestasiSaya,
            'daftarLomba' => $daftarLomba,
            'topMahasiswaPrestasi' => $topMahasiswaPrestasi,
            'jadwalLombaPerBulan' => $jadwalLombaPerBulan,
            'prestasiPerTingkat' => $prestasiPerTingkat,
            'lombaPerTingkat' => $lombaPerTingkat,
            'totalLomba' => $totalLomba,
            'lombaVerifikasi' => $lombaVerifikasi,
            'lombaPending' => $lombaPending,
            'lombaDitolak' => $lombaDitolak,
            'totalPrestasi' => $totalPrestasi,
            'prestasiVerifikasi' => $prestasiVerifikasi,
            'prestasiPending' => $prestasiPending,
            'prestasiDitolak' => $prestasiDitolak,
        ];
    }
}