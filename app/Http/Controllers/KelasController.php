<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class KelasController extends Controller
{
    public function index()
    {
        $prodi = ProdiModel::all();
        return view('admin.kelas.daftar_kelas')->with([
            'prodi' => $prodi
        ]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $kelas = KelasModel::with('prodi');

            if ($request->prodi_id) {
                $kelas->where('prodi_id', $request->prodi_id);
            }

            $kelas = $kelas->get();

            return DataTables::of($kelas)
                ->addIndexColumn()
                ->addColumn('kode', function ($row) {
                    return $row->kelas_kode;
                })
                ->addColumn('nama', function ($row) {
                    return $row->kelas_nama;
                })
                ->addColumn('prodi', function ($row) {
                    return $row->prodi->prodi_nama ?? '-';
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/kelas/' . $row->kelas_id . '/show') . '\')" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kelas/' . $row->kelas_id . '/edit') . '\')" class="btn btn-warning btn-sm mt-1 mb-1"><i class="fa fa-pen"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kelas/' . $row->kelas_id . '/confirm-delete') . '\')" class="btn btn-danger btn-sm mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function create()
    {
        $prodi = ProdiModel::all();
        return view('admin.kelas.create_kelas')->with([
            'prodi' => $prodi
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'kelas_kode' => 'required|string|max:50',
            'kelas_nama' => 'required|string|max:255',
            'prodi_id' => 'required|exists:m_prodi,prodi_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        KelasModel::create([
            'kelas_kode' => $request->kelas_kode,
            'kelas_nama' => $request->kelas_nama,
            'prodi_id' => $request->prodi_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function show(KelasModel $kelas)
    {
        return view('admin.kelas.show_kelas')->with([
            'kelas' => $kelas
        ]);
    }

    public function edit(KelasModel $kelas)
    {
        $prodi = ProdiModel::all();
        return view('admin.kelas.edit_kelas')->with([
            'kelas' => $kelas,
            'prodi' => $prodi
        ]);
    }

    public function update(Request $request, KelasModel $kelas)
    {
        $rules = [
            'kelas_kode' => 'required|string|max:50',
            'kelas_nama' => 'required|string|max:255',
            'prodi_id' => 'required|exists:m_prodi,prodi_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $kelas->update([
            'kelas_kode' => $request->kelas_kode,
            'kelas_nama' => $request->kelas_nama,
            'prodi_id' => $request->prodi_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    public function confirmDelete(KelasModel $kelas)
    {
        return view('admin.kelas.confirm_delete_kelas')->with([
            'kelas' => $kelas
        ]);
    }

    public function destroy(KelasModel $kelas)
    {
        try {
            $kelas->delete();

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
        return view('admin.kelas.import_kelas');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kelas' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal saat upload file.',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_kelas');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $inserted = 0;

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris == 1) continue; // Skip header

                    try {
                        KelasModel::create([
                            'kelas_kode' => trim($value['A']),
                            'kelas_nama' => trim($value['B']),
                            'prodi_id' => trim($value['C']) ?: null,
                        ]);

                        $inserted++;
                    } catch (\Exception $e) {
                        continue;
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
        $kelasList = KelasModel::with(['prodi'])->orderBy('kelas_id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kelas');
        $sheet->setCellValue('C1', 'Nama Kelas');
        $sheet->setCellValue('D1', 'Program Studi');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $baris = 2;
        $no = 1;
        foreach ($kelasList as $kelas) {
            $sheet->setCellValue("A$baris", $no++);
            $sheet->setCellValue("B$baris", $kelas->kelas_kode);
            $sheet->setCellValue("C$baris", $kelas->kelas_nama);
            $sheet->setCellValue("D$baris", $kelas->prodi->prodi_nama ?? '-');
            $baris++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Kelas_' . date('Y-m-d_H-i-s') . '.xlsx';

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