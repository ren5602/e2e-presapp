<?php

namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\KeahlianMahasiswaModel;
use App\Models\KelasModel;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaOrganisasiModel;
use App\Models\MinatMahasiswaModel;
use App\Models\OrganisasiModel;
use App\Models\UserModel;
use File;
use Hash;
use Illuminate\Http\Request;
use Storage;
use Validator;
use Yajra\DataTables\DataTables;

class MahasiswaProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = KelasModel::all();
        return view('mahasiswa.profile.profile_mahasiswa')->with([
            'kelas' => $kelas
        ]);
    }

    public function list_minat(Request $request)
    {
        if ($request->ajax()) {
            $minatMahasiswa = MinatMahasiswaModel::where('mahasiswa_id', auth()->user()->mahasiswa->mahasiswa_id)->with('bidang_keahlian')->get();

            // $kategoriBidangKeahlian = $query->get();

            return DataTables::of($minatMahasiswa)
                ->addIndexColumn()
                ->addColumn('bidang_keahlian_nama', function ($row) {
                    return $row->bidang_keahlian->bidang_keahlian_nama;
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalProfile(\'' . url('/profile/mahasiswa/minat/' . $row->minat_mahasiswa_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }
    public function list_organisasi(Request $request)
    {
        if ($request->ajax()) {
            $organisasiMahasiswa = MahasiswaOrganisasiModel::where('mahasiswa_id', auth()->user()->mahasiswa->mahasiswa_id)->with('organisasi')->get();

            // $kategoriBidangKeahlian = $query->get();

            return DataTables::of($organisasiMahasiswa)
                ->addIndexColumn()
                ->addColumn('organisasi_nama', function ($row) {
                    return $row->organisasi->organisasi_nama;
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalProfile(\'' . url('/profile/mahasiswa/organisasi/' . $row->mahasiswa_organisasi_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function list_keahlian(Request $request)
    {
        if ($request->ajax()) {
            $keahlianMahasiswa = KeahlianMahasiswaModel::where('mahasiswa_id', auth()->user()->mahasiswa->mahasiswa_id)->with('bidang_keahlian')->get();

            // $kategoriBidangKeahlian = $query->get();

            return DataTables::of($keahlianMahasiswa)
                ->addIndexColumn()
                ->addColumn('bidang_keahlian_nama', function ($row) {
                    return $row->bidang_keahlian->bidang_keahlian_nama;
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalProfile(\'' . url('/profile/mahasiswa/keahlian/' . $row->keahlian_mahasiswa_id . '/show') . '\')" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Detail</button> ';
                    $btn .= '<button onclick="modalProfile(\'' . url('/profile/mahasiswa/keahlian/' . $row->keahlian_mahasiswa_id . '/edit') . '\')" class="btn btn-sm btn-success" title="Edit"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalProfile(\'' . url('/profile/mahasiswa/keahlian/' . $row->keahlian_mahasiswa_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function edit_password()
    {
        return view('mahasiswa.profile.edit_password');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update_password(Request $request)
    {
        if (request()->ajax()) {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }



            if (!Hash::check($request->old_password, (string) auth()->user()->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password lama salah.'
                ]);
            }
            if ($request->new_password == $request->old_password) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password baru tidak boleh sama'
                ]);
            }

            auth()->user()->update([
                'password' => $request->new_password,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diubah.'
            ]);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MahasiswaModel $mahasiswaModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MahasiswaModel $mahasiswaModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if ($request->ajax() || $request->wantsJson()) {
            // dd($request);
            // dd($request->file('foto_profile'));

            $rules = [
                'username' => 'required|max:20|unique:m_user,username,' . $mahasiswa->user->user_id . ',user_id',
                'email' => 'required|email|unique:m_mahasiswa,email,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
                'alamat' => 'required|max:200',
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
                $destinationPath = storage_path('app/public/mahasiswa/' . $mahasiswa->nim . '/profile-pictures');
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

                $imagePath = "mahasiswa/$mahasiswa->nim/profile-pictures/$filename"; // Simpan path gambar
            } else {
                $imagePath = null;
                // return  'dijalankan';
            }

            // return 'aaaa'.$imagePath;

            $check = UserModel::find($mahasiswa->user->user_id);
            if ($check) {
                // if (!$request->filled('password')) {
                //     $data_user = [
                //         'username' => $request->username,
                //     ];
                // } else {
                $data_user = [
                    'username' => $request->username,
                    // 'password' => $request->password
                ];
                // }
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
                    'email' => $request->email,
                    'no_tlp' => $request->no_tlp,
                    'alamat' => $request->alamat,
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



    // MINAT KEAHLIAN DAN ORGANISASI

    //KEAHLIAN MAHASISWA
    public function show_keahlian(KeahlianMahasiswaModel $keahlian)
    {
        return view('mahasiswa.profile.show_keahlian', compact('keahlian'));
    }

    public function create_keahlian()
    {
        $bidangKeahlian = self::showAvailableBidangKeahlian(auth()->user()->mahasiswa->mahasiswa_id, 'keahlian');
        return view('mahasiswa.profile.create_keahlian', compact('bidangKeahlian'));
    }

    public function edit_keahlian(KeahlianMahasiswaModel $keahlian)
    {
        $bidangKeahlian = self::showAvailableBidangKeahlian($keahlian->mahasiswa_id, 'keahlian', $keahlian->bidang_keahlian_id);
        return view('mahasiswa.profile.edit_keahlian', compact('bidangKeahlian', 'keahlian'));
    }
    public function store_keahlian(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (request()->ajax() || request()->wantsJson()) {
            $rules = [
                'bidang_keahlian_id' => 'required',
                'file_sertifikat' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal. ' . implode(' ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ]);

            }

            $fileSertifikat = null;
            if ($request->hasFile('file_sertifikat')) {
                $fileSertifikat = FileController::saveMahasiswaFile($request, 'keahlian', $mahasiswa->nim, 'file_sertifikat');
            }

            try {
                KeahlianMahasiswaModel::create([
                    'mahasiswa_id' => auth()->user()->mahasiswa->mahasiswa_id,
                    'bidang_keahlian_id' => $request->bidang_keahlian_id,
                    'file_sertifikat' => $fileSertifikat
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal disimpan. ' . $th->getMessage(),
                ]);
            }
        }
    }
    public function update_keahlian(Request $request, KeahlianMahasiswaModel $keahlian)
    {
        // dd($keahlian->mahasiswa->nim);
        // dd($request->file());
        if (request()->ajax() || request()->wantsJson()) {
            $rules = [
                'bidang_keahlian_id' => 'required',
                'file_sertifikat' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal. ' . implode(' ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ]);

            }

            $keahlian->bidang_keahlian_id = $request->bidang_keahlian_id;
            $newFile = null;
            if ($request->hasFile('file_sertifikat') && ($newFile = FileController::saveMahasiswaFile($request, 'keahlian', $keahlian->mahasiswa->nim, 'file_sertifikat'))) {
                FileController::deleteFile($keahlian->file_sertifikat);
                $keahlian->file_sertifikat = $newFile;
            }

            try {
                $keahlian->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } catch (\Throwable $th) {
                FileController::deleteFile($newFile);
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal diupdate',
                    'errors' => $th->getMessage()
                ]);
            }
        }
    }


    public function confirm_keahlian(KeahlianMahasiswaModel $keahlian)
    {
        return view('mahasiswa.profile.confirm_delete_keahlian', compact('keahlian'));
    }
    public function destroy_keahlian(KeahlianMahasiswaModel $keahlian)
    {
        try {
            FileController::deleteFile($keahlian->file_sertifikat);
            $keahlian->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Data gagal dihapus']);
        }
    }

    //MINAT MAHASISWA
    public function create_minat()
    {
        $bidangKeahlian = self::showAvailableBidangKeahlian(auth()->user()->mahasiswa->mahasiswa_id, 'minat');
        return view('mahasiswa.profile.create_minat', compact('bidangKeahlian'));
    }
    public function store_minat(Request $request)
    {
        if (request()->ajax() || request()->wantsJson()) {
            $rules = [
                'bidang_keahlian_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal. ' . implode(' ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ]);

            }

            try {
                MinatMahasiswaModel::create([
                    'mahasiswa_id' => auth()->user()->mahasiswa->mahasiswa_id,
                    'bidang_keahlian_id' => $request->bidang_keahlian_id,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal disimpan. ' . $th->getMessage(),
                ]);
            }
        }
    }
    public function confirm_minat(MinatMahasiswaModel $minat)
    {
        return view('mahasiswa.profile.confirm_delete_minat', compact('minat'));
    }
    public function destroy_minat(MinatMahasiswaModel $minat)
    {
        try {
            $minat->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Data gagal dihapus']);
        }
    }

    //ORGANISASI MAHASISWA
    public function create_organisasi()
    {
        $organisasi = self::showAvailableOrganisasi(auth()->user()->mahasiswa->mahasiswa_id);
        return view('mahasiswa.profile.create_organisasi', compact('organisasi'));
    }
    public function store_organisasi(Request $request)
    {
        if (request()->ajax() || request()->wantsJson()) {
            $rules = [
                'organisasi_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal. ' . implode(' ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ]);
            }
            try {
                MahasiswaOrganisasiModel::create([
                    'organisasi_id' => $request->organisasi_id,
                    'mahasiswa_id' => auth()->user()->mahasiswa->mahasiswa_id
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal disimpan. ' . $th->getMessage(),
                ]);
            }
            # code...
        }
    }
    public function confirm_organisasi(MahasiswaOrganisasiModel $organisasi)
    {
        return view('mahasiswa.profile.confirm_delete_organisasi')->with([
            'mahasiswa_organisasi' => $organisasi
        ]);
    }

    public function destroy_organisasi(MahasiswaOrganisasiModel $organisasi)
    {
        try {
            $organisasi->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Data gagal dihapus']);
        }
    }

    public static function showAvailableBidangKeahlian($mahasiswaId, $key, $except = null)
    {
        if ($key == 'keahlian') {
            $bidangKeahlian = BidangKeahlianModel::whereNotIn('bidang_keahlian_id', function ($query) use ($mahasiswaId, $except) {
                $query->select('bidang_keahlian_id')
                    ->from('r_keahlian_mahasiswa')
                    ->where('mahasiswa_id', $mahasiswaId);

                if ($except) {
                    $query->where('bidang_keahlian_id', '!=', $except);
                }
            })->orWhere('bidang_keahlian_id', $except)->get();
        } elseif ($key == 'minat') {
            $bidangKeahlian = BidangKeahlianModel::whereNotIn('bidang_keahlian_id', function ($query) use ($mahasiswaId, $except) {
                $query->select('bidang_keahlian_id')
                    ->from('r_minat_mahasiswa')
                    ->where('mahasiswa_id', $mahasiswaId);

                if ($except) {
                    $query->where('bidang_keahlian_id', '!=', $except);
                }
            })->orWhere('bidang_keahlian_id', $except)->get();
        } else {
            return null;
        }

        return $bidangKeahlian;
    }
    public static function showAvailableOrganisasi($mahasiswaId, $except = null)
    {
        $bidangKeahlian = OrganisasiModel::whereNotIn('organisasi_id', function ($query) use ($mahasiswaId, $except) {
            $query->select('organisasi_id')
                ->from('r_mahasiswa_organisasi')
                ->where('mahasiswa_id', $mahasiswaId);

            if ($except) {
                $query->where('organisasi_id', '!=', $except);
            }
        })->orWhere('organisasi_id', $except)->get();

        return $bidangKeahlian;
    }


}
