<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class KonfigurasiController extends Controller
{
    public function lokasikantor()
    {
        $lokasi = DB::table('konfigurasi_lokasi')->get();
        return view('konfigurasi.lokasikantor', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $nama_lokasi = $request->nama_lokasi;
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        try {
            DB::table('konfigurasi_lokasi')->insert([
                'nama_lokasi' => $nama_lokasi,
                'lokasi_kantor' => $lokasi_kantor,
                'radius' => $radius
            ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $lokasi = DB::table('konfigurasi_lokasi')->where('id', $id)->first();
        return view('konfigurasi.editlokasi', compact('lokasi'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $nama_lokasi = $request->nama_lokasi;
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        try {
            DB::table('konfigurasi_lokasi')->where('id', $id)->update([
                'nama_lokasi' => $nama_lokasi,
                'lokasi_kantor' => $lokasi_kantor,
                'radius' => $radius
            ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('konfigurasi_lokasi')->where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
    }

    // Metode untuk profil admin tetap ada
    public function editprofileadmin()
    {
        $admin = auth()->user();
        return view('konfigurasi.editprofileadmin', compact('admin'));
    }

    public function updateprofileadmin(Request $request)
    {
        $admin = auth()->user();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        try {
            $admin->name = $request->name;
            $admin->email = $request->email;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = date('Y-m-d') . '-' . $photo->getClientOriginalName();
                $photo->storeAs('public/uploads/profile', $filename);

                // Delete old photo
                if ($admin->photo) {
                    Storage::delete('public/uploads/profile/' . $admin->photo);
                }

                $admin->photo = $filename;
            }

            if ($request->password) {
                if (!Hash::check($request->old_password, $admin->password)) {
                    return Redirect::back()->with(['warning' => 'Password Lama Salah']);
                }
                $admin->password = bcrypt($request->password);
            }

            $admin->save();

            return Redirect::back()->with(['success' => 'Profil Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Profil Gagal Diupdate']);
        }
    }

    public function saldocuti(Request $request)
    {
        $query = DB::table('karyawan')->orderBy('nama_lengkap');

        if ($request->has('nama_karyawan') && !empty($request->nama_karyawan)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        $karyawan = $query->paginate(5);

        return view('konfigurasi.saldocuti', compact('karyawan'));
    }

    public function updatesaldocuti(Request $request)
    {
        $nik = $request->nik;
        $saldo_cuti = $request->saldo_cuti;

        try {
            DB::table('karyawan')->where('nik', $nik)->update(['saldo_cuti' => $saldo_cuti]);
            return Redirect::back()->with(['success' => 'Saldo Cuti Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Saldo Cuti Gagal Diupdate']);
        }
    }
}