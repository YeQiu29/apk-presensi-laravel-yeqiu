<style>
    #map { 
        height: 250px; 
        width: 100%;
    }
</style>

<div id="map"></div>

<script>
    // 1. Ambil data lokasi user
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

    // 3. TAMPILKAN MARKER (PIN) LANGSUNG
    // Marker langsung ditambahkan ke peta saat dimuat.
    // .bindPopup() akan membuat popup nama muncul otomatis HANYA saat marker diklik.
    var marker = L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup("{{ $presensi->nama_lengkap }}");

    // 4. Tampilkan Radius Kantor (Lingkaran Merah)
    var lokasi_kantor = "{{ $lokasi_kantor->lokasi_kantor }}";
    var lok_kantor = lokasi_kantor.split(",");
    var lat_kantor = lok_kantor[0];
    var long_kantor = lok_kantor[1];
    var radius = "{{ $lokasi_kantor->radius }}";

    var circle = L.circle([lat_kantor, long_kantor], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: radius
    }).addTo(map);

    // 5. Fix Bug Peta Abu-abu (PENTING)
    // Tetap diperlukan karena peta ada di dalam Modal
    setTimeout(function() {
        map.invalidateSize();
        map.panTo([latitude, longitude]); // Geser kamera pas ke marker
    }, 800);

</script>