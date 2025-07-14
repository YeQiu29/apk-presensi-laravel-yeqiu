<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Disarankan untuk ditambahkan

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        // Menggunakan guard 'karyawan' sesuai kode asli Anda
        $credentials = [
            'nik' => $request->nik,
            'password' => $request->password
        ];

        if (Auth::guard('karyawan')->attempt($credentials)) {
            // Jika otentikasi berhasil
            $request->session()->regenerate();
            return response()->json([
                'status' => 'success',
                'redirect_url' => '/dashboard' // URL tujuan setelah login sukses
            ]);
        } else {
            // Jika otentikasi gagal
            return response()->json([
                'status' => 'error',
                'message' => 'NIK / Password Salah'
            ], 401); // 401 adalah status 'Unauthorized'
        }
    }

    public function proseslogout()
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }
    }

    public function proseslogoutadmin()
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }

    public function prosesloginadmin(Request $request)
    {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/panel/dashboardadmin');
        } else {
            return redirect('/panel')->with(['warning' => 'Username atau Password Salah']);
        }
    }
}
