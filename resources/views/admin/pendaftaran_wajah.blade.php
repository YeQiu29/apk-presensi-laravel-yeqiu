@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Pendaftaran Wajah Karyawan
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Formulir Pendaftaran Wajah</h3>
                    </div>
                    <div class="card-body">
                        {{-- Container untuk notifikasi --}}
                        <div id="notification" class="alert" style="display: none;"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="karyawan" class="form-label">Pilih Karyawan</label>
                                    <select name="karyawan" id="karyawan" class="form-select">
                                        <option value="">-- Pilih Karyawan --</option>
                                        @foreach ($karyawan as $item)
                                            <option value="{{ $item->nik }}" data-nama="{{ $item->nama_lengkap }}">{{ $item->nik }} - {{ $item->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-primary mt-3" id="start-capture" disabled>Mulai Capture</button>
                            </div>
                            <div class="col-md-6">
                                <video id="video" width="100%" autoplay muted playsinline style="border-radius: 8px; border: 1px solid #ccc; transform: scaleX(-1);"></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                                <p class="mt-2">Gambar ditangkap: <span id="capture-count">0</span>/50</p>
                                <div class="progress" style="display: none; height: 20px;">
                                    <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureCountSpan = document.getElementById('capture-count');
        const startCaptureButton = document.getElementById('start-capture');
        const karyawanSelect = document.getElementById('karyawan');
        const notification = document.getElementById('notification');
        const progressBar = document.getElementById('progress-bar');
        const progressContainer = document.querySelector('.progress');

        let capturedImages = [];
        let stream;
        let captureInterval;

        // Mengaktifkan tombol 'Mulai Capture' hanya jika karyawan dipilih
        karyawanSelect.addEventListener('change', () => {
            startCaptureButton.disabled = !karyawanSelect.value;
        });

        // Event listener untuk tombol 'Mulai Capture'
        startCaptureButton.addEventListener('click', async () => {
            if (!karyawanSelect.value) {
                showNotification('Silakan pilih karyawan terlebih dahulu.', 'warning');
                return;
            }

            // Nonaktifkan UI untuk mencegah input ganda
            startCaptureButton.disabled = true;
            karyawanSelect.disabled = true;
            resetCaptureState();
            showNotification('Mempersiapkan kamera...', 'info');
            
            try {
                // Meminta akses ke webcam
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                video.play(); // Memastikan video diputar

                // Tunggu hingga video siap untuk mendapatkan dimensi yang benar
                video.onloadedmetadata = () => {
                    showNotification('Arahkan wajah ke kamera. Pengambilan gambar akan dimulai.', 'info');
                    startImageCapture();
                };

            } catch (err) {
                console.error("Error mengakses webcam: ", err);
                showNotification('Tidak dapat mengakses webcam. Pastikan Anda memberikan izin.', 'danger');
                resetUI();
            }
        });

        function startImageCapture() {
            captureInterval = setInterval(() => {
                if (capturedImages.length >= 50) {
                    clearInterval(captureInterval);
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                    showNotification('Pengambilan gambar selesai. Mengirim ke server...', 'info');
                    uploadImages();
                    return;
                }

                // Menggambar frame video ke canvas
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                // Balikkan canvas secara horizontal agar sesuai dengan tampilan video
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Konversi canvas ke Blob
                canvas.toBlob(blob => {
                    if (blob) {
                        capturedImages.push(blob);
                        updateProgress();
                    }
                }, 'image/jpeg', 0.9); // Kualitas gambar 90%
            }, 200); // Interval capture dipercepat menjadi 200ms
        }

        function uploadImages() {
            const selectedOption = karyawanSelect.options[karyawanSelect.selectedIndex];
            const nik = selectedOption.value;
            const nama = selectedOption.getAttribute('data-nama');

            const formData = new FormData();
            formData.append('nik', nik);
            formData.append('nama', nama);
            capturedImages.forEach((blob, index) => {
                formData.append('images[]', blob, `image_${index}.jpg`);
            });

            // Kirim data ke server
            fetch('/admin/pendaftaran-wajah/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(async response => {
                // *** BLOK PENANGANAN ERROR YANG DIPERBARUI ***
                const contentType = response.headers.get("content-type");
                // Jika respons OK dan tipenya JSON, langsung proses
                if (response.ok && contentType && contentType.includes("application/json")) {
                    return response.json();
                }

                // Jika tidak, tangani sebagai error. Baca body sebagai teks.
                const responseText = await response.text();
                let errorData;
                try {
                    // Coba parse sebagai JSON, mungkin saja valid
                    errorData = JSON.parse(responseText);
                } catch (e) {
                    // Jika gagal parse, berarti ini HTML atau teks biasa.
                    // Buat objek error kustom.
                    errorData = { 
                        message: "Server mengembalikan respons yang tidak valid (kemungkinan HTML error dari PHP).",
                        details: responseText.substring(0, 250) + '...' // Tampilkan cuplikan error
                    };
                }
                
                // Lemparkan error agar bisa ditangkap oleh .catch()
                const error = new Error(errorData.message || 'Server Error');
                error.response = errorData;
                throw error;
            })
            .then(data => {
                // Blok ini hanya akan berjalan jika respons sukses (status 2xx)
                if (data.success) {
                    showNotification(`Pendaftaran wajah untuk ${nama} berhasil!`, 'success');
                } else {
                    showNotification(`Pendaftaran gagal: ${data.message || 'Terjadi kesalahan di server.'}`, 'danger');
                }
            })
            .catch(error => {
                console.error('Full Error Object:', error);
                let errorMessage = 'Terjadi kesalahan yang tidak diketahui.';
                
                // Cek apakah ada pesan error spesifik dari validasi Laravel
                if (error.response && error.response.errors) {
                    const firstErrorKey = Object.keys(error.response.errors)[0];
                    errorMessage = error.response.errors[firstErrorKey][0];
                } else if (error.response && error.response.message) {
                    errorMessage = error.response.message;
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                // Jika ada detail (cuplikan HTML), tambahkan ke pesan
                if (error.response && error.response.details) {
                    errorMessage += ` Detail: ${error.response.details}`;
                }

                showNotification(errorMessage, 'danger');
            })
            .finally(() => {
                resetUI();
            });
        }

        function updateProgress() {
            captureCountSpan.textContent = capturedImages.length;
            const progress = (capturedImages.length / 50) * 100;
            progressBar.style.width = `${progress}%`;
        }

        function showNotification(message, type) {
            notification.textContent = message;
            notification.className = `alert alert-${type}`;
            notification.style.display = message ? 'block' : 'none';
        }

        function resetCaptureState() {
            capturedImages = [];
            captureCountSpan.textContent = '0';
            progressContainer.style.display = 'block';
            progressBar.style.width = '0%';
        }
        
        function resetUI() {
            startCaptureButton.disabled = false;
            karyawanSelect.disabled = false;
            progressContainer.style.display = 'none';
            progressBar.style.width = '0%';
            capturedImages = [];
            if (captureInterval) {
                clearInterval(captureInterval);
            }
        }
    });
</script>
@endsection
