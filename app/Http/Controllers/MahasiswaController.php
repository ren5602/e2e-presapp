<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use App\Models\LevelModel;
use App\Models\MahasiswaModel;
use App\Models\PrestasiModel;
use App\Models\ProdiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = KelasModel::all();
        $prodi = ProdiModel::all();
        return view('admin.mahasiswa.daftar_mahasiswa')->with([
            'kelas' => $kelas,
            'prodi' => $prodi
        ]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $mahasiswas = MahasiswaModel::with('kelas', 'prestasi');

            if ($request->prodi_id) {
                $mahasiswas->whereHas('kelas', function ($query) use ($request) {
                    $query->where('prodi_id', $request->prodi_id);
                });
            }

            if ($request->kelas_id) {
                $mahasiswas->where('kelas_id', $request->kelas_id);
            }

            $mahasiswas = $mahasiswas->get();


            return DataTables::of($mahasiswas)
                ->addIndexColumn() // untuk DT_RowIndex
                ->addColumn('nim', function ($row) {
                    return $row->nim;
                })
                ->addColumn('info', function ($row) {
                    $image = $row->foto_profile ? asset('storage/' . $row->foto_profile) : asset('assets/images/user.png');
                    // $image = asset('assets/images/user.png');
    
                    return '
                        <div class="d-flex align-items-center text-start">
                            <img 
                                src="' . $image . '" 
                                alt="User image" 
                                class="rounded-circle" 
                                style="width: 40px; height: 40px; object-fit: cover; margin-right: 15px;"
                            >
                            <div class="d-flex flex-column justify-content-center">
                                <div style="font-weight: bold;">' . $row->nama . '</div>
                                <div class="text-muted"><i class="fa fa-envelope me-1"></i> ' . $row->email . '</div>
                                <div class="text-muted"><i class="fa fa-phone me-1"></i> ' . $row->no_tlp . '</div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('kelas', function ($row) {
                    return $row->kelas->kelas_nama ?? '-';
                })
                ->addColumn('alamat', function ($row) {
                    return collect(explode(' ', $row->alamat))->take(5)->implode(' ') . '...';
                })
                ->addColumn('poin_prestasi', function ($row) {
                    return $row->prestasi->sum('poin');
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/mahasiswa/' . $row->mahasiswa_id . '/show') . '\')" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $row->mahasiswa_id . '/edit') . '\')" class="btn btn-sm btn-warning mt-1 mb-1" title="Edit"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $row->mahasiswa_id . '/confirm-delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button> ';
                    // return '<div class="">' . $btn . '</div>';
                    return $btn;
                })
                ->rawColumns(['info', 'aksi']) // agar tombol HTML tidak di-escape
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = KelasModel::select('kelas_id', 'kelas_nama', 'prodi_id')->get();
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama')->get();
        return view('admin.mahasiswa.create_mahasiswa')->with(['kelas' => $kelas, 'prodi' => $prodi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->file('foto_profile'));

        // dd($request);
        if ($request->ajax() || $request->wantsJson()) {
            // dd($request);
            // dd($request->file('foto_profile'));

            $rules = [
                'username' => 'required|unique:m_user,username',
                'nama' => 'required|max:100',
                'email' => 'required|email|unique:m_mahasiswa,email',
                'no_tlp' => 'nullable|max:20',
                'nim' => 'required|unique:m_mahasiswa,nim',
                'prodi_id' => 'required',
                'kelas_id' => 'required',
                'alamat' => 'nullable',
                'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'ipk' => 'required|numeric',
                'password' => 'nullable|min:6|max:20'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
            if ($request->hasFile('foto_profile')) {
                // return response()->json(['error' => 'No file uploaded'], 400);
                $file = $request->file('foto_profile');

                if (!$file->isValid()) {
                    return response()->json(['error' => 'Invalid file'], 400);
                }

                // Nama file unik
                $filename = time() . '_' . $file->getClientOriginalName();

                // Pastikan folder penyimpanan ada
                $destinationPath = storage_path('app/public/mahasiswa/profile-pictures');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }

                // Hapus file lama jika ada
                $oldImage = $mahasiswa->foto_profile ?? null; // Ambil path file lama dari database

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Pindahkan file
                $file->move($destinationPath, $filename);

                $imagePath = "mahasiswa/profile-pictures/$filename"; // Simpan path gambar
            } else {
                $imagePath = null;
                // return  'dijalankan';
            }
            // dd($imagePath);

            try {
            $data_user = [
                'username' => $request->username,
                'password' => $request->password,
                'level_id' => LevelModel::where('level_kode', 'MHS')->first()->level_id
            ];
            $userId = UserModel::create($data_user)->user_id;

            $data_mahasiswa = [
                'user_id' => $userId,
                'prodi_id' => $request->prodi_id,
                'kelas_id' => $request->kelas_id,
                'nim' => $request->nim,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_tlp' => $request->no_tlp,
                'alamat' => $request->alamat,
                'tahun_angkatan' => $request->tahun_angkatan,
                'ipk' => $request->ipk,
                'foto_profile' => $imagePath
            ];

            $mahasiswa = MahasiswaModel::create($data_mahasiswa);
            return response()->json(['status' => true, 'message' => 'Data Mahasiswa berhasil ditambahkan']);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => 'Terjadi kesalahan pada server', 'error' => $e]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MahasiswaModel $mahasiswa)
    {
        return view('admin.mahasiswa.show_mahasiswa')->with(['mahasiswa' => $mahasiswa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MahasiswaModel $mahasiswa)
    {
        $kelas = KelasModel::select('kelas_id', 'kelas_nama', 'prodi_id')->get();
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama')->get();
        return view('admin.mahasiswa.edit_mahasiswa')->with(['kelas' => $kelas, 'prodi' => $prodi, 'mahasiswa' => $mahasiswa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MahasiswaModel $mahasiswa)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username' => 'required|max:20|unique:m_user,username,' . $mahasiswa->user->user_id . ',user_id',
                'nama' => 'required|max:100',
                'email' => 'required|email|unique:m_mahasiswa,email,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
                'no_tlp' => 'nullable|max:20',
                'nim' => 'required|unique:m_mahasiswa,nim,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
                'prodi_id' => 'required',
                'kelas_id' => 'required',
                'alamat' => 'nullable',
                'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'ipk' => 'required|numeric',
                'password' => 'nullable|min:6|max:20'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
            if ($request->hasFile('foto_profile')) {
                // return response()->json(['error' => 'No file uploaded'], 400);
                $file = $request->file('foto_profile');

                if (!$file->isValid()) {
                    return response()->json(['error' => 'Invalid file'], 400);
                }

                // Nama file unik
                $filename = time() . '_' . $file->getClientOriginalName();

                // Pastikan folder penyimpanan ada
                $destinationPath = storage_path('app/public/mahasiswa/profile-pictures');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }

                // Hapus file lama jika ada
                $oldImage = $mahasiswa->foto_profile ?? null; // Ambil path file lama dari database

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                // Pindahkan file
                $file->move($destinationPath, $filename);

                $imagePath = "mahasiswa/profile-pictures/$filename"; // Simpan path gambar
            } else {
                $imagePath = null;
                // return  'dijalankan';
            }

            // return 'aaaa'.$imagePath;

            $check = UserModel::find($mahasiswa->user->user_id);
            if ($check) {
                if (!$request->filled('password')) {
                    $data_user = [
                        'username' => $request->username,
                    ];
                } else {
                    $data_user = [
                        'username' => $request->username,
                        'password' => $request->password
                    ];
                }
                $check->update($data_user);

                if ($request->input('remove_picture') == "1") {
                    // Hapus gambar lama jika ada
                    if ($mahasiswa->foto_profile) {
                        $oldImage = $mahasiswa->foto_profile; // Ambil path file lama dari database
                        if ($oldImage) {
                            Storage::disk('public')->delete($oldImage);
                        }
                    }
                    $imagePath = null; // Set kolom di database jadi null
                }

                $data_mahasiswa = [
                    'nim' => $request->nim,
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_tlp' => $request->no_tlp,
                    'alamat' => $request->alamat,
                    'tahun_angkatan' => $request->tahun_angkatan,
                    'kelas_id' => $request->kelas_id,
                    'ipk' => $request->ipk,
                    'foto_profile' => $imagePath
                ];
                $mahasiswa->update($data_mahasiswa);
                return response()->json(['status' => true, 'message' => 'Data berhasil diupdate']);
            } else {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
            }
        }
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function confirmDelete(MahasiswaModel $mahasiswa)
    {
        $kelas = KelasModel::select('kelas_id', 'kelas_nama');
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama');
        return view('admin.mahasiswa.confirm_delete_mahasiswa')->with(['kelas' => $kelas, 'prodi' => $prodi, 'mahasiswa' => $mahasiswa]);
    }

    public function destroy(MahasiswaModel $mahasiswa)
    {
        // return $mahasiswa;
        if ($mahasiswa) {
            try {
                $oldImage = $mahasiswa->foto_profile; // Ambil path file lama dari database
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                $userId = $mahasiswa->user_id;
                $mahasiswa->delete();
                UserModel::where('user_id', $userId)->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                ]);
            }
        }
    }

    
    public function import()
    {
        return view('admin.mahasiswa.import_mahasiswa');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_mahasiswa' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal saat upload file.',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_mahasiswa');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $inserted = 0;
            $levelId = \App\Models\LevelModel::where('level_kode', 'MHS')->value('level_id');

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris == 1) continue; // Header

                    try {
                        $user = UserModel::create([
                            'username' => trim($value['A']),
                            'password' => bcrypt(trim($value['B'])),
                            'level_id' => $levelId,
                            'created_at' => now()
                        ]);

                        MahasiswaModel::create([
                            'user_id' => $user->user_id,
                            'nim' => trim($value['C']),
                            'nama' => trim($value['D']),
                            'email' => trim($value['E']),
                            'no_tlp' => trim($value['F']),
                            'alamat' => trim($value['G']),
                            'tahun_angkatan' => trim($value['H']),
                            'kelas_id' => trim($value['I']),
                            'ipk' => isset($value['J']) ? (float)trim($value['J']) : null,
                            'foto_profile' => null
                        ]);

                        $inserted++;
                    } catch (\Exception $e) {
                        Log::error("Import gagal di baris {$baris}: " . $e->getMessage());
                        continue; // Skip error, lanjut baris berikutnya
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => "Import selesai. Total data disimpan: $inserted"
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data pada file.'
            ]);
        }

        return redirect('/');
    }

    public function export_excel()
    {
        $mahasiswa = MahasiswaModel::with(['user', 'kelas'])->orderBy('mahasiswa_id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'NIM');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'No Telepon');
        $sheet->setCellValue('G1', 'Alamat');
        $sheet->setCellValue('H1', 'Tahun Angkatan');
        $sheet->setCellValue('I1', 'Kelas');
        $sheet->setCellValue('J1', 'IPK');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $baris = 2;
        $no = 1;
        foreach ($mahasiswa as $m) {
            $sheet->setCellValue("A$baris", $no++);
            $sheet->setCellValue("B$baris", $m->user->username ?? '-');
            $sheet->setCellValue("C$baris", $m->nim);
            $sheet->setCellValue("D$baris", $m->nama);
            $sheet->setCellValue("E$baris", $m->email);
            $sheet->setCellValue("F$baris", $m->no_tlp);
            $sheet->setCellValue("G$baris", $m->alamat);
            $sheet->setCellValue("H$baris", $m->tahun_angkatan);
            $sheet->setCellValue("I$baris", $m->kelas->kelas_nama ?? '-');
            $sheet->setCellValue("J$baris", $m->ipk ?? '-');
            $baris++;
        }

        // Auto width
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Mahasiswa_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Bersihkan output buffer
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
