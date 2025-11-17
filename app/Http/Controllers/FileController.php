<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use Storage;

class FileController extends Controller
{
    public static function saveFile($requestFile, string $jenis, string $nim_mahasiswa, string $nama_variabel)
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
            $destinationPath = storage_path('app/public/mahasiswa/' . $nim_mahasiswa . '/prestasi/' . $jenis . '/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            // Pindahkan file
            $file->move($destinationPath, $filename);

            $imagePath = "mahasiswa/$nim_mahasiswa/prestasi/$jenis/$filename"; // Simpan path gambar
        } else {
            $imagePath = null;
        }
        return $imagePath;
    }

        public static function saveMahasiswaFile($requestFile, string $jenis, string $nim_mahasiswa, string $nama_variabel)
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
            $destinationPath = storage_path('app/public/mahasiswa/' . $nim_mahasiswa . '/' . $jenis . '/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            // Pindahkan file
            $file->move($destinationPath, $filename);

            $imagePath = "mahasiswa/$nim_mahasiswa/$jenis/$filename"; // Simpan path gambar
        } else {
            $imagePath = null;
        }
        return $imagePath;
    }

    public static function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
