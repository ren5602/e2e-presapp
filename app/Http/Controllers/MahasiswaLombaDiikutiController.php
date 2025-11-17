<?php
namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\LombaModel;
use App\Models\MahasiswaLombaModel;
use App\Models\TingkatLombaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class MahasiswaLombaDiikutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $mahasiswa_id = auth()->user()->mahasiswa->mahasiswa_id;

        $query = MahasiswaLombaModel::where('mahasiswa_id', $mahasiswa_id)
            // ->where('status_verifikasi', 1)
            ->with([
                'lomba' => function ($q) {
                    $q->with(['tingkat', 'bidang', 'penyelenggara']);
                }
            ]);

        // Filter berdasarkan tingkat lomba
        if (request('tingkat_lomba_id')) {
            $query->whereHas('lomba', function ($q) {
                $q->where('tingkat_lomba_id', request('tingkat_lomba_id'));
            });
        }

        // Filter berdasarkan bidang keahlian
        if (request('bidang_keahlian_id')) {
            $query->whereHas('lomba', function ($q) {
                $q->where('bidang_keahlian_id', request('bidang_keahlian_id'));
            });
        }

        // Filter berdasarkan status waktu lomba
        if (request('status_waktu') !== null) {
            $query->whereHas('lomba', function ($q) {
                $now = Carbon::now();
                switch (request('status_waktu')) {
                    case '1': // Akan Datang
                        $q->where('tanggal_mulai', '>', $now);
                        break;
                    case '2': // Sedang Berlangsung
                        $q->where('tanggal_mulai', '<=', $now)
                            ->where('tanggal_selesai', '>=', $now);
                        break;
                    case '3': // Sudah Berlalu
                        $q->where('tanggal_selesai', '<', $now);
                        break;
                    default:
                        // Semua, tidak ada filter
                        break;
                }
            });
        }

        // Filter pencarian (nama lomba atau nama penyelenggara)
        if (request('search')) {
            $search = request('search');
            $query->whereHas('lomba', function ($q) use ($search) {
                $q->where('lomba_nama', 'like', '%' . $search . '%')
                    ->orWhereHas('penyelenggara', function ($qp) use ($search) {
                        $qp->where('penyelenggara_nama', 'like', '%' . $search . '%');
                    });
            });
        }

        $mahasiswaLomba = $query->paginate(6)->withQueryString(); // withQueryString agar filter tetap di URL saat paginate

        $tingkat = TingkatLombaModel::all();
        $bidang_keahlian = BidangKeahlianModel::all();

        return view('mahasiswa.lomba_diikuti.daftar_lomba_diikuti', [
            'mahasiswa_lomba' => $mahasiswaLomba,
            'tingkat_lomba' => $tingkat,
            'bidang_keahlian' => $bidang_keahlian,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($lomba_id)
    {
        try {
            MahasiswaLombaModel::create([
                'mahasiswa_id' => auth()->user()->mahasiswa->mahasiswa_id,
                'lomba_id' => $lomba_id,
                'pengaju' => 'MHS',
                'status_verifikasi_from_mhs' => true,
                'user_id' => auth()->user()->user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json(['status' => true, 'message' => 'Pengajuan berhasil dibuat']);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'message' => 'Pengajuan berhasil dibuat']);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(MahasiswaLombaModel $mahasiswaLomba)
    {
        // dd($mahasiswaLomba->lomba);
        return view('mahasiswa.lomba_diikuti.show_lomba_diikuti')->with(['lomba' => $mahasiswaLomba->lomba]);
    }

    public function confirm_ikuti(LombaModel $lomba)
    {
        return view('daftar_lomba.confirm_ikuti_lomba')->with(['lomba' => $lomba]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function verifikasi_from_mhs(MahasiswaLombaModel $mahasiswaLomba)
    {
        return view('mahasiswa.lomba_diikuti.verifikasi_ikuti_lomba')->with(['mahasiswa_lomba' => $mahasiswaLomba]);
    }
    public function update_verifikasi_from_mhs(Request $request, MahasiswaLombaModel $mahasiswaLomba)
    {
        if ($request->ajax()) {
            $rules = [
                'status_verifikasi' => 'required',
                'message' => 'nullable',
            ];
            $messages = [
                'status_verifikasi.required' => 'Status verifikasi harus diisi.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            try {
                $mahasiswaLomba->update([
                    'status_verifikasi_from_mhs' => $request->status_verifikasi,
                    'message' => $request->message,
                ]);
                return response()->json(['status' => true, 'message' => 'Status verifikasi berhasil diubah.']);
            } catch (\Throwable $th) {
                return response()->json(['status' => false, 'message' => $th->getMessage()]);
            }

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MahasiswaLombaModel $mahasiswaLombaModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MahasiswaLombaModel $mahasiswaLombaModel)
    {
        //
    }
}
