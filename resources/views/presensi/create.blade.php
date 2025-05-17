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
        .webcam-capture,
        .webcam-capture video{
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;

        }
        #map { 
            height: 200px; 
        }

    </style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection
@section('content')
<div class="row"style="margin-top: 70px">
    <div class="col">
        <input type="hidden" id="lokasi">
        <div class="webcam-capture"></div>
    </div>
</div>
<div class="row">
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
<div class="row mt-2">
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