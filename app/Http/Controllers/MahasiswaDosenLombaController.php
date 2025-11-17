<?php

namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\KotaModel;
use App\Models\LombaModel;
use App\Models\MahasiswaLombaModel;
use App\Models\RekomendasiMahasiswaLombaModel;
use App\Models\TingkatLombaModel;
use App\Models\PenyelenggaraModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Str;

class MahasiswaDosenLombaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $tingkatLombaId = $request->tingkat_lomba_id;
        $bidangKeahlianId = $request->bidang_keahlian_id;
        $statusVerifikasi = $request->status_verifikasi;
        $statusWaktu = $request->status_waktu;

        $user = auth()->user();

        $baseLombaQuery = LombaModel::with(['penyelenggara', 'tingkat', 'bidang'])
            ->where(function ($q) use ($user) {
                $q->where('status_verifikasi', 1)
                    ->orWhere('user_id', $user->user_id);
            });

        $applyFilters = function ($query) use ($search, $tingkatLombaId, $bidangKeahlianId, $statusWaktu) {
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('lomba_nama', 'like', "%{$search}%")
                        ->orWhereHas('penyelenggara', function ($q2) use ($search) {
                            $q2->where('penyelenggara_nama', 'like', "%{$search}%");
                        });
                });
            }

            if ($tingkatLombaId) {
                $query->where('tingkat_lomba_id', $tingkatLombaId);
            }

            if ($statusWaktu) {
                $now = Carbon::now();

                if ($statusWaktu == 1) {
                    // Akan Datang
                    $query->where('tanggal_mulai', '>', $now);
                } elseif ($statusWaktu == 2) {
                    // Sedang Berlangsung
                    $query->where('tanggal_mulai', '<=', $now)
                        ->where('tanggal_selesai', '>=', $now);
                } elseif ($statusWaktu == 3) {
                    // Sudah Berlalu
                    $query->where('tanggal_selesai', '<', $now);
                }
            }


            if ($bidangKeahlianId) {
                $query->whereHas('bidang', function ($q) use ($bidangKeahlianId) {
                    $q->where('bidang_keahlian_id', $bidangKeahlianId);
                });
            }

            return $query;
        };

        $tingkat_lomba = TingkatLombaModel::all();
        $bidang_keahlian = BidangKeahlianModel::all();

        if ($user->hasRole('MHS')) {
            $mahasiswa_id = $user->mahasiswa->mahasiswa_id;

            $rekomendasi_ids = RekomendasiMahasiswaLombaModel::where('mahasiswa_id', $mahasiswa_id)
                ->pluck('lomba_id')
                ->unique()
                ->toArray();

            $rekomendasi_lomba = LombaModel::with(['penyelenggara', 'tingkat', 'bidang'])
                ->whereIn('lomba_id', $rekomendasi_ids);

            $rekomendasi_lomba = $applyFilters($rekomendasi_lomba)
                ->orderBy('tanggal_selesai', 'desc')
                ->paginate(4, ['*'], 'rekomendasi_page');

            $query = clone $baseLombaQuery;
            if (!empty($rekomendasi_ids)) {
                $query->whereNotIn('lomba_id', $rekomendasi_ids);
            }
            $query = $applyFilters($query);
            $lomba = $query->orderBy('tanggal_selesai', 'desc')
                ->paginate(8, ['*'], 'lomba_page');

            return view('daftar_lomba.daftar_lomba', compact(
                'lomba',
                'rekomendasi_lomba',
                'tingkat_lomba',
                'bidang_keahlian'
            ));
        } else {
            $query = $applyFilters($baseLombaQuery);
            $lomba = $query->orderBy('tanggal_selesai', 'desc')->paginate(8, ['*'], 'lomba_page');

            return view('daftar_lomba.daftar_lomba', compact('lomba', 'tingkat_lomba', 'bidang_keahlian'));
        }
    }



    public function show($id)
    {
        $ikutiLomba = false;

        if (auth()->user()->hasRole('MHS')) {
            $mahasiswaId = auth()->user()->mahasiswa->mahasiswa_id ?? null;

            if ($mahasiswaId) {
                $sudahIkut = MahasiswaLombaModel::where('mahasiswa_id', $mahasiswaId)
                    ->where('lomba_id', $id)
                    ->exists();

                $ikutiLomba = !$sudahIkut; // true jika belum ikut
            }
        }


        $lomba = LombaModel::with(['penyelenggara', 'tingkat', 'bidang'])->findOrFail($id);
        return view('daftar_lomba.show_lomba', compact('lomba', 'ikutiLomba'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tingkat = TingkatLombaModel::all();
        $bidang = BidangKeahlianModel::all();
        $penyelenggara = PenyelenggaraModel::all();
        $kota = KotaModel::all();
        return view('daftar_lomba.create_lomba')->with([
            'tingkat' => $tingkat,
            'bidang' => $bidang,
            'penyelenggara' => $penyelenggara,
            'kota' => $kota
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            // 'lomba_kode' => 'required|string|max:255',
            'lomba_nama' => 'required|string|max:255',
            'lomba_deskripsi' => 'required|string',
            'link_website' => 'required|string|max:255',
            'tingkat_lomba_id' => 'required|exists:m_tingkat_lomba,tingkat_lomba_id',
            'bidang_keahlian_id' => 'required|exists:m_bidang_keahlian,bidang_keahlian_id',
            'penyelenggara_id' => 'required',
            'jumlah_anggota => required|max:5',
            'tanggal_mulai' => 'required|date|date_format:Y-m-d',
            'tanggal_selesai' => 'required|date|date_format:Y-m-d|after_or_equal:tanggal_mulai',
            'foto_pamflet' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Tambahkan validasi khusus jika penyelenggara_id adalah 'other'
        if ($request->penyelenggara_id === 'other') {
            $rules['penyelenggara_nama'] = 'required|string|max:255';
            $rules['kota_id'] = 'required|exists:m_kota,kota_id';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $imagePath = null;
        if ($request->hasFile('foto_pamflet')) {
            $file = $request->file('foto_pamflet');

            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file'], 400);
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = storage_path('app/public/lomba/foto-pamflet');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            $file->move($destinationPath, $filename);
            $imagePath = "lomba/foto-pamflet/$filename"; // Simpan path gambar
        }

        $penyelenggara_id = $request->penyelenggara_id;
        //PENYELENGGARA
        if ($request->penyelenggara_id === 'other') {
            $penyelenggara_id = PenyelenggaraModel::create([
                'penyelenggara_nama' => $request->penyelenggara_nama,
                'kota_id' => $request->kota_id
            ])->penyelenggara_id;
        }

        $lombaNama = $request->lomba_nama;

        // 1. Buat prefix dari nama lomba (ambil huruf besar awal kata, atau substring)
        $prefix = strtoupper(Str::slug(Str::words($lombaNama, 2, ''), ''));
        $prefix = substr(preg_replace('/[^A-Z]/', '', $prefix), 0, 3); // Ambil 3 huruf kapital saja

        // 2. Tambahkan angka random untuk membuat kode unik
        do {
            $randomNumber = rand(100, 999); // 3 digit angka
            $kode = $prefix . $randomNumber; // Misal: HCK123
        } while (LombaModel::where('lomba_kode', $kode)->exists());

        try {
            $lomba = LombaModel::create([
                'lomba_kode' => $kode,
                'lomba_nama' => $request->lomba_nama,
                'lomba_deskripsi' => $request->lomba_deskripsi,
                'link_website' => $request->link_website,
                'tingkat_lomba_id' => $request->tingkat_lomba_id,
                'bidang_keahlian_id' => $request->bidang_keahlian_id,
                'penyelenggara_id' => $penyelenggara_id,
                'jumlah_anggota' => $request->jumlah_anggota,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'foto_pamflet' => $imagePath,
                'user_id' => auth()->user()->user_id,
                'status_verifikasi' => null
            ]);
        } catch (\Throwable $e) {
            if (isset($lomba)) {
                $lomba->delete();
            }
            return response()->json(['status' => false, 'message' => 'Gagal menambahkan data baru: ' . $e->getMessage()], 500);
        }


        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $mahasiswaId = optional($user->mahasiswa)->mahasiswa_id;
        $dosenId = optional($user->dosen)->dosen_id;

        $lomba = LombaModel::findOrFail($id);

        if ($lomba->user_id !== $user->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit data ini.');
        }

        $tingkat = TingkatLombaModel::all();
        $bidang = BidangKeahlianModel::all();
        $penyelenggara = PenyelenggaraModel::all();
        $kota = KotaModel::all();

        return view('daftar_lomba.edit_lomba', compact('lomba', 'tingkat', 'bidang', 'penyelenggara', 'kota'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        // $mahasiswaId = optional($user->mahasiswa)->mahasiswa_id;
        // $dosenId = optional($user->dosen)->dosen_id;

        $lomba = LombaModel::findOrFail($id);

        if ($lomba->user_id !== $user->user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki izin untuk memperbarui data ini.'
            ], 403);
        }

        $rules = [
            // 'lomba_kode' => 'required|string|max:255',
            'lomba_nama' => 'required|string|max:255',
            'lomba_deskripsi' => 'required|string',
            'link_website' => 'required|string|max:255',
            'tingkat_lomba_id' => 'required|exists:m_tingkat_lomba,tingkat_lomba_id',
            'bidang_keahlian_id' => 'required|exists:m_bidang_keahlian,bidang_keahlian_id',
            'penyelenggara_id' => 'required',
            'jumlah_anggota => required|max:5',
            'tanggal_mulai' => 'required|date|date_format:Y-m-d',
            'tanggal_selesai' => 'required|date|date_format:Y-m-d',
            'foto_pamflet' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $imagePath = $lomba->foto_pamflet;
        $oldImagePath = $lomba->foto_pamflet;
        if ($request->hasFile('foto_pamflet')) {
            $file = $request->file('foto_pamflet');
            if ($file->isValid()) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/lomba/foto-pamflet');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
                $file->move($destinationPath, $filename);
                $imagePath = "lomba/foto-pamflet/$filename";
                FileController::deleteFile($oldImagePath);
            }
        }

        $penyelenggara_id = $request->penyelenggara_id;
        //PENYELENGGARA
        if ($request->penyelenggara_id === 'other') {
            $penyelenggara_id = PenyelenggaraModel::create([
                'penyelenggara_nama' => $request->penyelenggara_nama,
                'kota_id' => $request->kota_id
            ])->penyelenggara_id;
        }


        $lombaNama = $request->lomba_nama;

        // 1. Buat prefix dari nama lomba (ambil huruf besar awal kata, atau substring)
        $prefix = strtoupper(Str::slug(Str::words($lombaNama, 2, ''), ''));
        $prefix = substr(preg_replace('/[^A-Z]/', '', $prefix), 0, 3); // Ambil 3 huruf kapital saja

        // 2. Tambahkan angka random untuk membuat kode unik
        do {
            $randomNumber = rand(100, 999); // 3 digit angka
            $kode = $prefix . $randomNumber; // Misal: HCK123
        } while (LombaModel::where('lomba_kode', $kode)->exists());

        $lomba->update([
            'lomba_kode' => $kode,
            'lomba_nama' => $request->lomba_nama,
            'lomba_deskripsi' => $request->lomba_deskripsi,
            'link_website' => $request->link_website,
            'tingkat_lomba_id' => $request->tingkat_lomba_id,
            'bidang_keahlian_id' => $request->bidang_keahlian_id,
            'penyelenggara_id' => $penyelenggara_id,
            'jumlah_anggota' => $request->jumlah_anggota,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'foto_pamflet' => $imagePath,
            'status_verifikasi' => null
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui.'
        ]);
    }


    public function confirm(LombaModel $lomba)
    {
        $tingkat = TingkatLombaModel::all();
        $bidang = BidangKeahlianModel::all();
        $penyelenggara = PenyelenggaraModel::all();
        return view('daftar_lomba.confirm_lomba')->with(['lomba' => $lomba, 'tingkat' => $tingkat, 'bidang' => $bidang, 'penyelenggara' => $penyelenggara]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $mahasiswaId = optional($user->mahasiswa)->mahasiswa_id;
        $dosenId = optional($user->dosen)->dosen_id;

        $lomba = LombaModel::findOrFail($id);

        if ($lomba->user_id !== $user->user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus data ini.'
            ], 403);
        }

        try {
            if ($lomba->foto_pamflet && file_exists(storage_path("app/public/{$lomba->foto_pamflet}"))) {
                FileController::deleteFile($lomba->foto_pamflet);
                // unlink(storage_path("app/public/{$lomba->foto_pamflet}"));
            }

            $lomba->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
