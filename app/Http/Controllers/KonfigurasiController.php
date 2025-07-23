<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KonfigurasiController extends Controller
{
    public function lokasikantor()
    {
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request){
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasi')->where('id', 1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diupdate']);
        }
    }

    public function editprofileadmin()
    {
        return view('konfigurasi.editprofileadmin');
    }

    public function updateprofileadmin(Request $request)
    {
        $user = Auth::guard('user')->user();
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if ($request->hasFile('foto')) {
            $foto = $user->id . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $user->foto;
        }

        $user->name = $name;
        $user->email = $email;
        if (!empty($password)) {
            if ($password === $request->password_confirmation) {
                $user->password = Hash::make($password);
            } else {
                return redirect()->back()->with('error', 'Password dan konfirmasi password tidak cocok.');
            }
        }
        $user->foto = $foto;

        try {
            $user->save();
            if ($request->hasFile('foto')) {
                $folderPath = "public/assets/img/admin_profile/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil.');
        }
    }
}
