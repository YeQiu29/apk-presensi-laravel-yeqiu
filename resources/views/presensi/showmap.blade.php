<style>
    #map { 
        height: 250px; 
    }
</style>

<div id="map"></div>
<script>
    var lokasi = "{{ $presensi->lokasi_out ?? $presensi->lokasi_in }}";
    var lok = lokasi.split(",");
    var latitude = lok[0];
    var longitude = lok[1];
    var map = L.map('map').setView([latitude, longitude], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);

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

    var popup = L.popup()
        .setLatLng([latitude, longitude])
        .setContent("{{ $presensi->nama_lengkap }}")
        .openOn(map);
</script>