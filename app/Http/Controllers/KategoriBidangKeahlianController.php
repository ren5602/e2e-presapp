<?php

namespace App\Http\Controllers;

use App\Models\KategoriBidangKeahlianModel;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;

class KategoriBidangKeahlianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.kategori_bidang_keahlian.daftar_kategoriBidangKeahlian");
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $kategoriBidangKeahlian = KategoriBidangKeahlianModel::all();

            // $kategoriBidangKeahlian = $query->get();

            return DataTables::of($kategoriBidangKeahlian)
                ->addIndexColumn()
                ->addColumn('kategori_bidang_keahlian_kode', function ($row) {
                    return $row->kategori_bidang_keahlian_kode;
                })
                ->addColumn('kategori_bidang_keahlian_nama', function ($row) {
                    return $row->kategori_bidang_keahlian_nama;
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/KategoriBidangKeahlian/' . $row->kategori_bidang_keahlian_id . '/show') . '\')" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/KategoriBidangKeahlian/' . $row->kategori_bidang_keahlian_id . '/edit') . '\')" class="btn btn-sm btn-warning mt-1 mb-1" title="Edit"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/KategoriBidangKeahlian/' . $row->kategori_bidang_keahlian_id . '/delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.kategori_bidang_keahlian.create_kategoriBidangKeahlian");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'kategori_bidang_keahlian_kode' => 'required|string|max:255|unique:m_kategori_bidang_keahlian,kategori_bidang_keahlian_kode',
            'kategori_bidang_keahlian_nama' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        KategoriBidangKeahlianModel::create([
            'kategori_bidang_keahlian_kode' => $request->kategori_bidang_keahlian_kode,
            'kategori_bidang_keahlian_nama' => $request->kategori_bidang_keahlian_nama,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriBidangKeahlianModel $kategoriBidangKeahlian)
    {
        return view("admin.kategori_bidang_keahlian.show_kategoriBidangKeahlian")->with(["kategoriBidangKeahlian" => $kategoriBidangKeahlian]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriBidangKeahlianModel $kategoriBidangKeahlian)
    {
        return view("admin.kategori_bidang_keahlian.edit_kategoriBidangKeahlian")->with(["kategoriBidangKeahlian" => $kategoriBidangKeahlian]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriBidangKeahlianModel $kategoriBidangKeahlian)
    {
        // $kategoriBidangKeahlian = KategoriBidangKeahlianModel::findOrFail($id);

        $rules = [
            'kategori_bidang_keahlian_kode' => 'required|string|max:255',
            'kategori_bidang_keahlian_nama' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $kategoriBidangKeahlian->update([
            'kategori_bidang_keahlian_kode' => $request->kategori_bidang_keahlian_kode,
            'kategori_bidang_keahlian_nama' => $request->kategori_bidang_keahlian_nama,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function confirm(KategoriBidangKeahlianModel $kategoriBidangKeahlian)
    {
        return view("admin.kategori_bidang_keahlian.confirm_delete_kategoriBidangKeahlian")->with(["kategoriBidangKeahlian" => $kategoriBidangKeahlian]);
    }

    public function destroy(KategoriBidangKeahlianModel $kategoriBidangKeahlian)
    {
        try {
            $kategoriBidangKeahlian->delete();

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
