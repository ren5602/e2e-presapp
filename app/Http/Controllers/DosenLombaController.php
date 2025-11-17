<?php

namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\LombaModel;
use App\Models\PenyelenggaraModel;
use App\Models\TingkatLombaModel;
use App\Http\Controllers\Auth;
use App\Models\MahasiswaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenLombaController extends Controller
{
    public function index()
    {
        // Ambil data dengan pagination
        $lomba = LombaModel::with(['tingkat', 'bidang', 'penyelenggara']) // jika relasi ini digunakan di Blade
            ->orderByDesc('created_at')
            ->paginate(8); // batasi 8 per halaman

        return view('dosen.lomba.daftar_lomba', [
            'lomba' => $lomba
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(LombaModel $prestasi)
    {
        return view('dosen.lomba.show_lomba', compact('lomba'));
    }

    public function edit(LombaModel $lomba)
    {
        $tingkatLomba = TingkatLombaModel::all();
        $bidangKeahlian = BidangKeahlianModel::all();
        $penyelenggara = PenyelenggaraModel::all();
        return view('dosen.lomba.edit_lomba')->with([
            'lomba' => $lomba,
            'tingkatLomba' => $tingkatLomba,
            'bidangKeahlian' => $bidangKeahlian,
            'penyelenggara' => $penyelenggara
        ]);
    }

    public function update(Request $request, LombaModel $lomba)
    {
        // dd($request);

        $rules = [
            'lomba_kode' => 'required|string|max:255',
            'lomba_nama' => 'required|string|max:255',
            'lomba_deskripsi' => 'required|string|max:255',
            'tingkat_lomba_id' => 'required|exists:m_tingkat_lomba,tingkat_lomba_id',
            'bidang_keahlian_id' => 'required|exists:m_bidang_keahlian,bidang_keahlian_id',
            'penyelenggara_id' => 'required|exists:m_penyelenggara,penyelenggara_id',
            'tanggal_mulai' => 'required|date|date_format:Y-m-d',
            'tanggal_selesai' => 'required|date|date_format:Y-m-d',
            'foto_pamflet' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        
        $update_data =[
            'lomba_kode' => $request->lomba_kode,
            'lomba_nama' => $request->lomba_nama,
            'lomba_deskripsi' => $request->lomba_deskripsi,
            'link_website' => $request->link_website,
            'tingkat_lomba_id' => $request->tingkat_lomba_id,
            'bidang_keahlian_id' => $request->bidang_keahlian_id,
            'penyelenggara_id' => $request->penyelenggara_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'foto_pamflet' => $imagePath,
            'status_verifikasi' => $request->status_verifikasi
        ];

        $mhs = MahasiswaModel::all();

        $nim_dosen = $mhs->nim;

        if ($request->hasFile('foto_pamflet')) {
            self::deleteFile($lomba->foto_pamflet);
            $lomba->foto_pamflet = self::saveFile($request, 'foto_pamflet', $nim_dosen, 'foto_pamflet');
        }

        $lomba->status_verifikasi = null;
        $lomba->updated_at = Carbon::now();
        $lomba->save();

        $lomba->poin = self::hitungPoin($lomba);

        $lomba->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function confirm(LombaModel $lomba)
    {
        return view('dosen.lomba.confirm_delete_lomba', compact('lomba'));
    }

    public function destroy(LombaModel $lomba)
    {
        try {
            self::deleteFile($lomba->file_foto_pamflet);

            $lomba->delete();

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


    //STATIC METHOD

    private static function saveFile($requestFile, string $jenis, string $nim_dosen, string $nama_variabel)
    {
        if ($requestFile->hasFile($nama_variabel)) {
            // return response()->json(['error' => 'No file uploaded'], 400);
            $file = $requestFile->file($nama_variabel);

            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file'], 400);
            }

            // Nama file unik
            $filename = time() . '_' . $file->getClientOriginalName();

            // Pastikan folder penyimpanan ada
            $destinationPath = storage_path('app/public/dosen/' . $nim_dosen . '/prestasi/' . $jenis . '/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            // Pindahkan file
            $file->move($destinationPath, $filename);

            $imagePath = "dosen/$nim_dosen/prestasi/$jenis/$filename"; // Simpan path gambar
        } else {
            $imagePath = null;
        }
        return $imagePath;
    }

    private static function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private static function hitungPoin(LombaModel $prestasi)
    {
        $poin = 5;

        return $poin;
    }
}
