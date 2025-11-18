<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    /**
     * Login user dan return API token
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = UserModel::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        // Generate API token menggunakan Sanctum
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'level_id' => $user->level_id,
                    'nama' => $user->nama,
                    'role' => $user->getRole(),
                    'role_name' => $user->getRoleName(),
                ],
                'token' => $token,
            ]
        ], 200);
    }

    /**
     * Get user yang sedang login
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diambil',
            'data' => [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'level_id' => $user->level_id,
                'nama' => $user->nama,
                'role' => $user->getRole(),
                'role_name' => $user->getRoleName(),
                'level' => $user->level,
            ]
        ], 200);
    }

    /**
     * Logout user (delete token)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi',
            ], 401);
        }

        // Delete semua token user
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ], 200);
    }

    /**
     * Logout dari device saat ini saja (delete current token)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutCurrent(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi',
            ], 401);
        }

        // Delete hanya token yang sedang digunakan
        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout dari device ini berhasil',
        ], 200);
    }
}
