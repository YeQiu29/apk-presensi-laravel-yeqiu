<?php

namespace App\Http\Controllers;

use App\Models\Pengajuanizin;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Foundation\Console\StorageLinkCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use League\Flysystem\StorageAttributes;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $all_locations = DB::table('konfigurasi_lokasi')->get();
        return view('presensi.create', compact('cek', 'all_locations'));
    }

    public function faceRecognition(Request $request) 
    {
        // Validasi input
        $request->validate([
            'nik' => 'required',
            'image' => 'required'
        ]);

        // Simpan sementara di storage
        $tempPath = 'public/uploads/temp/';
        $formatName = $request->nik . "-" . date('Y-m-d') . "-check";
        $imageParts = explode(";base64", $request->image);
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = $formatName . ".png";
        $tempFile = $tempPath . $fileName;
        
        FacadesStorage::put($tempFile, $imageBase64);

        // Kirim ke API Python
        $response = Http::attach(
            'file', 
            file_get_contents(storage_path('app/' . $tempFile)), 
            $fileName
        )->post('https://aacffabcdebc.ngrok-free.app/recognize/', [
            'nik_input' => $request->nik
        ]);

        // Hapus file temp
        FacadesStorage::delete($tempFile);

        if ($response->successful()) {
            $result = $response->json();
            
            if ($result['nik_matched']) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result['message'],
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error, wajah tidak terdeteksi'
                ], 400);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal terhubung ke API Face Recognition'
        ], 500);
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $all_offices = DB::table('konfigurasi_lokasi')->get();

        $absen_di_lokasi = false;

        foreach ($all_offices as $office) {
            $lok = explode(",", $office->lokasi_kantor);
            $latitudekantor = $lok[0];
            $longitudekantor = $lok[1];

            $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
            $radius = round($jarak["meters"]);

            if ($radius <= $office->radius) {
                $absen_di_lokasi = true;
                break;
            }
        }

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64",$image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;
        
        if(!$absen_di_lokasi){
            echo "error|Maaf Anda Berada di Luar Radius Kantor|radius";
        }else{
            if($cek > 0){
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if($update){
                    echo "success|Terima Kasih, Hati-Hati di Jalan|out";
                    FacadesStorage::put($file, $image_base64);
                } else {
                    echo "error|Maaf, Absen Gagal|out";
                }
            }else{
                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($data);
                if($simpan){
                    echo "success|Selamat Bekerja|in";
                    FacadesStorage::put($file, $image_base64);
                } else {
                    echo "error|Maaf, Absen Gagal|in";
                }

            }

        }

    }
     //Menghitung Jarak
     function distance($lat1, $lon1, $lat2, $lon2)
     {
            $theta = $lon1 - $lon2;
            $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
            $miles = acos($miles);
            $miles = rad2deg($miles);
            $miles = $miles * 60 * 1.1515;
            $feet = $miles * 5280;
            $yards = $feet / 3;
            $kilometers = $miles * 1.609344;
            $meters = $kilometers * 1000;
            return compact('meters');
     }

     public function editprofile()
     {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
     }
     public function updateprofile(Request $request){
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        if($request->hasFile('foto')){
            $foto = $nik . "." .$request->file('foto')->getClientOriginalExtension(); 
        } else {
            $foto = $karyawan->foto;
        }
        if(empty($request->password)){
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }
        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update){
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal di Update']);;
        }
     }
     public function histori()
     {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
     }

     public function gethistori(Request $request){
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
     }

     public function izin()
     {
        $nik = Auth::guard('karyawan')->user()->nik;
        $saldo_cuti = Auth::guard('karyawan')->user()->saldo_cuti;
        $dataizin = DB::table('pengajuan_izin')->where('nik',$nik)->get();
        return view('presensi.izin', compact('dataizin','saldo_cuti'));
     }

     public function buatizin()
     {
        $saldo_cuti = Auth::guard('karyawan')->user()->saldo_cuti;
        return view('presensi.buatizin', compact('saldo_cuti'));
     }
     public function storeizin(Request $request)
     {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        if ($status == 'i') {
            $saldo_cuti = Auth::guard('karyawan')->user()->saldo_cuti;
            if ($saldo_cuti <= 0) {
                return redirect('/presensi/izin')->with(['error' => 'Maaf, jatah cuti anda telah habis']);
            }
        }

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
     ];

     $simpan = DB::table('pengajuan_izin')->insert($data);

     if($simpan){
        return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
     } else {
        return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
     }

     }

     public function monitoring() 
     {
        return view('presensi.monitoring');
     }

     public function getpresensi(Request $request) 
     {
        $tanggal  = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*','nama_lengkap','nama_dept')
            ->join('karyawan','presensi.nik','=','karyawan.nik')
            ->join('departemen','karyawan.kode_dept','=','departemen.kode_dept')
            ->where('tgl_presensi',$tanggal)
            ->get();

            return view('presensi.getpresensi', compact('presensi'));
     }

     public function tampilkanpeta(Request $request)
     {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->first();
        return view('presensi.showmap', compact('presensi'));
     }

     public function laporan()
     {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", 
        "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
     }

     public function cetaklaporan(Request $request)
     {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", 
        "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();

        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Karyawan $time.xls");
            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan' , 'karyawan' , 'presensi'));
        }
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan' , 'karyawan' , 'presensi'));
     }

     public function rekap()
     {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", 
        "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
     }

     public function cetakrekap(Request $request)
     {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", 
        "November", "Desember"];
        $rekap = DB::table('presensi')
        ->selectRaw('presensi.nik,nama_lengkap,
            MAX(if(DAY(tgl_presensi) = 1,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_1,
            MAX(if(DAY(tgl_presensi) = 2,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_2,
            MAX(if(DAY(tgl_presensi) = 3,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_3,
            MAX(if(DAY(tgl_presensi) = 4,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_4,
            MAX(if(DAY(tgl_presensi) = 5,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_5,
            MAX(if(DAY(tgl_presensi) = 6,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_6,
            MAX(if(DAY(tgl_presensi) = 7,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_7,
            MAX(if(DAY(tgl_presensi) = 8,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_8,
            MAX(if(DAY(tgl_presensi) = 9,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_9,
            MAX(if(DAY(tgl_presensi) = 10,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_10,
            MAX(if(DAY(tgl_presensi) = 11,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_11,
            MAX(if(DAY(tgl_presensi) = 12,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_12,
            MAX(if(DAY(tgl_presensi) = 13,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_13,
            MAX(if(DAY(tgl_presensi) = 14,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_14,
            MAX(if(DAY(tgl_presensi) = 15,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_15,
            MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_16,
            MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_17,
            MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_18,
            MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_19,
            MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_20,
            MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_21,
            MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_22, 
            MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_23,
            MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_24,
            MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_25,
            MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_26,
            MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_27,
            MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_28,
            MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_29,
            MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_30,
            MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_31')
            
            ->join('karyawan','presensi.nik','=','karyawan.nik')
            ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
            ->groupByRaw('presensi.nik,nama_lengkap')
            ->get();
            
        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }
        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
     }

     public function izinsakit(Request $request)
     {
        $query = Pengajuanizin::query();
        $query->select('id','tgl_izin','pengajuan_izin.nik','nama_lengkap','jabatan','status','status_approved','keterangan');
        $query->join('karyawan','pengajuan_izin.nik','=','karyawan.nik');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like','%' . $request->nama_lengkap . '%');
        }

        if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin','desc');
        $izinsakit = $query->paginate(5); // halaman website admin menu Pengajuan Izin / Sakit
        $izinsakit->appends($request->all());
        return view('presensi.izinsakit', compact('izinsakit'));
     }

     public function approveizinsakit(Request $request){
        $status_approved = $request->status_approved;
        $id_izinsakit_form = $request->id_izinsakit_form;

        DB::beginTransaction();
        try {
            $pengajuan = DB::table('pengajuan_izin')->where('id', $id_izinsakit_form)->first();

            if (!$pengajuan) {
                throw new \Exception('Data pengajuan tidak ditemukan');
            }

            // Lakukan update status approval
            DB::table('pengajuan_izin')->where('id', $id_izinsakit_form)->update([
                'status_approved' => $status_approved
            ]);

            // Cek jika status adalah 'approved' (1) dan jenisnya adalah 'izin' (i)
            if ($status_approved == 1 && $pengajuan->status == 'i') {
                $karyawan = DB::table('karyawan')->where('nik', $pengajuan->nik)->first();
                if ($karyawan && $karyawan->saldo_cuti > 0) {
                    DB::table('karyawan')->where('nik', $pengajuan->nik)->decrement('saldo_cuti');
                }
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Data berhasil diupdate']);

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data gagal diupdate: ' . $e->getMessage()]);
        }
     }

     public function batalkanizinsakit($id)
     {
        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_approved' => 0
        ]);
        if($update){
            return Redirect::back()->with(['success' => 'Data berhasil di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal di Update']);
        }
     }

     public function cekpengajuanizin(Request $request)
     {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
     }
}
