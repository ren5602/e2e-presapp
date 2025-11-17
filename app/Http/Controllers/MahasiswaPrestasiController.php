<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\LombaModel;
use App\Models\PrestasiModel;
use App\Models\TingkatLombaModel;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Storage;
use Validator;

class MahasiswaPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswaId = auth()->user()->mahasiswa->mahasiswa_id;
        $search = request('search');

        $tingkatLombaId = request('tingkat_lomba_id');
        $statusVerifikasiInput = request('status_verifikasi');

        $prestasi = PrestasiModel::where('mahasiswa_id', $mahasiswaId)
            ->when($search, function ($query, $search) {
                $query->where('prestasi_nama', 'like', "%{$search}%")
                    ->orWhereHas('lomba', function ($q) use ($search) {
                        $q->where('lomba_nama', 'like', "%{$search}%")
                            ->orWhereHas('penyelenggara', function ($q2) use ($search) {
                                $q2->where('penyelenggara_nama', 'like', "%{$search}%");
                            });
                    });
            })
            ->when(request('tingkat_lomba_id'), function ($query, $tingkatLombaId) {
                $query->whereHas('lomba', function ($q) use ($tingkatLombaId) {
                    $q->where('tingkat_lomba_id', $tingkatLombaId);
                });
            })
            ->when($statusVerifikasiInput !== null && $statusVerifikasiInput !== '', function ($query) use ($statusVerifikasiInput) {
                if ($statusVerifikasiInput === '2') {
                    $query->whereNull('status_verifikasi');
                } else {
                    $query->where('status_verifikasi', $statusVerifikasiInput);
                }
            })
            ->with(['lomba.tingkat', 'lomba.penyelenggara'])
            ->orderByDesc('updated_at')
            ->paginate(6)
            ->appends([
                'search' => $search,
                'tingkat_lomba_id' => request('tingkat_lomba_id'),
                'status_verifikasi' => $statusVerifikasiInput,
            ]);

        $tingkat_lomba = TingkatLombaModel::all();
        return view('mahasiswa.prestasi.daftar_prestasi')->with([
            'prestasi' => $prestasi,
            'tingkat_lomba' => $tingkat_lomba
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lomba = LombaModel::where('tanggal_selesai', '<', Carbon::now())
            ->where('status_verifikasi', 1)
            ->get();
        $dosen = DosenModel::all();
        return view('mahasiswa.prestasi.create_prestasi')->with([
            'lomba' => $lomba,
            'dosen' => $dosen
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (request()->ajax() || request()->wantsJson()) {
            $rules = [
                'dosen_id' => 'required',
                'lomba_id' => 'required',
                'prestasi_nama' => 'required',
                'nama_juara' => 'nullable',
                'tanggal_perolehan' => 'required|date',
                'file_sertifikat' => 'required|mimes:jpg,jpeg,png|max:2048',
                'file_bukti_foto' => 'required|mimes:jpg,jpeg,png|max:2048',
                'file_surat_tugas' => 'required|mimes:jpg,jpeg,png|max:2048',
                'file_surat_undangan' => 'required|mimes:jpg,jpeg,png|max:2048',
                'file_proposal' => 'mimes:pdf|max:4096',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ]);
            }

            if ($request->juara == 4) {
                // Validasi: nama_juara wajib jika juara == 4
                $validator = Validator::make($request->all(), [
                    'juara' => 'required',
                    'nama_juara' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal.',
                        'msgField' => $validator->errors()
                    ]);
                }

                $nama_juara = $request->nama_juara;
            } else {
                $nama_juara = 'Juara ' . $request->juara;
            }
            $mhs = Auth::user()->mahasiswa;
            $nim_mahasiswa = $mhs->nim;

            // dd($request->file());
            $imagePaths['file_sertifikat'] = FileController::saveFile($request, 'sertifikat', $nim_mahasiswa, 'file_sertifikat');
            $imagePaths['file_bukti_foto'] = FileController::saveFile($request, 'bukti_foto', $nim_mahasiswa, 'file_bukti_foto');
            $imagePaths['file_surat_tugas'] = FileController::saveFile($request, 'surat_tugas', $nim_mahasiswa, 'file_surat_tugas');
            $imagePaths['file_surat_undangan'] = FileController::saveFile($request, 'surat_undangan', $nim_mahasiswa, 'file_surat_undangan');
            $imagePaths['file_proposal'] = FileController::saveFile($request, 'proposal', $nim_mahasiswa, 'file_proposal');

            // dd($imagePath);

            try {
                $prestasi = PrestasiModel::create([
                    'mahasiswa_id' => $mhs->mahasiswa_id,
                    'dosen_id' => $request->dosen_id,
                    'lomba_id' => $request->lomba_id,
                    'prestasi_nama' => $request->prestasi_nama,
                    'juara' => $request->juara,
                    'nama_juara' => $nama_juara,
                    'tanggal_perolehan' => $request->tanggal_perolehan,
                    'file_sertifikat' => $imagePaths['file_sertifikat'],
                    'file_bukti_foto' => $imagePaths['file_bukti_foto'],
                    'file_surat_tugas' => $imagePaths['file_surat_tugas'],
                    'file_surat_undangan' => $imagePaths['file_surat_undangan'],
                    'file_proposal' => $imagePaths['file_proposal'],
                    'poin' => 0,
                    'status_verifikasi' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $poin = PoinPrestasiController::hitungPoin($prestasi);
                $prestasi->poin = $poin;
                $prestasi->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            } catch (\Throwable $th) {
                foreach ($imagePaths as $imagePath) {
                    if ($imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PrestasiModel $prestasi)
    {
        if ($prestasi->mahasiswa->user_id !== auth()->user()->user_id) {
            abort(403, 'Anda tidak diizinkan mengakses prestasi ini.');
        }

        return view('mahasiswa.prestasi.show_prestasi', compact('prestasi'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrestasiModel $prestasi)
    {
        if ($prestasi->mahasiswa->user_id !== auth()->user()->user_id) {
            abort(403, 'Anda tidak diizinkan mengakses prestasi ini.');
        }
        $lomba = LombaModel::where('tanggal_selesai', '<', Carbon::now())
            ->where('status_verifikasi', 1)
            ->get();;
        $dosen = DosenModel::all();
        return view('mahasiswa.prestasi.edit_prestasi')->with([
            'prestasi' => $prestasi,
            'lomba' => $lomba,
            'dosen' => $dosen
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrestasiModel $prestasi)
    {
        // dd($request);

        $rules = [
            // 'mahasiswa_id' => 'required',
            'dosen_id' => 'required',
            'lomba_id' => 'required',
            'prestasi_nama' => 'required',
            'juara' => 'required',
            'nama_juara' => 'nullable',
            'tanggal_perolehan' => 'required|date',
            'file_sertifikat' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file_bukti_foto' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file_surat_tugas' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file_surat_undangan' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file_proposal' => 'nullable|mimes:pdf|max:4096',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        if ($request->juara == 4) {
            // Validasi: nama_juara wajib jika juara == 4
            $validator = Validator::make($request->all(), [
                'juara' => 'required',
                'nama_juara' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $prestasi->nama_juara = $request->nama_juara;
        } else {
            $prestasi->nama_juara = 'Juara ' . $request->juara;
        }

        $mhs = Auth::user()->mahasiswa;

        $prestasi->juara = $request->juara;
        $prestasi->mahasiswa_id = $mhs->mahasiswa_id;
        $prestasi->dosen_id = $request->dosen_id;
        $prestasi->lomba_id = $request->lomba_id;
        $prestasi->prestasi_nama = $request->prestasi_nama;
        $prestasi->tanggal_perolehan = $request->tanggal_perolehan;


        $nim_mahasiswa = $mhs->nim;

        if ($request->hasFile('file_sertifikat')) {
            FileController::deleteFile($prestasi->file_sertifikat);
            $prestasi->file_sertifikat = FileController::saveFile($request, 'sertifikat', $nim_mahasiswa, 'file_sertifikat');
        }

        if ($request->hasFile('file_bukti_foto')) {
            FileController::deleteFile($prestasi->file_bukti_foto);
            $prestasi->file_bukti_foto = FileController::saveFile($request, 'bukti_foto', $nim_mahasiswa, 'file_bukti_foto');
        }

        if ($request->hasFile('file_surat_tugas')) {
            FileController::deleteFile($prestasi->file_surat_tugas);
            $prestasi->file_surat_tugas = FileController::saveFile($request, 'surat_tugas', $nim_mahasiswa, 'file_surat_tugas');
        }

        if ($request->hasFile('file_surat_undangan')) {
            FileController::deleteFile($prestasi->file_surat_undangan);
            $prestasi->file_surat_undangan = FileController::saveFile($request, 'surat_undangan', $nim_mahasiswa, 'file_surat_undangan');
        }

        if ($request->hasFile('file_proposal')) {
            FileController::deleteFile($prestasi->file_proposal);
            $prestasi->file_proposal = FileController::saveFile($request, 'proposal', $nim_mahasiswa, 'file_proposal');
        }

        $prestasi->status_verifikasi = null;
        $prestasi->updated_at = Carbon::now();
        $prestasi->save();

        $prestasi->poin = PoinPrestasiController::hitungPoin($prestasi);

        $prestasi->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function confirm(PrestasiModel $prestasi)
    {
        return view('mahasiswa.prestasi.confirm_delete_prestasi', compact('prestasi'));
    }
    public function destroy(PrestasiModel $prestasi)
    {
        try {
            FileController::deleteFile($prestasi->file_sertifikat);
            FileController::deleteFile($prestasi->file_bukti_foto);
            FileController::deleteFile($prestasi->file_surat_tugas);
            FileController::deleteFile($prestasi->file_surat_undangan);
            FileController::deleteFile($prestasi->file_proposal);

            $prestasi->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
            ]);
        }
    }

}
