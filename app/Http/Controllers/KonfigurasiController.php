<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        // Logika untuk edit profil admin
    }

    public function updateprofileadmin(Request $request)
    {
        // Logika untuk update profil admin
    }
}