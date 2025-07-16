<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        $request->validate([
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = auth()->user();

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nama_foto = time() . "." . $foto->getClientOriginalExtension();
            $tujuan_upload = 'public/uploads/profile';
            $foto->move($tujuan_upload, $nama_foto);
            $user->foto = $nama_foto;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return Redirect::back()->with(['success' => 'Profil Berhasil Diupdate']);
    }
}
