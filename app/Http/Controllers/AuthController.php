<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard'); // ganti 'dashboard' dengan nama route Anda
        }
        return view('login');
    }

    public function login(Request $request)
    {
        // dd($request);
        $identifier = $request->identifier;
        $password = $request->password;

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }


        // Coba login sebagai Mahasiswa (berdasarkan NIM)
        // $user = MahasiswaModel::where('nim', $identifier)->first();
        // if ($user && \Hash::check($password, $user->password)) {
        //     Auth::guard('mahasiswa')->login($user);
        //     return redirect()->intended('/dashboard');
        // }

        // // Coba login sebagai Dosen (berdasarkan NIDN)
        // $user = DosenModel::where('nidn', $identifier)->first();
        // if ($user && \Hash::check($password, $user->password)) {
        //     Auth::guard('dosen')->login($user);
        //     return redirect()->intended('/dashboard');
        // }

        // // Coba login sebagai Admin (berdasarkan username)
        // $user = AdminModel::where('username', $identifier)->first();
        // if ($user && \Hash::check($password, $user->password)) {
        //     Auth::guard('admin')->login($user);
        //     return redirect()->intended('/dashboard');
        // }

        // Jika semua gagal
        return back()
            ->with(['loginError' => 'Login gagal, periksa kembali Username dan password Anda.'])
            ->withInput();
        ;
    }

    public function confirmLogout()
    {
        // return 'hallo';
        return view('logout');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
