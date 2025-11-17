<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\PrestasiModel;
use App\Models\TingkatLombaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdminPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tingkat_lomba = TingkatLombaModel::all();
        return view('admin.prestasi.daftar_prestasi')->with([
            'tingkat_lomba' => $tingkat_lomba
        ]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $prestasi = PrestasiModel::with('mahasiswa', 'dosen', 'lomba.tingkat');

            if ($request->tingkat_lomba_id) {
                $prestasi->whereHas('lomba.tingkat', function ($query) use ($request) {
                    $query->where('tingkat_lomba_id', $request->tingkat_lomba_id);
                });
            }

            if ($request->status_verifikasi) {
                // dd($request->status_verifikasi);
                if ($request->status_verifikasi == 2) {
                    $prestasi->whereNull('status_verifikasi');
                } else if ($request->status_verifikasi == 3) {
                    $prestasi->where('status_verifikasi', 0);
                } else {
                    $prestasi->where('status_verifikasi', $request->status_verifikasi);
                }
            }

            $prestasi = $prestasi->get();

            return DataTables::of($prestasi)
                ->addIndexColumn() // untuk DT_RowIndex
                ->addColumn('nim', function ($row) {
                    return $row->mahasiswa->nim;
                })

                // ->addColumn('info', function ($row) {
                //     $image = $row->foto_profile ? asset('storage/' . $row->foto_profile) : asset('assets/images/user.png');
                //     // $image = asset('assets/images/user.png');

                //     return '
                //         <div class="d-flex align-items-center text-start">
                //             <img 
                //                 src="' . $image . '" 
                //                 alt="User image" 
                //                 class="rounded-circle" 
                //                 style="width: 40px; height: 40px; object-fit: cover; margin-right: 15px;"
                //             >
                //             <div class="d-flex flex-column justify-content-center">
                //                 <div style="font-weight: bold;">' . $row->nama . '</div>
                //                 <div class="text-muted"><i class="fa fa-envelope me-1"></i> ' . $row->email . '</div>
                //                 <div class="text-muted"><i class="fa fa-phone me-1"></i> ' . $row->no_tlp . '</div>
                //             </div>
                //         </div>
                //     ';
                // })

                ->addColumn('mahasiswa', function ($row) {
                    return $row->mahasiswa->nama ?? '-';
                })
                ->addColumn('prestasi', function ($row) {
                    return collect(explode(' ', $row->prestasi_nama))->take(4)->implode(' ') . '...';
                })
                ->addColumn('lomba', function ($row) {
                    return collect(explode(' ', $row->lomba->lomba_nama))->take(4)->implode(' ') . '...';
                })
                ->addColumn('juara', function ($row) {
                    return $row->nama_juara ?? '-';
                })
                ->addColumn('tingkat', function ($row) {
                    return $row->lomba->tingkat->tingkat_lomba_nama ?? '-';
                })
                ->addColumn('poin', function ($row) {
                    return $row->poin ?? '-';
                })
                ->addColumn('status_verifikasi', function ($row) {
                    if ($row->status_verifikasi === 1) {
                        return '<button onclick="modalAction(\'' . url('/prestasi/' . $row->prestasi_id . '/edit-verifikasi') . '\')" class="badge bg-success" style="color: white; border: none; outline: none; box-shadow: none;">Terverifikasi</button>';
                    } else if ($row->status_verifikasi === 0) {
                        return '<button onclick="modalAction(\'' . url('/prestasi/' . $row->prestasi_id . '/edit-verifikasi') . '\')" class="badge bg-danger" style="color: white; border: none; outline: none; box-shadow: none;">Ditolak</button>';
                    } else if ($row->status_verifikasi === null) {
                        return '<button onclick="modalAction(\'' . url('/prestasi/' . $row->prestasi_id . '/edit-verifikasi') . '\')" class="badge bg-warning" style="color: white; border: none; outline: none; box-shadow: none;">Menunggu</button>';
                    }
                })

                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/prestasi/' . $row->prestasi_id . '/show') . '\')" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/prestasi/' . $row->prestasi_id . '/edit') . '\')" class="btn btn-sm btn-warning mt-1 mb-1" title="Edit"><i class="fa fa-pen"></i> Edit</button> ';
                    // $btn .= '<button onclick="modalAction(\'' . url('/prestasi/' . $row->prestasi_id . '/confirm-delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button> ';
                    // return '<div class="">' . $btn . '</div>';
                    return $btn;
                })
                ->rawColumns(['info', 'aksi', 'status_verifikasi']) // agar tombol HTML tidak di-escape
                ->make(true);
        }
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
        $mahasiswa = MahasiswaModel::all();
        return view('admin.prestasi.create_prestasi')->with([
            'lomba' => $lomba,
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (request()->ajax() || request()->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required',
                'dosen_id' => 'required',
                'lomba_id' => 'required',
                'prestasi_nama' => 'required',
                'nama_juara' => 'nullable',
                'tanggal_perolehan' => 'required|date',
                'file_sertifikat' => 'required|mimes:jpg,jpeg,png',
                'file_bukti_foto' => 'required|mimes:jpg,jpeg,png',
                'file_surat_tugas' => 'required|mimes:jpg,jpeg,png',
                'file_surat_undangan' => 'required|mimes:jpg,jpeg,png',
                'file_proposal' => 'required|mimes:pdf',
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

            $nim_mahasiswa = MahasiswaModel::findOrFail($request->mahasiswa_id)->nim;

            // dd($request->file());
            $imagePaths['file_sertifikat'] = FileController::saveFile($request, 'sertifikat', $nim_mahasiswa, 'file_sertifikat');
            $imagePaths['file_bukti_foto'] = FileController::saveFile($request, 'bukti_foto', $nim_mahasiswa, 'file_bukti_foto');
            $imagePaths['file_surat_tugas'] = FileController::saveFile($request, 'surat_tugas', $nim_mahasiswa, 'file_surat_tugas');
            $imagePaths['file_surat_undangan'] = FileController::saveFile($request, 'surat_undangan', $nim_mahasiswa, 'file_surat_undangan');
            $imagePaths['file_proposal'] = FileController::saveFile($request, 'proposal', $nim_mahasiswa, 'file_proposal');

            // dd($imagePath);

            try {
                $prestasi = PrestasiModel::create([
                    'mahasiswa_id' => $request->mahasiswa_id,
                    // 'dosen_id' => '2222',
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
                    'status_verifikasi' => 1,
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
        return view('admin.prestasi.show_prestasi')->with(['prestasi' => $prestasi]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrestasiModel $prestasi)
    {
        $lomba = LombaModel::where('tanggal_selesai', '<', Carbon::now())
            ->where('status_verifikasi', 1)
            ->get();

        $dosen = DosenModel::all();
        $mahasiswa = MahasiswaModel::all();

        return view('admin.prestasi.edit_prestasi')->with([
            'prestasi' => $prestasi,
            'lomba' => $lomba,
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrestasiModel $prestasi)
    {

        $rules = [
            'mahasiswa_id' => 'required',
            'dosen_id' => 'required',
            'lomba_id' => 'required',
            'prestasi_nama' => 'required',
            'juara' => 'required',
            'nama_juara' => 'nullable',
            'tanggal_perolehan' => 'required|date',
            'file_sertifikat' => 'nullable|mimes:jpg,jpeg,png',
            'file_bukti_foto' => 'nullable|mimes:jpg,jpeg,png',
            'file_surat_tugas' => 'nullable|mimes:jpg,jpeg,png',
            'file_surat_undangan' => 'nullable|mimes:jpg,jpeg,png',
            'file_proposal' => 'nullable|mimes:pdf',
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


        $prestasi->juara = $request->juara;
        $prestasi->mahasiswa_id = $request->mahasiswa_id;
        $prestasi->dosen_id = $request->dosen_id;
        $prestasi->lomba_id = $request->lomba_id;
        $prestasi->prestasi_nama = $request->prestasi_nama;
        $prestasi->tanggal_perolehan = $request->tanggal_perolehan;


        $nim_mahasiswa = MahasiswaModel::findOrFail($request->mahasiswa_id)->nim;

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

        $prestasi->status_verifikasi = 1;
        $prestasi->updated_at = Carbon::now();
        $prestasi->save();

        $prestasi->poin = PoinPrestasiController::hitungPoin($prestasi);

        $prestasi->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    public function edit_verifikasi(PrestasiModel $prestasi)
    {
        return view('admin.prestasi.edit_verifikasi_prestasi')->with(['prestasi' => $prestasi]);
    }

    public function update_verifikasi(Request $request, PrestasiModel $prestasi)
    {
        // dd($request);
        try {
            $prestasi->status_verifikasi = $request->status_verifikasi;
            $prestasi->message = $request->message;


            $prestasi->save();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate.'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }

    }

    public function confirmDelete(PrestasiModel $prestasi)
    {
        return view('admin.prestasi.confirm_delete_prestasi')->with(['prestasi' => $prestasi]);
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
