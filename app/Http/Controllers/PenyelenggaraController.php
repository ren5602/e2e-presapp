<?php

namespace App\Http\Controllers;

use App\Models\PenyelenggaraModel;
use App\Models\KotaModel;
use App\Models\NegaraModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PenyelenggaraController extends Controller
{
    public function index()
    {
        $kota = KotaModel::all();
        $negara = NegaraModel::all();
        return view('admin.penyelenggara.daftar_penyelenggara')->with([
            'kota' => $kota,
            'negara' => $negara
        ]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $penyelenggaras = PenyelenggaraModel::with(['kota', 'negara']);

            if ($request->kota_id) {
                $penyelenggaras->where('kota_id', $request->kota_id);
            }

            // if ($request->negara_id) {
            //     $penyelenggaras->where('negara_id', $request->negara_id);
            // }

            $penyelenggaras = $penyelenggaras->get();

            return DataTables::of($penyelenggaras)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->penyelenggara_nama;
                })
                ->addColumn('kota', function ($row) {
                    return $row->kota->kota_nama ?? '-';
                })
                ->addColumn('negara', function ($row) {
                    return $row->kota->provinsi->negara->negara_nama ?? '-';
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/penyelenggara/' . $row->penyelenggara_id . '/show') . '\')" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/penyelenggara/' . $row->penyelenggara_id . '/edit') . '\')" class="btn btn-warning btn-sm mt-1 mb-1"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/penyelenggara/' . $row->penyelenggara_id . '/confirm-delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function create()
    {
        $kota = KotaModel::all();
        // $negara = NegaraModel::all();
        return view('admin.penyelenggara.create_penyelenggara')->with([
            'kota' => $kota,
            // 'negara' => $negara
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'penyelenggara_nama' => 'required|string|max:255',
            'kota_id' => 'required|exists:m_kota,kota_id',
            // 'negara_id' => 'required|exists:m_negara,negara_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        PenyelenggaraModel::create([
            'penyelenggara_nama' => $request->penyelenggara_nama,
            'kota_id' => $request->kota_id,
            // 'negara_id' => $request->negara_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function show(PenyelenggaraModel $penyelenggara)
    {
        // $penyelenggara = PenyelenggaraModel::with(['kota', 'negara'])->findOrFail($id);
        return view('admin.penyelenggara.show_penyelenggara')->with(['penyelenggara' => $penyelenggara]);
    }

    public function edit($id)
    {
        $penyelenggara = PenyelenggaraModel::findOrFail($id);
        $kota = KotaModel::all();
        $negara = NegaraModel::all();

        return view('admin.penyelenggara.edit_penyelenggara')->with([
            'penyelenggara' => $penyelenggara,
            'kota' => $kota,
            'negara' => $negara
        ]);
    }

    public function update(Request $request, $id)
    {
        $penyelenggara = PenyelenggaraModel::findOrFail($id);

        $rules = [
            'penyelenggara_nama' => 'required|string|max:255',
            'kota_id' => 'required|exists:m_kota,kota_id',
            // 'negara_id' => 'required|exists:m_negara,negara_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        // Validasi kota hanya bisa dipilih jika negara Indonesia
        // if ($request->negara_id != 92 && $request->filled('kota_id')) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Kota hanya boleh dipilih jika negara adalah Indonesia.',
        //         'msgField' => ['kota_id' => ['Hanya untuk negara Indonesia']]
        //     ]);
        // }

        $penyelenggara->update([
            'penyelenggara_nama' => $request->penyelenggara_nama,
            'kota_id' => $request->kota_id,
            // 'negara_id' => $request->negara_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    public function confirmDelete(PenyelenggaraModel $penyelenggara)
    {
        return view('admin.penyelenggara.confirm_delete_penyelenggara')->with(['penyelenggara' => $penyelenggara]);
    }

    public function destroy(PenyelenggaraModel $penyelenggara)
    {
        try {
            $penyelenggara->delete();

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

    public function import()
    {
        return view('admin.penyelenggara.import_penyelenggara');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_penyelenggara' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal saat upload file.',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_penyelenggara');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $inserted = 0;

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris == 1) continue; // Header

                    try {
                        PenyelenggaraModel::create([
                            'penyelenggara_nama' => trim($value['A']),
                            'kota_id' => trim($value['B']) ?: null,
                        ]);

                        $inserted++;
                    } catch (\Exception $e) {
                        continue; // Skip error, lanjutkan
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => "Import selesai. Total data disimpan: $inserted"
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data pada file.'
            ]);
        }

        return redirect('/');
    }

    public function export_excel()
    {
        $penyelenggaras = PenyelenggaraModel::with(['kota'])->orderBy('penyelenggara_id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Penyelenggara');
        $sheet->setCellValue('C1', 'Kota');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $baris = 2;
        $no = 1;
        foreach ($penyelenggaras as $p) {
            $sheet->setCellValue("A$baris", $no++);
            $sheet->setCellValue("B$baris", $p->penyelenggara_nama);
            $sheet->setCellValue("C$baris", $p->kota->kota_nama ?? '-');
            $baris++;
        }

        // Auto width
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Penyelenggara_' . date('Y-m-d_H-i-s') . '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}