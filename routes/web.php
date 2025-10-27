<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PendaftaranWajahController;
use Illuminate\Support\Facades\Route;



Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']); 
});

Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']); 
});

Route::middleware(['auth:karyawan'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //presensi
    Route::get('/presensi/create', [PresensiController::class,'create']);
    Route::post('/presensi/face-recognition', [PresensiController::class, 'faceRecognition']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile
    Route::get('/editprofile',[PresensiController::class,'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);

    //Histori
    Route::get('/presensi/histori', [PresensiController::class,'histori']);
    Route::post('/gethistori', [PresensiController::class,'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class,'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class,'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class,'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);
});

Route::middleware(['auth:user'])->group(function(){
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    //Karyawan 
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);

    //Departemen
    Route::get('/departemen', [DepartemenController::class, 'index']);
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    //Monitoring Presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);

    //Konfigurasi Lokasi Kantor
    Route::get('/konfigurasi/lokasikantor',[KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/lokasi/store', [KonfigurasiController::class, 'store']);
    Route::post('/konfigurasi/lokasi/edit', [KonfigurasiController::class, 'edit']);
    Route::post('/konfigurasi/lokasi/{id}/update', [KonfigurasiController::class, 'update']);
    Route::post('/konfigurasi/lokasi/{id}/delete', [KonfigurasiController::class, 'delete']);
    Route::get('/konfigurasi/saldocuti', [KonfigurasiController::class, 'saldocuti']);
    Route::post('/konfigurasi/saldocuti/update', [KonfigurasiController::class, 'updatesaldocuti']);

    //Edit Profile Admin
    Route::get('/konfigurasi/editprofileadmin', [KonfigurasiController::class, 'editprofileadmin']);
    Route::post('/konfigurasi/updateprofileadmin', [KonfigurasiController::class, 'updateprofileadmin']);

    //Pendaftaran Wajah
    Route::get('/admin/pendaftaran-wajah', [PendaftaranWajahController::class, 'index']);
    Route::post('/admin/pendaftaran-wajah/upload', [PendaftaranWajahController::class, 'upload']);

    //Kantor Cabang
    Route::get('/cabang', [App\Http\Controllers\CabangController::class, 'index']);
    Route::post('/cabang/store', [App\Http\Controllers\CabangController::class, 'store']);
    Route::post('/cabang/edit', [App\Http\Controllers\CabangController::class, 'edit']);
    Route::post('/cabang/{kode_cabang}/update', [App\Http\Controllers\CabangController::class, 'update']);
    Route::post('/cabang/{kode_cabang}/delete', [App\Http\Controllers\CabangController::class, 'delete']);

    Route::get('/infophp', function () {
    phpinfo();
});
});