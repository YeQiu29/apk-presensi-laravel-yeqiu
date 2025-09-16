<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        $karyawan = Karyawan::where('nik', $request->nik)->first();

        if (!$karyawan || !Hash::check($request->password, $karyawan->password)) {
            return response()->json([
                'success' => false,
                'message' => 'NIK atau Password Salah',
            ], 401);
        }

        $token = $karyawan->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $karyawan
        ]);
    }
}
