<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaLombaModel;
use App\Models\MahasiswaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;

class MahasiswaTerdaftarLombaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lomba = LombaModel::where('status_verifikasi', 1)
            ->whereHas('mahasiswa_terdaftar') // nama relasi di model
            ->get();

        return view('admin.mahasiswa_lomba.daftar_mahasiswa_lomba')->with('lomba', $lomba);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = MahasiswaLombaModel::with([
                'mahasiswa',
                'lomba'
            ]);

            if ($request->lomba_id) {
                $query->where('lomba_id', $request->lomba_id);
            }

            $bidangKeahlian = $query->get();

            return DataTables::of($bidangKeahlian)
                ->addIndexColumn()
                ->addColumn('nama_lomba', function ($row) {
                    return $row->lomba->lomba_nama;
                })
                ->addColumn('mahasiswa_terdaftar', function ($row) {
                    return $row->mahasiswa->nama;
                })
                ->addColumn('nim', function ($row) {
                    return $row->mahasiswa->nim;
                })
                ->addColumn('status_verifikasi', function ($row) {
                    if ($row->status_verifikasi === 1) {
                        return '<button onclick="modalAction(\'' . url('/mahasiswa_lomba/' . $row->mahasiswa_lomba_id . '/edit-verifikasi') . '\')" class="badge bg-success" style="color: white; border: none; outline: none; box-shadow: none;">Terverifikasi</button>';
                    } else if ($row->status_verifikasi === 0) {
                        return '<button onclick="modalAction(\'' . url('/mahasiswa_lomba/' . $row->mahasiswa_lomba_id . '/edit-verifikasi') . '\')" class="badge bg-danger" style="color: white; border: none; outline: none; box-shadow: none;">Ditolak</button>';
                    } else if ($row->status_verifikasi === null) {
                        return '<button onclick="modalAction(\'' . url('/mahasiswa_lomba/' . $row->mahasiswa_lomba_id . '/edit-verifikasi') . '\')" class="badge bg-warning" style="color: white; border: none; outline: none; box-shadow: none;">Menunggu</button>';
                    }
                })
                ->addColumn('status_verifikasi_from_mhs', function ($row) {
                    if ($row->status_verifikasi_from_mhs === 1) {
                        return '<div class="badge bg-success" style="color: white; border: none; outline: none; box-shadow: none;">Diterima</div>';
                    } else if ($row->status_verifikasi_from_mhs === 0) {
                        return '<div class="badge bg-danger" style="color: white; border: none; outline: none; box-shadow: none;">Ditolak</div>';
                    } else if ($row->status_verifikasi_from_mhs === null) {
                        return '<div class="badge bg-warning" style="color: white; border: none; outline: none; box-shadow: none;">Menunggu</div>';
                    }
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/mahasiswa_lomba/' . $row->mahasiswa_lomba_id . '/show') . '\')" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Detail</button> ';
                    // $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa_lomba/' . $row->mahasiswa_lomba_id . '/edit') . '\')" class="btn btn-sm btn-warning mt-1 mb-1" title="Edit"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa_lomba/' . $row->mahasiswa_lomba_id . '/confirm-delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['status_verifikasi', 'aksi', 'status_verifikasi_from_mhs'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lomba = LombaModel::where('tanggal_selesai', '>', Carbon::now())
            ->where('status_verifikasi', 1)
            ->get();
        $mahasiswa = MahasiswaModel::all();

        return view('admin.mahasiswa_lomba.create_mahasiswa_lomba')
            ->with([
                'lomba' => $lomba,
                'mahasiswa' => $mahasiswa
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'lomba_id' => 'required',
                'mahasiswa_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = MahasiswaLombaModel::where('mahasiswa_id', $value)
                            ->where('lomba_id', $request->lomba_id)
                            ->exists();
                        if ($exists) {
                            $fail('Mahasiswa sudah terdaftar dalam lomba ini');
                        }
                    },
                ],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => implode(' ', $validator->errors()->all()),
                ]);
            }
            try {
                MahasiswaLombaModel::create([
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'lomba_id' => $request->lomba_id,
                    'status_verifikasi' => true,
                    'status_verifikasi_from_mhs' => true,
                    'pengaju' => 'ADM',
                    'user_id' => auth()->user()->user_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Data Berhasil Disimpan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MahasiswaLombaModel $mahasiswaLomba)
    {
        return view('admin.mahasiswa_lomba.show_mahasiswa_lomba')->with(['mahasiswa_lomba' => $mahasiswaLomba]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_verifikasi(MahasiswaLombaModel $mahasiswaLomba)
    {
        return view('admin.mahasiswa_lomba.verifikasi_mahasiswa_lomba')->with(['mahasiswa_lomba' => $mahasiswaLomba]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_verifikasi(Request $request, MahasiswaLombaModel $mahasiswaLomba)
    {
        try {
            $mahasiswaLomba->update([
                'status_verifikasi' => $request->status_verifikasi
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status Verifikasi Berhasil Diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function confirm(MahasiswaLombaModel $mahasiswaLomba)
    {
        return view('admin.mahasiswa_lomba.confirm_delete_mahasiswa_lomba')->with(['mahasiswa_lomba' => $mahasiswaLomba]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MahasiswaLombaModel $mahasiswaLomba)
    {
        try {
            $mahasiswaLomba->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
