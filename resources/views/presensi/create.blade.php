@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Face Recognition Attendance</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        /* Webcam Container */
        
        .webcam-container {
            margin-bottom: 20px; /* Tambahkan ini */
            position: relative;
            width: 100%;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        
        .webcam-capture, 
        .webcam-capture video {
            display: block;
            width: 100% !important;
            height: auto !important;
        }
        
        /* Face Guide */
        .face-guide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            pointer-events: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .face-guide {
            width: 50%;
            max-width: 200px;
            aspect-ratio: 3/4;
            border: 2px dashed rgba(0, 255, 255, 0.5);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            position: relative;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
        }
        
        .face-guide::before,
        .face-guide::after {
            content: '';
            position: absolute;
            width: 12%;
            height: 6%;
            border: 1px solid rgba(0, 255, 255, 0.7);
            border-radius: 50%;
            top: 30%;
        }
        
        .face-guide::before {
            left: 25%;
        }
        
        .face-guide::after {
            right: 25%;
        }
        
        .face-guide .mouth-guide {
            position: absolute;
            width: 25%;
            height: 4%;
            border-bottom: 2px solid rgba(0, 255, 255, 0.7);
            border-radius: 0 0 50% 50%;
            bottom: 25%;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Scanning Effect */
        .scanning-line {
            position: absolute;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to bottom, 
                          rgba(0, 255, 255, 0), 
                          rgba(0, 255, 255, 0.8),
                          rgba(0, 255, 255, 0));
            z-index: 101;
            animation: scan 2.5s infinite ease-in-out;
            opacity: 0.7;
        }

        @keyframes scan {
            0% { top: 0; opacity: 0.7; }
            50% { opacity: 1; }
            100% { top: 100%; opacity: 0.7; }
        }

        .face-dots {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(0, 255, 255, 0.8);
            border-radius: 50%;
            z-index: 102;
        }

        #map { 
            height: 200px; 
        }
    </style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
<div class="row webcam-row" style="margin-top: 70px"> <!-- Tambahkan class webcam-row -->
    <div class="col">
        <input type="hidden" id="lokasi">
        <div class="webcam-container">
            <div class="webcam-capture"></div>
            <div class="face-guide-overlay">
                <div class="face-guide">
                    <div class="mouth-guide"></div>
                </div>
                <div class="scanning-line"></div>
                <!-- Titik-titik deteksi wajah -->
                <div class="face-dots" style="top: 30%; left: 25%;"></div>
                <div class="face-dots" style="top: 30%; right: 25%;"></div>
                <div class="face-dots" style="bottom: 25%; left: 50%; transform: translateX(-50%);"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1"> <!-- Tambahkan class button-row -->
    <div class="col">
        @if ($cek > 0)
        <button id="takeabsen" class="btn btn-danger btn-block">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Pulang
        </button>
        @else
        <button id="takeabsen" class="btn btn-primary btn-block">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Masuk
        </button>
        @endif
        
    </div>
</div>
<div class="row mt-1">
    <div class="col">
        <div id="map"></div>
    </div>
</div>

<audio id="notifikasi_in">
    <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
</audio>
<audio id="notifikasi_out">
    <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
</audio>
<audio id="notifikasi_radius">
    <source src="{{ asset('assets/sound/notifikasi_radius.mp3') }}" type="audio/mpeg">
</audio>
@endsection

@push('myscript')
    <script>

        var notifikasi_in = document.getElementById('notifikasi_in');
        var notifikasi_out = document.getElementById('notifikasi_out');
        var notifikasi_radius = document.getElementById('notifikasi_radius');
        Webcam.set({
            height:480,
            width:640,
            image_format:'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position){
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 14);
            var lokasi_kantor = "{{ $lok_kantor->lokasi_kantor }}";
            var lok = lokasi_kantor.split(",");
            var lat_kantor = lok[0];
            var long_kantor = lok[1];
            var radius = "{{ $lok_kantor->radius }}";
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([lat_kantor, long_kantor], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
        }

        function errorCallback(){

        }
        $("#takeabsen").click(function(e){
            Webcam.snap(function(uri){
                image = uri;
            });
            
            // Panggil API face recognition dulu
            $.ajax({
                type: 'POST',
                url: '/presensi/face-recognition',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    nik: "{{ Auth::guard('karyawan')->user()->nik }}"
                },
                success: function(respond) {
                    if(respond.status == 'success') {
                        // Jika face recognition sukses, lanjutkan proses absen
                        $.ajax({
                            type: 'POST',
                            url: '/presensi/store',
                            data: {
                                _token: "{{ csrf_token() }}",
                                image: image,
                                lokasi: $("#lokasi").val()
                            },
                            success: function(respond2) {
                                var status = respond2.split("|");
                                if(status[0] == "success") {
                                    if(status[2] == "in") {
                                        notifikasi_in.play();
                                        Swal.fire({
                                            title: 'Success !!!',
                                            text: respond.message + ' - ' + status[1],
                                            icon: 'success'
                                        });
                                    } else {
                                        notifikasi_out.play();
                                        Swal.fire({
                                            title: 'Success !!!',
                                            text: status[1],
                                            icon: 'success'
                                        });
                                    }
                                    setTimeout("location.href='/dashboard'", 3000);
                                } else {
                                    Swal.fire({
                                        title: 'Error !!!',
                                        text: status[1],
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error !!!',
                            text: respond.message,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error !!!',
                        text: 'Gagal melakukan face recognition',
                        icon: 'error'
                    });
                }
            });
        });
    </script>
@endpush