<div class="p-6 space-y-6">
    <flux:card class="space-y-2">
        <flux:heading size="xl" level="1">Tourism Destinations Map (LTN)</flux:heading>
        <flux:subheading>Explore top tourist attractions across Tanzania on our interactive live map.</flux:subheading>
    </flux:card>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="space-y-3">
            <flux:heading size="lg">Featured Attractions</flux:heading>
            <flux:navlist class="bg-white dark:bg-zinc-900 p-2 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800">
                @foreach($locations as $location)
                <flux:navlist.item
                    icon="map-pin"
                    href="#"
                    data-lat="{{ $location['lat'] }}"
                    data-lng="{{ $location['lng'] }}"
                    class="tourism-map-location">
                    <div class="flex flex-col">
                        <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $location['name'] }}</span>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $location['description'] }}</span>
                        </div>
                    </flux:navlist.item>
                    @endforeach
                </flux:navlist>
            </div>

            <div class="md:col-span-2">
                <div wire:ignore class="rounded-2xl shadow-md border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div id="map" style="height: 450px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <script id="tourism-map-data" type="application/json">
            {!! json_encode($locations, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
        </script>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-o9N1j7k5kG1hVbXkY1Kv3j2UHDa3Q6zVbLkIn1R8MUE=" crossorigin="" />

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-o3ep17q3u7V4Xz2zH8BlGCJW+7UPv36nUv97Hi3uw5w=" crossorigin=""></script>

        <script>
            let map;
            let markers = [];

            function initMap() {
                const tanzaniaCenter = [-6.369028, 34.888822];
                const mapContainer = document.getElementById('map');

                if (!mapContainer || typeof L === 'undefined') {
                    console.error('Leaflet library not loaded or map container missing.');
                    return;
                }

                map = L.map(mapContainer, {
                    center: tanzaniaCenter,
                    zoom: 6,
                    zoomControl: true,
                    scrollWheelZoom: false,
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 18,
                }).addTo(map);

                const locations = JSON.parse(document.getElementById('tourism-map-data').textContent);

                locations.forEach(location => {
                    const marker = L.marker([location.lat, location.lng]).addTo(map);
                    marker.bindPopup(`<strong>${location.name}</strong><br>${location.description}`);
                    markers.push(marker);
                });

                document.querySelectorAll('.tourism-map-location').forEach(item => {
                    item.addEventListener('click', event => {
                        event.preventDefault();
                        const lat = parseFloat(item.dataset.lat);
                        const lng = parseFloat(item.dataset.lng);

                        if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                            focusLocation(lat, lng);
                        }
                    });
                });
            }

            function focusLocation(lat, lng) {
                if (!map) {
                    return;
                }

                map.flyTo([lat, lng], 10, {
                    duration: 0.8
                });
            }

            window.addEventListener('load', initMap);
            window.addEventListener('resize', function () {
                if (map) {
                    map.invalidateSize();
                }
            });
        </script>
