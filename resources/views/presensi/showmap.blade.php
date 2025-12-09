<style>
    #map { 
        height: 250px; 
        width: 100%;
    }

    /* CSS untuk membuat Titik Lokasi User yang cantik (Google Maps Style) */
    .gps-point {
        background-color: #3388ff; /* Warna Biru Leaflet */
        border: 2px solid white;   /* Garis tepi putih */
        border-radius: 50%;        /* Membuatnya bulat sempurna */
        box-shadow: 0 0 8px rgba(0,0,0,0.4); /* Efek bayangan agar timbul */
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

    // 3. MEMBUAT TITIK LOKASI USER (Gaya Google Maps)
    // Kita gunakan DivIcon agar bisa dikasih Style CSS (.gps-point)
    var iconTitik = L.divIcon({
        className: 'gps-point', // Panggil class CSS di atas
        iconSize: [16, 16],     // Ukuran titik (px)
        iconAnchor: [8, 8]      // Titik jangkar pas di tengah (setengah dari ukuran)
    });

    // Tampilkan titiknya di peta
    var markerTitik = L.marker([latitude, longitude], {
        icon: iconTitik
    }).addTo(map);


    // 4. Logic: Klik Titik -> Munculkan Marker Pin Besar
    var markerPin = null; 

    markerTitik.on('click', function() {
        if (markerPin) {
            // Jika pin sudah ada, hapus (toggle)
            map.removeLayer(markerPin);
            markerPin = null;
        } else {
            // Jika belum ada, munculkan Pin Marker standar + Popup Nama
            markerPin = L.marker([latitude, longitude])
                .addTo(map)
                .bindPopup("{{ $presensi->nama_lengkap }}")
                .openPopup();
        }
    });

    // 5. Tampilkan Radius Kantor (Lingkaran Merah)
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

    // 6. Fix Bug Peta Abu-abu & Center
    setTimeout(function() {
        map.invalidateSize();
        map.panTo([latitude, longitude]);
    }, 800);

</script>