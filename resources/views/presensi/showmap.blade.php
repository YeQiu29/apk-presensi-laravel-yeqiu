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

    // --- PERUBAHAN UTAMA DISINI ---
    
    // A. Buat "Titik Biru" (Pengganti Marker Awal)
    // CircleMarker posisinya pasti presisi di tengah koordinat (tidak akan meleset)
    var titikBiru = L.circleMarker([latitude, longitude], {
        radius: 8,          // Ukuran titik
        fillColor: "#3388ff", // Warna biru standar Leaflet
        color: "white",     // Garis tepi putih biar kontras
        weight: 2,
        opacity: 1,
        fillOpacity: 1      // Solid (tidak transparan)
    }).addTo(map);

    // B. Logic: Ketika Titik Biru diklik -> Munculkan Marker (Pin) & Popup
    var markerLayer = null; // Variabel penampung marker
    
    titikBiru.on('click', function() {
        // Cek dulu, kalau marker sudah ada, jangan dibuat lagi (toggle)
        if (markerLayer) {
            map.removeLayer(markerLayer);
            markerLayer = null;
        } else {
            // Buat Marker (Pin) tepat di posisi yang sama
            markerLayer = L.marker([latitude, longitude])
                .addTo(map)
                .bindPopup("{{ $presensi->nama_lengkap }}")
                .openPopup();
        }
    });

    // -----------------------------

    // 3. Tampilkan Radius Kantor (Lingkaran Merah)
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

    // 4. Fix Bug Peta Abu-abu & Center
    setTimeout(function() {
        map.invalidateSize();
        map.panTo([latitude, longitude]);
    }, 800);

</script>