<?php

namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\KategoriBidangKeahlianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BidangKeahlianController extends Controller
{
    public function index()
    {
        $bidangKeahlian = BidangKeahlianModel::all();
        $kategori = KategoriBidangKeahlianModel:: all();
        return view("admin.bidangKeahlian.daftar_bidangKeahlian")->with(["bidangKeahlian" => $bidangKeahlian, 'kategori' => $kategori]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = BidangKeahlianModel::with('kategoriBidangKeahlian');

            if($request->kategori_bidang_keahlian_id){
                $query->where('kategori_bidang_keahlian_id', $request->kategori_bidang_keahlian_id);
            }

            $bidangKeahlian = $query->get();

            return DataTables::of($bidangKeahlian)
                ->addIndexColumn()
                ->addColumn('bidang_keahlian_kode', function ($row) {
                    return $row->bidang_keahlian_kode;
                })
                ->addColumn('bidang_keahlian_nama', function ($row) {
                    return $row->bidang_keahlian_nama;
                })
                ->addColumn('kategori', function ($row) {
                    // pastikan relasi tersedia sebelum diakses
                    return $row->kategoriBidangKeahlian->kategori_bidang_keahlian_nama ?? '-';
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/bidangKeahlian/' . $row->bidang_keahlian_id . '/show') . '\')" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/bidangKeahlian/' . $row->bidang_keahlian_id . '/edit') . '\')" class="btn btn-sm btn-warning mt-1 mb-1" title="Edit"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/bidangKeahlian/' . $row->bidang_keahlian_id . '/delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }


    public function create()
    {
        $kategoriBidangKeahlian = KategoriBidangKeahlianModel::all();
        return view("admin.bidangKeahlian.create_bidangKeahlian")->with(["kategoriBidangKeahlian" => $kategoriBidangKeahlian]);
    }

    public function store(Request $request)
    {
        $rules = [
            'bidang_keahlian_kode' => 'required|string|max:255',
            'bidang_keahlian_nama' => 'required|string|max:255',
            'kategori_bidang_keahlian_id' => 'required|exists:m_kategori_bidang_keahlian,kategori_bidang_keahlian_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        BidangKeahlianModel::create([
            'bidang_keahlian_kode' => $request->bidang_keahlian_kode,
            'bidang_keahlian_nama' => $request->bidang_keahlian_nama,
            'kategori_bidang_keahlian_id' => $request->kategori_bidang_keahlian_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function show(BidangKeahlianModel $bidangKeahlian)
    {
        // $bidangKeahlian = BidangKeahlianModel::find($id);
        return view('admin.bidangKeahlian.show_bidangKeahlian')->with(['bidangKeahlian' => $bidangKeahlian]);
    }

    public function edit(BidangKeahlianModel $bidangKeahlian)
    {
        $kategoriBidangKeahlian = KategoriBidangKeahlianModel::all();
        return view('admin.bidangKeahlian.edit_bidangKeahlian')->with([
            'kategoriBidangKeahlian' => $kategoriBidangKeahlian, 
            'bidangKeahlian' => $bidangKeahlian
        ]);
    }

    public function update(Request $request, BidangKeahlianModel $bidangKeahlian)
    {
        // $bidangKeahlian = BidangKeahlianModel::findOrFail($id);

        $rules = [
            'bidang_keahlian_kode' => 'required|string|max:255',
            'bidang_keahlian_nama' => 'required|string|max:255',
            'kategori_bidang_keahlian_id' => 'required|exists:m_kategori_bidang_keahlian,kategori_bidang_keahlian_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $bidangKeahlian->update([
            'bidang_keahlian_kode' => $request->bidang_keahlian_kode,
            'bidang_keahlian_nama' => $request->bidang_keahlian_nama,
            'kategori_bidang_keahlian_id' => $request->kategori_bidang_keahlian_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    public function confirm(BidangKeahlianModel $bidangKeahlian)
    {
        return view('admin.bidangKeahlian.confirm_delete_bidangKeahlian')->with(['bidangKeahlian' => $bidangKeahlian]);
    }

    public function destroy(BidangKeahlianModel $bidangKeahlian)
    {
        try {
            $bidangKeahlian->delete();

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