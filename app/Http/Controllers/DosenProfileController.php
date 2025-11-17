<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\UserModel;
use Hash;
use Illuminate\Http\Request;
use Storage;
use Validator;

class DosenProfileController extends Controller
{
    public function index()
    {
        return view('dosen.profile.profile_dosen');
    }

    public function update(Request $request, DosenModel $dosen)
    {
        // dd($dosen);

        // $dosen = auth()->user()->dosen;
        if ($request->ajax() || $request->wantsJson()) {
            // dd($request);
            // dd($request->file('foto_profile'));

            $rules = [
                'nama' => 'required|max:200',
                'username' => 'required|max:20|unique:m_user,username,' . $dosen->user->user_id . ',user_id',
                'email' => 'required|email|unique:m_dosen,email,' . $dosen->dosen_id . ',dosen_id',
                'no_tlp' => 'required|max:20',
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
                $destinationPath = storage_path('app/public/dosen/' . $dosen->username . '/profile-pictures');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }

                // Hapus file lama jika ada
                $oldImage = $dosen->foto_profile ?? null; // Ambil path file lama dari database

                if ($oldImage) {
                    FileController::deleteFile($oldImage);
                }

                // Pindahkan file
                $file->move($destinationPath, $filename);

                $imagePath = "dosen/$dosen->username/profile-pictures/$filename"; // Simpan path gambar
            } else {
                $imagePath = null;
                // return  'dijalankan';
            }

            // return 'aaaa'.$imagePath;

            $check = UserModel::find($dosen->user->user_id);
            if ($check) {
                $data_user = [
                    'username' => $request->username,
                ];
                // }
                $check->update($data_user);

                if ($request->input('remove_picture') == "1") {
                    // Hapus gambar lama jika ada
                    if ($dosen->foto_profile) {
                        $oldImage = $dosen->foto_profile; // Ambil path file lama dari database
                        if ($oldImage) {
                            FileController::deleteFile($oldImage);
                        }
                    }
                    $imagePath = null; // Set kolom di database jadi null
                }

                $data_dosen = [
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_tlp' => $request->no_tlp,
                    'foto_profile' => $imagePath
                ];
                $dosen->update($data_dosen);
                return response()->json(['status' => true, 'message' => 'Data berhasil diupdate']);
            } else {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
            }
        }
        return redirect('/');
    }

    public function edit_password()
    {
        return view('dosen.profile.edit_password');
    }

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



            if (!Hash::check($request->old_password, auth()->user()->password)) {
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
}
