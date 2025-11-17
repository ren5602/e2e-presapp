<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPrestasiController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangKeahlianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenBimbinganController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenPrestasiController;
use App\Http\Controllers\DosenProfileController;
use App\Http\Controllers\KategoriBidangKeahlianController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MahasiswaDosenLombaController;
use App\Http\Controllers\MahasiswaPrestasiController;
use App\Http\Controllers\MahasiswaProfileController;
use App\Http\Controllers\MahasiswaLombaDiikutiController;
use App\Http\Controllers\MahasiswaTerdaftarLombaController;
use App\Http\Controllers\PenyelenggaraController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RekomendasiMahasiswaController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/python', [RekomendasiMahasiswaController::class, 'kalkulasiBobot'])->name('kalkulasiBobot');
// Route::get('/python_coba', [RekomendasiMahasiswaController::class, 'python_coba'])->name('python_coba');
// Route::get('/topsis', [RekomendasiMahasiswaController::class, 'rekomendasiByTopsis'])->name('rekomendasiByTopsis');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('custom.login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/logout', [AuthController::class, 'confirmLogout'])->name('logout.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['role:ADM'])->group(function () {
        Route::prefix('mahasiswa')->group(function () {
            Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
            Route::post('/list', [MahasiswaController::class, 'list']);
            Route::get('/{mahasiswa}/show', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
            Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
            Route::post('/', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
            Route::get('/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
            Route::put('/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
            Route::get('/{mahasiswa}/confirm-delete', [MahasiswaController::class, 'confirmDelete'])->name('mahasiswa.confirm-delete');
            Route::delete('/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
            Route::get('/import', [MahasiswaController::class, 'import']);
            Route::post('/import_ajax', [MahasiswaController::class, 'import_ajax']);
            Route::get('/export', [MahasiswaController::class, 'export_excel']);
        });
        Route::prefix('kelas')->group(function () {
            Route::get('/', [KelasController::class, 'index'])->name('kelas.index');
            Route::post('/list', [KelasController::class, 'list'])->name('kelas.list');
            Route::get('/create', [KelasController::class, 'create'])->name('kelas.create');
            Route::post('/', [KelasController::class, 'store'])->name('kelas.store');
            Route::get('/{kelas}/show', [KelasController::class, 'show'])->name('kelas.show');
            Route::get('/{kelas}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
            Route::put('/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
            Route::get('/{kelas}/confirm-delete', [KelasController::class, 'confirmDelete'])->name('kelas.confirm-delete'); // jika ingin pakai konfirmasi hapus
            Route::delete('/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');
            Route::get('/import', [KelasController::class, 'import']);
            Route::post('/import_ajax', [KelasController::class, 'import_ajax']);
            Route::get('/export', [KelasController::class, 'export_excel']);
        });
        Route::prefix('dosen')->group(function () {
            Route::get('/', [DosenController::class, 'index'])->name('dosen.index');
            Route::post('/list', [DosenController::class, 'list']);
            Route::get('/{dosen}/show', [DosenController::class, 'show'])->name('dosen.show');
            Route::get('/create', [DosenController::class, 'create'])->name('dosen.create');
            Route::post('/', [DosenController::class, 'store'])->name('dosen.store');
            Route::get('/{dosen}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
            Route::put('/{dosen}', [DosenController::class, 'update'])->name('dosen.update');
            Route::get('/{dosen}/delete', [DosenController::class, 'delete'])->name('dosen.delete');
            Route::delete('/{dosen}', [DosenController::class, 'destroy'])->name('dosen.destroy');
            Route::get('/import', [DosenController::class, 'import']);
            Route::post('/import_ajax', [DosenController::class, 'import_ajax']);
            Route::get('/export', [DosenController::class, 'export_excel']);
        });
        Route::prefix('admin')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admin.index');
            Route::post('/list', [AdminController::class, 'list'])->name('admin.list');
            Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
            Route::post('/', [AdminController::class, 'store'])->name('admin.store');
            Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
            Route::put('/{id}', [AdminController::class, 'update'])->name('admin.update');
            Route::delete('/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
        });
        Route::prefix('prodi')->group(function () {
            Route::get('/', [ProdiController::class, 'index'])->name('prodi.index');
            Route::post('/list', [ProdiController::class, 'list'])->name('prodi.list');
            Route::get('/{prodi}/show', [ProdiController::class, 'show'])->name('prodi.show');
            Route::get('/create', [ProdiController::class, 'create'])->name('prodi.create');
            Route::post('/', [ProdiController::class, 'store'])->name('prodi.store');
            Route::get('/{prodi}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
            Route::put('/{prodi}', [ProdiController::class, 'update'])->name('prodi.update');
            Route::get('/{prodi}/confirm-delete', [ProdiController::class, 'confirmDelete'])->name('prodi.confirm-delete'); // jika ingin pakai konfirmasi hapus
            Route::delete('/{prodi}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
        });
        Route::prefix('penyelenggara')->group(function () {
            Route::get('/', [PenyelenggaraController::class, 'index'])->name('penyelenggara.index');
            Route::post('/list', [PenyelenggaraController::class, 'list'])->name('penyelenggara.list');
            Route::get('/create', [PenyelenggaraController::class, 'create'])->name('penyelenggara.create');
            Route::post('/', [PenyelenggaraController::class, 'store'])->name('penyelenggara.store');
            Route::get('/{penyelenggara}/show', [PenyelenggaraController::class, 'show'])->name('penyelenggara.show');
            Route::get('/{penyelenggara}/edit', [PenyelenggaraController::class, 'edit'])->name('penyelenggara.edit');
            Route::put('/{penyelenggara}', [PenyelenggaraController::class, 'update'])->name('penyelenggara.update');
            Route::get('/{penyelenggara}/confirm-delete', [PenyelenggaraController::class, 'confirmDelete'])->name('penyelenggara.confirm-delete');
            Route::delete('/{penyelenggara}', [PenyelenggaraController::class, 'destroy'])->name('penyelenggara.destroy');
            Route::get('/import', [PenyelenggaraController::class, 'import']);
            Route::post('/import_ajax', [PenyelenggaraController::class, 'import_ajax']);
            Route::get('/export', [PenyelenggaraController::class, 'export_excel']);
        });
        Route::prefix('prestasi')->group(function () {
            Route::get('/', [AdminPrestasiController::class, 'index'])->name('prestasi.index');
            Route::post('/list', [AdminPrestasiController::class, 'list']);
            Route::get('/{prestasi}/show', [AdminPrestasiController::class, 'show'])->name('prestasi.show');
            Route::get('/create', [AdminPrestasiController::class, 'create'])->name('prestasi.create');
            Route::post('/', [AdminPrestasiController::class, 'store'])->name('prestasi.store');
            Route::get('/{prestasi}/edit', [AdminPrestasiController::class, 'edit'])->name('prestasi.edit');
            Route::put('/{prestasi}', [AdminPrestasiController::class, 'update'])->name('prestasi.update');
            Route::get('/{prestasi}/edit-verifikasi', [AdminPrestasiController::class, 'edit_verifikasi'])->name('prestasi.edit_verifikasi');
            Route::put('/{prestasi}/update-verifikasi', [AdminPrestasiController::class, 'update_verifikasi'])->name('prestasi.update_verifikasi');
            Route::get('/{prestasi}/confirm-delete', [AdminPrestasiController::class, 'confirmDelete'])->name('prestasi.confirm-delete');
            Route::delete('/{prestasi}', [AdminPrestasiController::class, 'destroy'])->name('prestasi.destroy');
        });

        Route::prefix('lomba')->group(function () {
            Route::get('/', [LombaController::class, 'index'])->name('lomba.index');
            Route::post('/list', [LombaController::class, 'list']);
            Route::get('/{lomba}/show', [LombaController::class, 'show'])->name('lomba.show');
            Route::get('/create', [LombaController::class, 'create'])->name('lomba.create');
            Route::post('/', [LombaController::class, 'store'])->name('lomba.store');
            Route::get('/{lomba}/edit', [LombaController::class, 'edit'])->name('lomba.edit');
            Route::put('/{lomba}', [LombaController::class, 'update'])->name('lomba.update');
            Route::get('/{lomba}/delete', [LombaController::class, 'confirm'])->name('lomba.delete');
            Route::delete('/{lomba}', [LombaController::class, 'destroy'])->name('lomba.destroy');
        });

        Route::prefix('rekomendasi')->name('rekomendasi.')->group(function () {
            Route::get('/', [RekomendasiMahasiswaController::class, 'index'])->name('index');
            Route::post('/list', [RekomendasiMahasiswaController::class, 'list']);
            Route::get('/refresh', [RekomendasiMahasiswaController::class, 'show_refresh'])->name('show_refresh');
            Route::post('/refresh', [RekomendasiMahasiswaController::class, 'refresh'])->name('refresh');
            Route::get('/confirm', [RekomendasiMahasiswaController::class, 'confirm'])->name('confirm');
            Route::delete('/', [RekomendasiMahasiswaController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('mahasiswa_lomba')->name('mahasiswa_lomba.')->group(function () {
            Route::get('/', [MahasiswaTerdaftarLombaController::class, 'index'])->name('index');
            Route::post('/list', [MahasiswaTerdaftarLombaController::class, 'list']);
            Route::get('/{mahasiswaLomba}/show', [MahasiswaTerdaftarLombaController::class, 'show'])->name('show');
            Route::get('/create', [MahasiswaTerdaftarLombaController::class, 'create'])->name('create');
            Route::post('/', [MahasiswaTerdaftarLombaController::class, 'store'])->name('store');
            Route::get('/{mahasiswaLomba}/edit-verifikasi', [MahasiswaTerdaftarLombaController::class, 'edit_verifikasi'])->name('edit-verifikasi');
            Route::put('/{mahasiswaLomba}', [MahasiswaTerdaftarLombaController::class, 'update_verifikasi'])->name('update-verifikasi');
            Route::get('/{mahasiswaLomba}/confirm-delete', [MahasiswaTerdaftarLombaController::class, 'confirm'])->name('delete');
            Route::delete('/{mahasiswaLomba}', [MahasiswaTerdaftarLombaController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('bidangKeahlian')->group(function () {
            Route::get('/', [BidangKeahlianController::class, 'index'])->name('bidangKeahlian.index');
            Route::post('/list', [BidangKeahlianController::class, 'list']);
            Route::get('/{bidangKeahlian}/show', [BidangKeahlianController::class, 'show'])->name('bidangKeahlian.show');
            Route::get('/create', [BidangKeahlianController::class, 'create'])->name('bidangKeahlian.create');
            Route::post('/', [BidangKeahlianController::class, 'store'])->name('bidangKeahlian.store');
            Route::get('/{bidangKeahlian}/edit', [BidangKeahlianController::class, 'edit'])->name('bidangKeahlian.edit');
            Route::put('/{bidangKeahlian}', [BidangKeahlianController::class, 'update'])->name('bidangKeahlian.update');
            Route::get('/{bidangKeahlian}/delete', [BidangKeahlianController::class, 'confirm'])->name('bidangKeahlian.delete');
            Route::delete('/{bidangKeahlian}', [BidangKeahlianController::class, 'destroy'])->name('bidangKeahlian.destroy');
        });

        Route::prefix('KategoriBidangKeahlian')->group(function () {
            Route::get('/', [KategoriBidangKeahlianController::class, 'index'])->name('kategoriBidangKeahlian.index');
            Route::post('/list', [KategoriBidangKeahlianController::class, 'list']);
            Route::get('/{kategoriBidangKeahlian}/show', [KategoriBidangKeahlianController::class, 'show'])->name('kategoriBidangKeahlian.show');
            Route::get('/create', [KategoriBidangKeahlianController::class, 'create'])->name('kategoriBidangKeahlian.create');
            Route::post('/', [KategoriBidangKeahlianController::class, 'store'])->name('kategoriBidangKeahlian.store');
            Route::get('/{kategoriBidangKeahlian}/edit', [KategoriBidangKeahlianController::class, 'edit'])->name('kategoriBidangKeahlian.edit');
            Route::put('/{kategoriBidangKeahlian}', [KategoriBidangKeahlianController::class, 'update'])->name('kategoriBidangKeahlian.update');
            Route::get('/{kategoriBidangKeahlian}/delete', [KategoriBidangKeahlianController::class, 'confirm'])->name('kategoriBidangKeahlian.delete');
            Route::delete('/{kategoriBidangKeahlian}', [KategoriBidangKeahlianController::class, 'destroy'])->name('kategoriBidangKeahlian.destroy');
        });
    });

    Route::middleware(['role:DOS'])->group(function () {
        Route::prefix('mahasiswa-bimbingan')->name('dosen.mahasiswa.')->group(function () {
            Route::get('/', [DosenBimbinganController::class, 'index'])->name('index');
            Route::post('/list', [DosenBimbinganController::class, 'list']);
            Route::get('/{id}/show', [DosenBimbinganController::class, 'show']);
        });

        Route::get('semua-prestasi', [DosenPrestasiController::class, 'allPrestasi'])->name('dosen.prestasi.allPrestasi');

        Route::prefix('prestasi-bimbingan')->name('dosen.prestasi.')->group(function () {
            Route::get('/', [DosenPrestasiController::class, 'index'])->name('index');
            Route::get('/{prestasi}', [DosenPrestasiController::class, 'show'])->name('show');
        });

        // Route::prefix('prestasi-lomba')->name('dosen.lomba.')->group(function () {
        //     Route::get('/', [DosenLombaController::class, 'index'])->name('index');
        //     Route::get('/{prestasi}', [DosenLombaController::class, 'show'])->name('show');
        // });
    });

    Route::middleware(['role:MHS'])->group(function () {
        Route::prefix('prestasiku')->name('mahasiswa.prestasi.')->group(function () {
            Route::get('/', [MahasiswaPrestasiController::class, 'index'])->name('index');
            Route::get('/create', [MahasiswaPrestasiController::class, 'create'])->name('create');
            Route::post('/', [MahasiswaPrestasiController::class, 'store'])->name('store');
            Route::get('/{prestasi}', [MahasiswaPrestasiController::class, 'show'])->name('show');
            Route::get('/{prestasi}/edit', [MahasiswaPrestasiController::class, 'edit'])->name('edit');
            Route::put('/{prestasi}', [MahasiswaPrestasiController::class, 'update'])->name('update');
            Route::get('/{prestasi}/confirm', [MahasiswaPrestasiController::class, 'confirm'])->name('confirm');
            Route::delete('/{prestasi}', [MahasiswaPrestasiController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('lomba_diikuti')->name('lomba_diikuti.')->group(function () {
            Route::get('/', [MahasiswaLombaDiikutiController::class, 'index'])->name('index');
            Route::get('/create', [MahasiswaLombaDiikutiController::class, 'create'])->name('create');
            Route::post('/{lomba_id}/store', [MahasiswaLombaDiikutiController::class, 'store'])->name('store');
            Route::get('/{mahasiswaLomba}', [MahasiswaLombaDiikutiController::class, 'show'])->name('show');
            Route::get('/{mahasiswaLomba}/edit', [MahasiswaLombaDiikutiController::class, 'edit'])->name('edit');
            Route::put('/{mahasiswaLomba}', [MahasiswaLombaDiikutiController::class, 'update'])->name('update');
            Route::get('/{mahasiswaLomba}/verifikasi_from_mhs', [MahasiswaLombaDiikutiController::class, 'verifikasi_from_mhs'])->name('verifikasi_from_mhs');
            Route::put('/{mahasiswaLomba}', [MahasiswaLombaDiikutiController::class, 'update_verifikasi_from_mhs'])->name('update_verifikasi_from_mhs');
            Route::get('/{mahasiswaLomba}/confirm', [MahasiswaLombaDiikutiController::class, 'confirm'])->name('confirm');
            Route::delete('/{mahasiswaLomba}', [MahasiswaLombaDiikutiController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['role:MHS,DOS'])->group(function () {
        Route::prefix('daftar_lomba')->name('daftar_lomba.')->group(function () {
            Route::get('/', [MahasiswaDosenLombaController::class, 'index'])->name('index');
            Route::get('/create', [MahasiswaDosenLombaController::class, 'create'])->name('create');
            Route::post('/', [MahasiswaDosenLombaController::class, 'store'])->name('store');
            Route::get('/{lomba}', [MahasiswaDosenLombaController::class, 'show'])->name('show');
            Route::get('/{lomba}/ikuti', [MahasiswaLombaDiikutiController::class, 'confirm_ikuti'])->name('ikuti')->middleware('role:MHS');
            Route::get('/{lomba}/edit', [MahasiswaDosenLombaController::class, 'edit'])->name('edit');
            Route::put('/{lomba}', [MahasiswaDosenLombaController::class, 'update'])->name('update');
            Route::get('/{lomba}/confirm', [MahasiswaDosenLombaController::class, 'confirm'])->name('confirm');
            Route::delete('/{lomba}', [MahasiswaDosenLombaController::class, 'destroy'])->name('destroy');
        });
    });

    //PROFILE PROFILE PROFILE

    Route::get('/profile', function () {
        if (auth()->user()->hasRole('MHS')) {
            return redirect()->action([MahasiswaProfileController::class, 'index']);
        } elseif (auth()->user()->hasRole('DOS')) {
            return redirect()->action([DosenProfileController::class, 'index']);
        } elseif (auth()->user()->hasRole('ADM')) {
            return redirect()->action([AdminProfileController::class, 'index']);
        }
        abort(403); // Role tidak dikenal
    })->name('profile');

    Route::middleware('role:MHS')->prefix('profile/mahasiswa')->name('profile.mahasiswa.')->group(function () {
        Route::get('/', [MahasiswaProfileController::class, 'index'])->name('');
        Route::get('/edit', [MahasiswaProfileController::class, 'edit'])->name('edit');
        Route::post('/update', [MahasiswaProfileController::class, 'update'])->name('update');
        Route::get('/edit-password', [MahasiswaProfileController::class, 'edit_password'])->name('edit-password');
        Route::post('/update-password', [MahasiswaProfileController::class, 'update_password'])->name('update-password');

        Route::post('/list_minat', [MahasiswaProfileController::class, 'list_minat'])->name('list_minat');
        Route::post('/list_keahlian', [MahasiswaProfileController::class, 'list_keahlian'])->name('list_keahlian');
        Route::post('/list_organisasi', [MahasiswaProfileController::class, 'list_organisasi'])->name('list_organisasi');

        Route::get('/create_minat', [MahasiswaProfileController::class, 'create_minat'])->name('minat.create');
        Route::get('/create_keahlian', [MahasiswaProfileController::class, 'create_keahlian'])->name('keahlian.create');
        Route::get('/create_organisasi', [MahasiswaProfileController::class, 'create_organisasi'])->name('organisasi.create');

        Route::post('/store_minat', [MahasiswaProfileController::class, 'store_minat'])->name('minat.store');
        Route::post('/store_keahlian', [MahasiswaProfileController::class, 'store_keahlian'])->name('keahlian.store');
        Route::post('/store_organisasi', [MahasiswaProfileController::class, 'store_organisasi'])->name('organisasi.store');

        // Keahlian
        Route::get('/keahlian/{keahlian}/show', [MahasiswaProfileController::class, 'show_keahlian'])->name('keahlian.show');
        Route::get('/keahlian/{keahlian}/edit', [MahasiswaProfileController::class, 'edit_keahlian'])->name('keahlian.edit');
        Route::put('/keahlian/{keahlian}', [MahasiswaProfileController::class, 'update_keahlian'])->name('keahlian.update');
        Route::get('/keahlian/{keahlian}/delete', [MahasiswaProfileController::class, 'confirm_keahlian'])->name('keahlian.confirm');
        Route::delete('/keahlian/{keahlian}', [MahasiswaProfileController::class, 'destroy_keahlian'])->name('keahlian.destroy');

        // Minat
        Route::get('/minat/{minat}/delete', [MahasiswaProfileController::class, 'confirm_minat'])->name('minat.confirm');
        Route::delete('/minat/{minat}', [MahasiswaProfileController::class, 'destroy_minat'])->name('minat.destroy');

        // Organisasi
        Route::get('/organisasi/{organisasi}/delete', [MahasiswaProfileController::class, 'confirm_organisasi'])->name('organisasi.confirm');
        Route::delete('/organisasi/{organisasi}', [MahasiswaProfileController::class, 'destroy_organisasi'])->name('organisasi.destroy');
    });

    Route::middleware('role:DOS')->prefix('profile/dosen')->name('profile.dosen.')->group(function () {
        Route::get('/', [DosenProfileController::class, 'index'])->name('index');
        // Route::get('/profile/dosen/edit', [DosenProfileController::class, 'edit'])->name('profile.dosen.edit');
        Route::put('/{dosen}/update', [DosenProfileController::class, 'update'])->name('update');
        Route::get('/edit-password', [DosenProfileController::class, 'edit_password'])->name('edit-password');
        Route::post('/update-password', [DosenProfileController::class, 'update_password'])->name('update-password');
    });

    Route::middleware('role:ADM')->prefix('profile/admin')->name('profile.admin.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'index'])->name('index');
        Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/{admin}/update', [AdminProfileController::class, 'update'])->name('update');
        Route::get('/edit-password', [AdminProfileController::class, 'edit_password'])->name('edit-password');
        Route::post('/update-password', [AdminProfileController::class, 'update_password'])->name('update-password');
    });

});

// Route::get('/dashboard', function () {
//     return view('admin.dashboard');
// });

// Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
