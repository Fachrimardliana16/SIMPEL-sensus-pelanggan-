<div x-data="{
    lat: @entangle('lati'),
    lng: @entangle('longi'),
    map: null,
    marker: null,
    init() {
        this.$nextTick(() => {
            this.setupMap();
        });
    },
    setupMap() {
        if (typeof L === 'undefined') {
            setTimeout(() => this.setupMap(), 250);
            return;
        }

        if (this.map) return;

        let initialLat = parseFloat(this.lat) || -7.3857;
        let initialLng = parseFloat(this.lng) || 109.3590;

        try {
            this.map = L.map($refs.map_container).setView([initialLat, initialLng], 14);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);

            this.marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(this.map);

            this.marker.on('dragend', (e) => {
                let pos = e.target.getLatLng();
                this.lat = pos.lat.toFixed(7);
                this.lng = pos.lng.toFixed(7);
                $wire.set('data.lati', this.lat);
                $wire.set('data.longi', this.lng);
            });

            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng);
                this.lat = e.latlng.lat.toFixed(7);
                this.lng = e.latlng.lng.toFixed(7);
                $wire.set('data.lati', this.lat);
                $wire.set('data.longi', this.lng);
            });

            $watch('lat', value => {
                if (value && this.marker) {
                    let currentPos = this.marker.getLatLng();
                    if (Math.abs(currentPos.lat - parseFloat(value)) > 0.00001) {
                        let newPos = [parseFloat(value), parseFloat(this.lng)];
                        this.marker.setLatLng(newPos);
                        this.map.panTo(newPos);
                    }
                }
            });
            $watch('lng', value => {
                if (value && this.marker) {
                    let currentPos = this.marker.getLatLng();
                    if (Math.abs(currentPos.lng - parseFloat(value)) > 0.00001) {
                        let newPos = [parseFloat(this.lat), parseFloat(value)];
                        this.marker.setLatLng(newPos);
                        this.map.panTo(newPos);
                    }
                }
            });
        } catch (e) {
            console.error('Leaflet Init Error:', e);
        }
    },
    getCurrentLocation() {
        if (!navigator.geolocation) {
            alert('GPS tidak didukung di browser ini.');
            return;
        }
        navigator.geolocation.getCurrentPosition((position) => {
            this.lat = position.coords.latitude.toFixed(7);
            this.lng = position.coords.longitude.toFixed(7);
            let alti = position.coords.altitude ? position.coords.altitude.toFixed(1) : 0;
            
            this.map.setView([this.lat, this.lng], 18);
            this.marker.setLatLng([this.lat, this.lng]);
            
            // Explicitly update Filament form state
            $wire.set('data.lati', this.lat);
            $wire.set('data.longi', this.lng);
            $wire.set('data.alti', alti);
        }, (error) => {
            alert('Gagal mengambil lokasi: ' + error.message);
        }, { enableHighAccuracy: true });
    }
}" class="w-full space-y-4 py-2">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Map Container -->
    <div x-ref="map_container" 
         class="w-full h-[400px] rounded-2xl border border-gray-200 shadow-sm bg-gray-50 z-0" 
         wire:ignore 
         style="min-height: 400px;">
    </div>

    <!-- Simple Green GPS Button below Map -->
    <button type="button"
            @click="getCurrentLocation()"
            class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition shadow-sm">
        <x-heroicon-s-map-pin class="w-5 h-5" />
        Ambil Lokasi Saat Ini (GPS)
    </button>
    
    <p class="text-[10px] text-gray-400 text-center">
        * Anda bisa menggeser titik di peta secara manual.
    </p>
</div>
