<?php

namespace App\Http\Controllers;

use App\Models\PrestasiModel;
use App\Models\TingkatLombaModel;
use Illuminate\Http\Request;

class DosenPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosenId = auth()->user()->dosen->dosen_id;
        $search = request('search');

        $tingkatLombaId = request('tingkat_lomba_id');
        $statusVerifikasiInput = request('status_verifikasi');

        $prestasi = PrestasiModel::where('dosen_id', $dosenId)
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
        return view('dosen.prestasi.daftar_prestasi')->with([
            'prestasi' => $prestasi,
            'tingkat_lomba' => $tingkat_lomba,
            'dosbim' => false
        ]);
    }

    public function allPrestasi()
    {
        $search = request('search');

        $tingkatLombaId = request('tingkat_lomba_id');
        $statusVerifikasiInput = request('status_verifikasi');

        $prestasi = PrestasiModel::when($search, function ($query, $search) {
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
        return view('dosen.prestasi.daftar_prestasi')->with([
            'prestasi' => $prestasi,
            'tingkat_lomba' => $tingkat_lomba,
            'dosbim' => true
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PrestasiModel $prestasi)
    {
        return view('dosen.prestasi.show_prestasi', compact('prestasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrestasiModel $prestasiModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrestasiModel $prestasiModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrestasiModel $prestasiModel)
    {
        //
    }
}
