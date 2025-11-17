<?php

namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\LombaModel;
use App\Models\MahasiswaLombaModel;
use App\Models\MahasiswaModel;
use App\Models\PrestasiModel;
use App\Models\RekomendasiMahasiswaLombaModel;
use Auth;
use Carbon\Carbon;
use Doctrine\Inflector\Rules\English\Rules;
use Http;
use Illuminate\Http\Request;
use Log;
use Validator;
use Yajra\DataTables\DataTables;

class RekomendasiMahasiswaController extends Controller
{
    public function index()
    {
        $lomba = LombaModel::where('tanggal_mulai', '>', Carbon::now())
            ->where('status_verifikasi', 1)
            ->get();
        return view('admin.rekomendasi.daftar_rekomendasi')->with('lomba', $lomba);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = RekomendasiMahasiswaLombaModel::with([
                'mahasiswa',
                'lomba'
            ]);

            if ($request->lomba_id) {
                $query->where('lomba_id', $request->lomba_id);
            }

            $rekomendasi = $query->get();

            return DataTables::of($rekomendasi)
                ->addIndexColumn()
                ->addColumn('nama_lomba', function ($row) {
                    return $row->lomba->lomba_nama;
                })
                ->addColumn('rekomendasi_mahasiswa', function ($row) {
                    return $row->mahasiswa->nama;
                })
                ->addColumn('nim', function ($row) {
                    return $row->mahasiswa->nim;
                })
                ->addColumn('rank', function ($row) {
                    return $row->rank;
                })

                ->make(true);
        }
    }

    public function show_refresh()
    {
        return view('admin.rekomendasi.refresh_rekomendasi');
    }

    public function refresh(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'metode' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Metode harus dipilih.',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                if ($request->metode == 'topsis') {
                    $this->rekomendasiByTopsis();
                } elseif ($request->metode == 'saw') {
                    $this->rekomendasiBySAW();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data rekomendasi berhasil di perbarui.'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data rekomendasi gagal di perbarui. ' . $th->getMessage()
                ]);
            }

        }
    }

    public function confirm()
    {
        return view('admin.rekomendasi.confirm_delete_rekomendasi');
    }

    public function destroy()
    {
        RekomendasiMahasiswaLombaModel::truncate();
        MahasiswaLombaModel::where('pengaju', 'SPK')->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data rekomendasi berhasil di hapus.'
        ]);
    }

    // KRITERIA

    //IPK
    //Keahlian
    //Jumlah Prestasi
    //Tingkat lomba prestasi
    //Poin Prestasi
    //Bidang Prestasi 
    //Minat
    //Organisasi


    public static function rekomendasiByTopsis()
    {

        $allLomba = LombaModel::with([
            'bidang.kategoriBidangKeahlian',
            'penyelenggara.kota.provinsi.negara',
            'tingkat'
        ])
            ->where('tanggal_mulai', '>', Carbon::now())
            ->where('status_verifikasi', 1)
            ->get();
        // dd($allLomba);

        RekomendasiMahasiswaLombaModel::truncate();

        foreach ($allLomba as $lomba) {
            $response = Http::post('http://127.0.0.1:8000/api/topsis', [
                "jumlah_anggota" => $lomba->jumlah_anggota,
                "bobot" => self::getBobot($lomba),
                "kriteria" => ["benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit"],
                "mahasiswa" => self::getAlternatif($lomba)
            ]);

            if ($response->successful()) {
                MahasiswaLombaModel::where('lomba_id', $lomba->lomba_id)
                    ->where('pengaju', 'SPK')->delete();

                foreach ($response->json() as $mahasiswa) {
                    RekomendasiMahasiswaLombaModel::create([
                        "mahasiswa_id" => $mahasiswa['mahasiswa_id'],
                        "lomba_id" => $lomba->lomba_id,
                        "rank" => $mahasiswa['rank']
                    ]);

                    // Cek apakah kombinasi mahasiswa_id dan lomba_id sudah ada
                    $sudahAda = MahasiswaLombaModel::where('mahasiswa_id', $mahasiswa['mahasiswa_id'])
                        ->where('lomba_id', $lomba->lomba_id)
                        ->exists();

                    // Hanya insert jika belum ada
                    if (!$sudahAda) {
                        MahasiswaLombaModel::create([
                            "mahasiswa_id" => $mahasiswa['mahasiswa_id'],
                            "lomba_id" => $lomba->lomba_id,
                            'status_verifikasi' => true,
                            'status_verifikasi_from_mhs' => true,
                            'pengaju' => 'SPK',
                            'user_id' => Auth::user()->user_id
                        ]);
                    }
                }
            } else {
                Log::error('Gagal mendapatkan data dari TOPSIS API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        }
    }
    public static function rekomendasiBySAW()
    {
        $allLomba = LombaModel::with([
            'bidang.kategoriBidangKeahlian',
            'penyelenggara.kota.provinsi.negara',
            'tingkat'
        ])
            ->where('tanggal_mulai', '>', Carbon::now())
            ->where('status_verifikasi', 1)
            ->get();
        // dd($allLomba);

        RekomendasiMahasiswaLombaModel::truncate();

        foreach ($allLomba as $lomba) {
            $response = Http::post('http://127.0.0.1:8000/api/saw', [
                "jumlah_anggota" => $lomba->jumlah_anggota,
                // "bobot" => [0.15, 0.1, 0.15, 0.2, 0.1, 0.1, 0.1, 0.1],
                "bobot" => self::getBobot($lomba),
                "kriteria" => ["benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit"],
                "mahasiswa" => self::getAlternatif($lomba)
            ]);

            if ($response->successful()) {
                MahasiswaLombaModel::where('lomba_id', $lomba->lomba_id)
                    ->where('pengaju', 'SPK')->delete();

                foreach ($response->json() as $mahasiswa) {
                    // dd($mahasiswa[ra]);
                    RekomendasiMahasiswaLombaModel::create([
                        "mahasiswa_id" => $mahasiswa['mahasiswa_id'],
                        "lomba_id" => $lomba->lomba_id,
                        "rank" => $mahasiswa['rank']
                    ]);

                    // Cek apakah kombinasi mahasiswa_id dan lomba_id sudah ada
                    $sudahAda = MahasiswaLombaModel::where('mahasiswa_id', $mahasiswa['mahasiswa_id'])
                        ->where('lomba_id', $lomba->lomba_id)
                        ->exists();

                    // Hanya insert jika belum ada
                    if (!$sudahAda) {
                        MahasiswaLombaModel::create([
                            "mahasiswa_id" => $mahasiswa['mahasiswa_id'],
                            "lomba_id" => $lomba->lomba_id,
                            'status_verifikasi' => true,
                            'status_verifikasi_from_mhs' => true,
                            'pengaju' => 'SPK',
                            'user_id' => Auth::user()->user_id
                        ]);
                    }
                }
            } else {
                Log::error('Gagal mendapatkan data dari SAW API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        }
    }

    public static function getBobot(LombaModel $lomba)
    {
        $response = Http::post('http://127.0.0.1:8000/api/psi', [
            "kriteria" => ["benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit"],
            "mahasiswa" => self::getAlternatif($lomba)
        ]);

        if ($response->successful()) {
            $bobot = $response->json()['bobot'];
            // Misalnya ingin mencetak atau memproses bobotnya
            return $bobot;
        } else {
            Log::error('Gagal menghitung bobot dengan PSI', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }
    }

    private static function getAlternatif(LombaModel $lomba)
    {
        $allMahasiswa = MahasiswaModel::with(
            'prestasi.lomba.bidang.kategoriBidangKeahlian',
            'prestasi.lomba.tingkat',
            'prestasi.lomba.penyelenggara.kota.provinsi.negara',
            'minat',
            'keahlian.bidang_keahlian.kategoriBidangKeahlian',
            'organisasi'
        )->get();

        $allternatif = [];
        foreach ($allMahasiswa as $mahasiswa) {
            $allternatif[] =
                [
                    "mahasiswa_id" => $mahasiswa->mahasiswa_id,
                    "ipk" => $mahasiswa->ipk,
                    "keahlian" => self::kesesuaianKeahlian($mahasiswa->keahlian, $lomba),
                    "jumlah_prestasi" => $mahasiswa->prestasi->where('status_verifikasi', 1)->count(),
                    "kesesuaian_bidang_prestasi" => self::kesesuaianBidangPrestasi($mahasiswa->prestasi, $lomba),
                    "tingkat_lomba_prestasi" => self::tingkatLombaPrestasi($mahasiswa->prestasi->where('status_verifikasi', 1)),
                    "poin_prestasi" => $mahasiswa->prestasi()->where('status_verifikasi', 1)->sum('poin'),
                    "minat" => self::kesesuaianMinat($mahasiswa->minat, $lomba),
                    "organisasi" => count($mahasiswa->organisasi)
                ];
        }

        // dd(json_encode($allternatif));
        return $allternatif;
    }

    private static function totalPoinMahasiswa($listPrestasimahasiswa)
    {
        $totalPoin = 0;
        foreach ($listPrestasimahasiswa as $prestasi) {
            if ($prestasi->status_verifikasi === 1) {
                $totalPoin += $prestasi->poin;
            }
        }
        return $totalPoin;
    }

    private static function kesesuaianKeahlian($listKeahlian, LombaModel $lomba)
    {
        $bidangKeahlian = $lomba->bidang;
        // dd($bidangKeahlian);
        $poin = 0;
        if ($listKeahlian->isEmpty()) {
            // dd('null'); // Tidak ada keahlian mahasiswa
        } else {
            foreach ($listKeahlian as $keahlian) {
                if ($keahlian->bidang_keahlian_id === $bidangKeahlian->bidang_keahlian_id) {
                    $poin += 100;
                } else {
                    $keahlian->bidang_keahlian->kategoriBidangKeahlian->kategori_bidang_keahlian_id === $bidangKeahlian->kategoriBidangKeahlian->kategori_bidang_keahlian_id ? $poin += 65 : $poin += 10;
                }
            }
        }

        // dd($poin); // Debug isi
        return $poin;
    }

    private static function kesesuaianMinat($listMinat, lombaModel $lomba)
    {
        $bidangKeahlian = $lomba->bidang;
        // dd($bidangKeahlian);
        $poin = 0;
        if ($listMinat->isEmpty()) {
            // dd('null'); // Tidak ada keahlian mahasiswa
        } else {
            foreach ($listMinat as $minat) {
                if ($minat->bidang_keahlian_id === $bidangKeahlian->bidang_keahlian_id) {
                    $poin += 100;
                } else {
                    $minat->bidang_keahlian->kategoriBidangKeahlian->kategori_bidang_keahlian_id === $bidangKeahlian->kategoriBidangKeahlian->kategori_bidang_keahlian_id ? $poin += 65 : $poin += 10;
                }
            }
        }

        // dd($poin); // Debug isi
        return $poin;
    }

    private static function kesesuaianBidangPrestasi($ListPrestasiMahasiswa, LombaModel $lomba)
    {
        $bidangKeahlian = $lomba->bidang;
        // dd($bidangKeahlian);
        $poin = 0;
        if ($ListPrestasiMahasiswa->isEmpty()) {
            // dd('null'); // Tidak ada keahlian mahasiswa
        } else {
            foreach ($ListPrestasiMahasiswa as $prestasi) {
                if ($prestasi->status_verifikasi === 0) {
                    continue;
                }
                if ($prestasi->lomba->bidang->bidang_keahlian_id === $bidangKeahlian->bidang_keahlian_id) {
                    $poin += 100;
                } else {
                    $prestasi->lomba->bidang->kategoriBidangKeahlian->kategori_bidang_keahlian_id === $bidangKeahlian->kategoriBidangKeahlian->kategori_bidang_keahlian_id ? $poin += 65 : $poin += 10;
                }
            }
        }

        // dd($poin); // Debug isi
        return $poin;
    }
    // private static function jumlahPrestasiSebidang($ListPrestasiMahasiswa, BidangKeahlianModel $bidangKeahlian)
    // {
    //     // dd($bidangKeahlian);
    //     $jml = 0;
    //     if ($ListPrestasiMahasiswa->isEmpty()) {
    //         // dd('null'); // Tidak ada keahlian mahasiswa
    //     } else {
    //         foreach ($ListPrestasiMahasiswa as $prestasi) {
    //             if ($prestasi->status_verifikasi === 0) {
    //                 continue;
    //             }
    //             if ($prestasi->lomba->bidang->bidang_keahlian_id === $bidangKeahlian->bidang_keahlian_id) {
    //                 $jml += 1;
    //             }
    //         }
    //     }

    //     // dd($poin); // Debug isi
    //     return $jml;
    // }
    // private static function jumlahPrestasiSekategoriBidang($ListPrestasiMahasiswa, BidangKeahlianModel $bidangKeahlian)
    // {
    //     // dd($bidangKeahlian);
    //     $jml = 0;
    //     if ($ListPrestasiMahasiswa->isEmpty()) {
    //         // dd('null'); // Tidak ada keahlian mahasiswa
    //     } else {
    //         foreach ($ListPrestasiMahasiswa as $prestasi) {
    //             if ($prestasi->status_verifikasi === 0) {
    //                 continue;
    //             }
    //             if ($prestasi->lomba->bidang->kategoriBidangKeahlian->kategori_bidang_keahlian_id === $bidangKeahlian->kategoriBidangKeahlian->kategori_bidang_keahlian_id) {
    //                 $jml += 1;
    //             }
    //         }
    //     }

    //     // dd($poin); // Debug isi
    //     return $jml;
    // }

    private static function tingkatLombaPrestasi($listPrestasiMahasiswa)
    {
        $prioritas = [
            'INT' => 100, // Internasional
            'NAS' => 60,  // Nasional
            'PRO' => 30,  // Provinsi
            'KAB' => 10   // Kabupaten
        ];
        // $poin = 0;
        foreach ($prioritas as $kode => $poin) {
            foreach ($listPrestasiMahasiswa as $prestasi) {
                if (
                    $prestasi->status_verifikasi === 1 &&
                    $prestasi->lomba &&
                    $prestasi->lomba->tingkat &&
                    $prestasi->lomba->tingkat->tingkat_lomba_kode === $kode
                ) {
                    return $poin; // return saat menemukan tingkat tertinggi
                }
            }
        }
        return 0; // Jika tidak ada prestasi terverifikasi
    }




    public function python()
    {
        // return 'hello';
        // $lomba = LombaModel::all();

        // $response = Http::get('http://127.0.0.1:8000/api/data');
        // return $response->json()['message'];


        $lomba = LombaModel::find(1)->with('bidang', 'penyelenggara.kota.provinsi.negara', 'tingkat')->first();
        $mahasiswa = MahasiswaModel::all();

        // Ubah ke array agar bisa dikirim sebagai JSON
        $lombaData = $lomba->toArray();
        $mahasiswaData = $mahasiswa->toArray();

        // Kirim via POST ke FastAPI
        $response = Http::post('http://127.0.0.1:8000/api/data', [
            'lomba' => $lombaData,
            'mahasiswa' => $mahasiswaData
        ]);

        // Ambil data respons
        return $response->json(); // atau ['data'] tergantung isi respons FastAPI
    }


    public function python_coba()
    {
        $response = Http::post('http://127.0.0.1:8000/api/topsis', [
            "jumlah_anggota" => 2, // tambahkan ini
            "bobot" => [0.15, 0.1, 0.15, 0.0, 0, 0.2, 0.2, 0.1],
            "kriteria" => ["benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit", "benefit"],
            "mahasiswa" => [
                [
                    "mahasiswa_id" => 1,
                    "ipk" => 0,
                    "keahlian" => 0,
                    "jumlah_prestasi" => 0,
                    "kesesuaian_bidang_prestasi" => 0,
                    "tingkat_lomba_prestasi" => 0,
                    "poin_prestasi" => 0,
                    "minat" => 0,
                    "organisasi" => 0
                ],
                [
                    "mahasiswa_id" => 2,
                    "ipk" => 0,
                    "keahlian" => 0,
                    "jumlah_prestasi" => 0,
                    "kesesuaian_bidang_prestasi" => 0,
                    "tingkat_lomba_prestasi" => 0,
                    "poin_prestasi" => 0,
                    "minat" => 0,
                    "organisasi" => 0
                ]
            ]
        ]);

        return $response->json(); // bisa juga ->body() untuk raw response
    }

    // KRITERIA

    //IPK
    //Keahlian
    //Jumlah Prestasi
    //Tingkat lomba prestasi
    //Poin Prestasi
    //Bidang Prestasi 
    //Minat
    //Organisasi


}
