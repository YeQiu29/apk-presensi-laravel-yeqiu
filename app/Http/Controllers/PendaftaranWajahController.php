<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // <-- Penting untuk logging

class PendaftaranWajahController extends Controller
{
    /**
     * Menampilkan halaman pendaftaran wajah.
     * Mengambil semua data karyawan untuk ditampilkan di dropdown.
     */
    public function index()
    {
        $karyawan = Karyawan::all();
        return view('admin.pendaftaran_wajah', compact('karyawan'));
    }

    /**
     * Menerima request unggahan gambar, memvalidasi, dan meneruskannya ke API Python.
     * Dilengkapi dengan logging dan penanganan error koneksi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        Log::info('Menerima request pendaftaran wajah...');

        // 1. Validasi input dari form untuk memastikan data lengkap dan benar.
        $request->validate([
            'nik' => 'required',
            'nama' => 'required|string',
            'images' => 'required|array|min:50|max:50',
            'images.*' => 'image',
        ]);

        Log::info('Validasi berhasil untuk NIK: ' . $request->nik);

        // 2. Siapkan data untuk dikirim dalam format multipart/form-data.
        $multiparts = [
            ['name' => 'nik', 'contents' => $request->nik],
            ['name' => 'nama', 'contents' => strtoupper($request->nama)], // Sesuai permintaan API Python
        ];

        foreach ($request->file('images') as $image) {
            $multiparts[] = [
                'name'     => 'files',
                'contents' => fopen($image->getPathname(), 'r'),
                'filename' => $image->getClientOriginalName(),
            ];
        }

        try {
            Log::info('Mencoba mengirim data ke API Python...');
            
            // 3. Kirim data ke API Python.
            // !!! PENTING: GANTI DENGAN URL NGROK ANDA YANG AKTIF !!!
            $response = Http::timeout(300) // Timeout 5 menit untuk proses upload & training
                ->asMultipart()
                ->post('https://9efed2fb8ad9.ngrok-free.app/upload/', $multiparts);

            Log::info('Menerima respons dari API Python. Status: ' . $response->status());

            // 4. Proses respons dari API Python.
            if ($response->successful()) {
                // Jika API merespons dengan sukses (status 2xx), teruskan responsnya.
                return $response->json();
            } else {
                // Jika API merespons dengan error (status 4xx atau 5xx).
                Log::error('API Python mengembalikan error: ' . $response->body());
                return response()->json(['success' => false, 'message' => 'API Error: ' . $response->body()], $response->status());
            }

        } catch (\Exception $e) {
            // 5. Tangani jika koneksi ke API Python GAGAL TOTAL (misal: timeout, URL salah, server down).
            Log::error('EXCEPTION saat koneksi ke API Python: ' . $e->getMessage());
            
            // Kembalikan respons JSON yang bersih ke browser, bukan halaman error HTML.
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke layanan Pendaftaran Wajah. Periksa koneksi atau URL API. Error: ' . $e->getMessage()
            ], 503); // 503 Service Unavailable adalah status yang tepat.
        }
    }
}
