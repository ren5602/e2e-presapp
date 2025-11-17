<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\PrestasiModel;
use App\Models\RekomendasiMahasiswaLombaModel;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function getMahasiswaNotification()
    {
        $mahasiswa_id = auth()->user()->mahasiswa->mahasiswa_id;
        $rekomendasi = RekomendasiMahasiswaLombaModel::with(['lomba:lomba_id,lomba_nama,lomba_deskripsi'])
            ->where('mahasiswa_id', $mahasiswa_id)
            ->get()
            ->unique('lomba_id')
            ->map(function ($item) {
                return (object) [
                    'lomba_id' => $item->lomba->lomba_id ?? null,
                    'lomba_nama' => $item->lomba->lomba_nama ?? null,
                    'lomba_deskripsi' => $item->lomba->lomba_deskripsi ?? null,
                ];
            })
            ->values(); // <--- TANPA toArray()


        $jmlPrestasi = count(PrestasiModel::where('mahasiswa_id', $mahasiswa_id)->where('status_verifikasi', 1)->get());
        $jmlPrestasiPending = count(PrestasiModel::where('mahasiswa_id', $mahasiswa_id)->where('status_verifikasi', null)->get());
        $jmlPrestasiDitolak = count(PrestasiModel::where('mahasiswa_id', $mahasiswa_id)->where('status_verifikasi', 0)->get());

        return (object) [
            'rekomendasi' => $rekomendasi,
            'jmlPrestasi' => $jmlPrestasi,
            'jmlPrestasiPending' => $jmlPrestasiPending,
            'jmlPrestasiDitolak' => $jmlPrestasiDitolak,
        ];

    }
}
