<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.admin.daftar_admin');
    }

    public function list(Request $request)
{
    if ($request->ajax()) {
        $admin = AdminModel::select("admin_id", "nama", "email", "no_tlp", "foto_profile");


        if ($request->nama) {
            $admin->where('nama', 'like', '%' . $request->nama . '%');
        }

        $admin = $admin->get();

        return DataTables::of($admin)
            ->addIndexColumn() // untuk nomor urut (DT_RowIndex)
            ->addColumn('info', function ($row) {
                $image = $row->foto_profile
                    ? asset('storage/' . $row->foto_profile)
                    : asset('assets/images/user.png');

                return '
                    <div class="d-flex align-items-center text-start">
                        <img 
                            src="' . $image . '" 
                            alt="User image" 
                            class="rounded-circle" 
                            style="width: 40px; height: 40px; object-fit: cover; margin-right: 15px;"
                        >
                        <div class="d-flex flex-column justify-content-center">
                            <div style="font-weight: bold;">' . e($row->nama) . '</div>
                            <div class="text-muted"><i class="fa fa-envelope me-1"></i> ' . e($row->email) . '</div>
                            <div class="text-muted"><i class="fa fa-phone me-1"></i> ' . e($row->no_tlp) . '</div>
                        </div>
                    </div>
                ';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/' . $row->admin_id . '/show') . '\')" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $row->admin_id . '/edit') . '\')" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $row->admin_id . '/delete') . '\')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>';
                return $btn;
            })
            ->rawColumns(['info', 'aksi'])
            ->make(true);
    }

    
    return response()->json(['message' => 'Invalid request.'], 400);
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

/*******  b9bbc611-7253-420d-a07e-801617ec55d4  *******/
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminModel $AdminModel)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminModel $AdminModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminModel $AdminModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminModel $AdminModel)
    {
        //
    }
}
