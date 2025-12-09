<style>
    #map { 
        height: 250px; 
        width: 100%;
    }
</style>

<div id="map"></div>

<script>
    // 1. Ambil data lokasi user (Masuk atau Pulang)
    var lokasi = "{{ $presensi->lokasi_out ?? $presensi->lokasi_in }}";
    var lok = lokasi.split(",");
    var latitude = lok[0];
    var longitude = lok[1];

    // 2. Inisialisasi Peta
    var map = L.map('map').setView([latitude, longitude], 13);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // 3. TAMPILKAN MARKER USER (PIN)
    var marker = L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup("{{ $presensi->nama_lengkap }}");

    // 4. TAMPILKAN RADIUS SEMUA KANTOR CABANG
    // Kita looping data $cabang yang dikirim dari controller
    @foreach ($cabang as $loc)
        var lokasi_kantor_{{ $loop->iteration }} = "{{ $loc->lokasi_kantor }}";
        var split_loc_{{ $loop->iteration }} = lokasi_kantor_{{ $loop->iteration }}.split(",");
        var lat_kantor_{{ $loop->iteration }} = split_loc_{{ $loop->iteration }}[0];
        var long_kantor_{{ $loop->iteration }} = split_loc_{{ $loop->iteration }}[1];
        var radius_{{ $loop->iteration }} = "{{ $loc->radius }}";

        L.circle([lat_kantor_{{ $loop->iteration }}, long_kantor_{{ $loop->iteration }}], {
            color: 'red',       // Warna garis
            fillColor: '#f03',  // Warna isi
            fillOpacity: 0.5,   // Transparansi
            radius: radius_{{ $loop->iteration }}
        }).addTo(map)
        .bindPopup("{{ $loc->nama_lokasi }}"); 
    @endforeach

    // 5. FIX BUG PETA (Agar tidak abu-abu dan posisi pas)
    setTimeout(function() {
        map.invalidateSize();
        map.panTo([latitude, longitude]); 
    }, 800);

</script>