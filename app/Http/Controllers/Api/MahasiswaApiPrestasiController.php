<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PoinPrestasiController;
use App\Models\DosenModel;
use App\Models\LombaModel;
use App\Models\PrestasiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class MahasiswaApiPrestasiController extends Controller
{
    /**
     * Get list of prestasi for the authenticated user
     * GET /api/prestasi
     */
    public function index(Request $request)
    {
        try {
            $mahasiswaId = auth()->user()->mahasiswa->mahasiswa_id;
            $search = $request->query('search');
            $tingkatLombaId = $request->query('tingkat_lomba_id');
            $statusVerifikasiInput = $request->query('status_verifikasi');
            $page = $request->query('page', 1);
            $perPage = $request->query('per_page', 10);

            $prestasi = PrestasiModel::where('mahasiswa_id', $mahasiswaId)
                ->when($search, function ($query, $search) {
                    $query->where('prestasi_nama', 'like', "%{$search}%")
                        ->orWhereHas('lomba', function ($q) use ($search) {
                            $q->where('lomba_nama', 'like', "%{$search}%");
                        });
                })
                ->when($tingkatLombaId, function ($query, $tingkatLombaId) {
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
                ->with(['mahasiswa', 'dosen', 'lomba.tingkat', 'lomba.penyelenggara'])
                ->orderByDesc('updated_at')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'message' => 'Daftar prestasi berhasil diambil',
                'data' => $prestasi->items(),
                'pagination' => [
                    'current_page' => $prestasi->currentPage(),
                    'per_page' => $prestasi->perPage(),
                    'total' => $prestasi->total(),
                    'last_page' => $prestasi->lastPage(),
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar prestasi',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail of a specific prestasi
     * GET /api/prestasi/{prestasi_id}
     */
    public function show($prestasiId)
    {
        try {
            $prestasi = PrestasiModel::with(['mahasiswa', 'dosen', 'lomba.tingkat', 'lomba.penyelenggara'])
                ->findOrFail($prestasiId);

            // Authorization check: verify mahasiswa ownership
            if ($prestasi->mahasiswa->user_id !== auth()->user()->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak diizinkan mengakses prestasi ini'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail prestasi berhasil diambil',
                'data' => $prestasi
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Prestasi tidak ditemukan'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail prestasi',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new prestasi
     * POST /api/prestasi
     */
    public function store(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'dosen_id' => 'required|exists:m_dosen,dosen_id',
                'lomba_id' => 'required|exists:m_lomba,lomba_id',
                'prestasi_nama' => 'required|string|max:255',
                'juara' => 'required|integer|in:1,2,3,4',
                'nama_juara' => 'nullable|string|max:255',
                'tanggal_perolehan' => 'required|date',
                'file_sertifikat' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'file_bukti_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'file_surat_tugas' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'file_surat_undangan' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'file_proposal' => 'nullable|file|mimes:pdf|max:4096',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Special validation for juara == 4
            if ($request->juara == 4) {
                $juaraValidator = Validator::make($request->all(), [
                    'nama_juara' => 'required|string|max:255',
                ]);

                if ($juaraValidator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $juaraValidator->errors()
                    ], 422);
                }

                $nama_juara = $request->nama_juara;
            } else {
                $nama_juara = 'Juara ' . $request->juara;
            }

            $mhs = auth()->user()->mahasiswa;
            $nim_mahasiswa = $mhs->nim;

            // Save files
            $imagePaths = [];
            $imagePaths['file_sertifikat'] = FileController::saveFile($request, 'sertifikat', $nim_mahasiswa, 'file_sertifikat');
            $imagePaths['file_bukti_foto'] = FileController::saveFile($request, 'bukti_foto', $nim_mahasiswa, 'file_bukti_foto');
            $imagePaths['file_surat_tugas'] = FileController::saveFile($request, 'surat_tugas', $nim_mahasiswa, 'file_surat_tugas');
            $imagePaths['file_surat_undangan'] = FileController::saveFile($request, 'surat_undangan', $nim_mahasiswa, 'file_surat_undangan');
            $imagePaths['file_proposal'] = FileController::saveFile($request, 'proposal', $nim_mahasiswa, 'file_proposal');

            // Create prestasi
            $prestasi = PrestasiModel::create([
                'mahasiswa_id' => $mhs->mahasiswa_id,
                'dosen_id' => $request->dosen_id,
                'prestasi_nama' => $request->prestasi_nama,
                'lomba_id' => $request->lomba_id,
                'juara' => $request->juara,
                'nama_juara' => $nama_juara,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'file_sertifikat' => $imagePaths['file_sertifikat'],
                'file_bukti_foto' => $imagePaths['file_bukti_foto'],
                'file_surat_tugas' => $imagePaths['file_surat_tugas'],
                'file_surat_undangan' => $imagePaths['file_surat_undangan'],
                'file_proposal' => $imagePaths['file_proposal'],
                'status_verifikasi' => null,
            ]);

            // Calculate poin
            $poin = PoinPrestasiController::hitungPoin($prestasi);
            $prestasi->poin = $poin;
            $prestasi->save();

            // Load relationships and return
            $prestasi->load(['mahasiswa', 'dosen', 'lomba.tingkat', 'lomba.penyelenggara']);

            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil ditambahkan',
                'data' => $prestasi
            ], 201);
        } catch (\Throwable $th) {
            // Rollback: delete uploaded files on failure
            if (isset($imagePaths)) {
                foreach ($imagePaths as $imagePath) {
                    if ($imagePath) {
                        FileController::deleteFile($imagePath);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan prestasi',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
