@extends('layouts.presensi')
@section('content')
<style>
    .logout {
        position: absolute;
        color: white;
        font-size: 30px;
        text-decoration: none;
        right: 8px;
    }

    .logout {
        color: white;
    }
</style>
<div class="section" id="user-section">
    <a href="/proseslogout" class="logout">
        <ion-icon name="exit-outline"></ion-icon>
    </a>
    <div id="user-detail">
        <div class="avatar">
            @if(!empty(Auth::guard('karyawan')->user()->foto))
            @php
                $path = Storage::url('uploads/karyawan/'.Auth::guard('karyawan')->user()->foto);
            @endphp
            <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height: 70px">
            @else
            <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
        </div>
    </div>
</div>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/editprofile" class="green" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/izin" class="danger" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Cuti</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/histori" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Histori</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="#" class="orange" style="font-size: 40px;" data-toggle="modal" data-target="#locationModal">
                            <ion-icon name="location"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        Lokasi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini !== null)
                                @php
                                    $path = Storage::url('uploads/absensi/'.$presensihariini->foto_in);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensihariini !== null ? $presensihariini->jam_in: 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini !== null && $presensihariini->jam_out !== null)
                                @php
                                    $path = Storage::url('uploads/absensi/'.$presensihariini->foto_out);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensihariini !== null && $presensihariini->jam_out !== null ? $presensihariini->jam_out: 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rekappresensi">
        <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }} </h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
                        <span class="badge bg-danger" style="position: absolute; top: 3px; right: 10px; font-size: 0.6rem; z-index: 999">{{ $rekappresensi->jmlhadir }}</span>
                        <ion-icon name="accessibility-outline" style="font-size: 1.6rem;" class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight: 500">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
                        <span class="badge bg-danger" style="position: absolute; top: 3px; right: 10px; font-size: 0.6rem; z-index: 999">{{ $rekapizin->jmlizin }}</span>
                        <ion-icon name="newspaper-outline" style="font-size: 1.6rem;" class="text-success mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight: 500">Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
                        <span class="badge bg-danger" style="position: absolute; top: 3px; right: 10px; font-size: 0.6rem; z-index: 999">{{ $rekapizin->jmlsakit }}</span>
                        <ion-icon name="medkit-outline" style="font-size: 1.6rem;" class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight: 500">Sakit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
                        <span class="badge bg-danger" style="position: absolute; top: 3px; right: 10px; font-size: 0.6rem; z-index: 999">{{ $rekappresensi->jmlterlambat }}</span>
                        <ion-icon name="alarm-outline" style="font-size: 1.6rem;" class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight: 500">Telat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Bulan Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        Leaderboard
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($historibulanini as $d)
                    @php
                        $path = Storage::url('uploads/absensi/'.$d->foto_in);
                    @endphp
                    <li>
                        <div class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="finger-print-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</div>
                                <span class="badge badge-success">{{ $d->jam_in }}</span>
                                <span class="badge badge-danger">{{ $d->jam_out !== null ? $d->jam_out : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboard as $d)
                    <li>
                        <div class="item">
                            <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                            <div class="in">
                                <div>
                                    <b>{{ $d->nama_lengkap }}</b><br>
                                    <small class="text-muted">{{ $d->jabatan }}</small>
                                </div>
                                <span class="badge {{ $d->jam_in < "07:00" ? "bg-success" : "bg-danger" }}">
                                    {{ $d->jam_in }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                    
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Modal Lokasi -->
<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">Lokasi Anda Saat Ini</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 300px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    var map = null; // Declare map variable globally or in a scope accessible by the modal event

    $('#locationModal').on('shown.bs.modal', function () {
        if (map !== null) {
            map.remove(); // Remove existing map if it was initialized before
        }

        function initializeMap() {
            // Initialize map
            map = L.map('map').setView([0, 0], 13); // Default view, will be updated by geolocation

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    var accuracy = position.coords.accuracy;

                    var latlng = L.latLng(lat, lng);

                    map.setView(latlng, 16); // Set map view to user's location with higher zoom

                    L.marker(latlng).addTo(map)
                        .bindPopup("Lokasi Anda saat ini. Akurasi: " + accuracy + " meter.")
                        .openPopup();

                    L.circle(latlng, accuracy).addTo(map);

                    // Invalidate size to ensure map renders correctly after modal is shown
                    setTimeout(function() { // Keep this setTimeout for invalidateSize
                        map.invalidateSize();
                    }, 250);
                }, function(error) {
                    console.error("Error getting location: ", error);
                    alert("Tidak dapat mengambil lokasi Anda. Pastikan Anda mengizinkan akses lokasi.");
                    map.setView([-6.2088, 106.8456], 13); // Default to Jakarta if location fails
                    L.marker([-6.2088, 106.8456]).addTo(map)
                        .bindPopup("Lokasi default (Jakarta) karena gagal mengambil lokasi Anda.")
                        .openPopup();
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
            } else {
                alert("Geolocation tidak didukung oleh browser Anda.");
                map.setView([-6.2088, 106.8456], 13); // Default to Jakarta if geolocation not supported
                L.marker([-6.2088, 106.8456]).addTo(map)
                    .bindPopup("Lokasi default (Jakarta) karena Geolocation tidak didukung.")
                    .openPopup();
            }
        }

        // Check if L is defined, if not, wait and try again
        var checkLeafletReady = setInterval(function() {
            if (typeof L !== 'undefined') {
                clearInterval(checkLeafletReady);
                initializeMap();
            }
        }, 100); // Check every 100ms
    });

    // Clear map when modal is hidden to prevent issues on subsequent openings
    $('#locationModal').on('hidden.bs.modal', function () {
        if (map !== null) {
            map.remove();
            map = null;
        }
    });
</script>
@endpush