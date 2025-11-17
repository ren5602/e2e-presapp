<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodi = ProdiModel::all();
        return view('admin.prodi.daftar_prodi')->with(["prodi" => $prodi]);
    }

    /**
     * Display a listing of Prodi data in DataTables.
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $prodi = ProdiModel::select('prodi_id', 'prodi_kode', 'prodi_nama');
            return DataTables::of($prodi)
                ->addIndexColumn()
                ->addColumn('info', function ($row) {
                    return $row->prodi_nama;
                })
                ->addColumn('kode', function ($row) {
                    return $row->prodi_kode;
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/prodi/' . $row->prodi_id . '/edit') . '\')" class="btn btn-warning btn-sm mt-1 mb-1"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $row->prodi_id . '/confirm-delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['info', 'aksi'])
                ->make(true);
        }
    }

    public function create()
    {
        return view("admin.prodi.create_prodi");
    }

    public function store(Request $request)
    {
        $rules = [
            'prodi_nama' => 'required|string|max:255',
            'prodi_kode' => 'required|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        ProdiModel::create([
            'prodi_nama' => $request->prodi_nama,
            'prodi_kode' => $request->prodi_kode,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function show($id)
    {
        $prodi = ProdiModel::find($id);
        return view('admin.prodi.show_prodi')->with(['prodi' => $prodi]);
    }

    public function edit(ProdiModel $prodi)
    {
        return view('admin.prodi.edit_prodi')->with(['prodi' => $prodi]);
    }

    public function update(Request $request, $id)
    {
        $prodi = ProdiModel::findOrFail($id);

        $rules = [
            'prodi_nama' => 'required|string|max:255',
            'prodi_kode' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $prodi->update([
            'prodi_nama' => $request->prodi_nama,
            'prodi_kode' => $request->prodi_kode,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    public function confirmDelete(ProdiModel $prodi)
    {
        return view('admin.prodi.confirm_delete_prodi')->with(['prodi' => $prodi]);
    }

    public function destroy(ProdiModel $prodi)
    {
        try {
            $prodi->delete();

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
