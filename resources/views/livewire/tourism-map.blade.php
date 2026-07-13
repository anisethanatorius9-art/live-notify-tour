<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <flux:card class="space-y-2 flex-1">
            <flux:heading size="xl" level="1">Tourism Destinations Map (LTN)</flux:heading>
            <flux:subheading>Explore top tourist attractions across Tanzania on our interactive live map.</flux:subheading>
        </flux:card>
        <a href="{{ route('dashboard') }}" class="ml-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Directions Section -->
            <flux:card class="space-y-3 p-4">
                <flux:heading size="md">Get Directions</flux:heading>
                <div class="space-y-2">
                    <input type="text" id="directions-from" placeholder="From (Your location)" class="w-full px-3 py-2 rounded-lg bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="text" id="directions-to" placeholder="To (Destination)" class="w-full px-3 py-2 rounded-lg bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="get-directions-btn" class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium text-sm">
                        Get Route
                    </button>
                </div>
                <div id="eta-display" class="hidden bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-300 dark:border-green-700">
                    <div class="text-sm">
                        <p class="font-semibold text-green-700 dark:text-green-300">ETA: <span id="eta-time">--:--</span></p>
                        <p class="text-xs text-green-600 dark:text-green-400" id="eta-distance">Distance: -- km</p>
                    </div>
                </div>
            </flux:card>

            <!-- Featured Attractions -->
            <flux:card class="space-y-3 p-4">
                <flux:heading size="md">Featured Attractions</flux:heading>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($locations as $location)
                    <div class="tourism-map-location p-2 rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 transition border border-zinc-200 dark:border-zinc-700"
                         data-lat="{{ $location['lat'] }}"
                         data-lng="{{ $location['lng'] }}"
                         data-name="{{ $location['name'] }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-zinc-800 dark:text-zinc-200">{{ $location['name'] }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $location['description'] }}</p>
                                <div class="flex gap-1 mt-2 flex-wrap">
                                    @foreach($location['attractions'] as $attraction)
                                    <span class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">{{ $attraction }}</span>
                                    @endforeach
                                </div>
                                <div class="flex items-center gap-2 mt-2 text-xs">
                                    <span class="text-yellow-500">★ {{ $location['rating'] }}</span>
                                    <span class="text-zinc-500 dark:text-zinc-400">Best: {{ $location['bestTime'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </flux:card>
        </div>

        <!-- Map Section -->
        <div class="lg:col-span-3">
            <div wire:ignore class="rounded-2xl shadow-md border border-zinc-200 dark:border-zinc-800 overflow-hidden h-full">
                <div id="map" style="height: 600px; width: 100%; min-height: 600px;"></div>
            </div>
        </div>
    </div>

        <script id="tourism-map-data" type="application/json">
            {!! json_encode($locations, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
        </script>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

        <script>
            let map;
            let markers = [];
            let routingControl = null;
            let userLocation = null;

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
                    scrollWheelZoom: true,
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19,
                }).addTo(map);

                // Try to get user location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        userLocation = [position.coords.latitude, position.coords.longitude];
                        const userMarker = L.marker(userLocation, {
                            icon: L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                            })
                        }).addTo(map);
                        userMarker.bindPopup('Your Location');
                        document.getElementById('directions-from').value = 'Your Location';
                    });
                }

                const locations = JSON.parse(document.getElementById('tourism-map-data').textContent);

                locations.forEach(location => {
                    const marker = L.marker([location.lat, location.lng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                        })
                    }).addTo(map);

                    const popupContent = `
                        <div class="text-sm">
                            <strong>${location.name}</strong><br>
                            ${location.description}<br>
                            <small class="text-xs">⭐ ${location.rating} | Best: ${location.bestTime}</small>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                    markers.push({marker, location});
                });

                document.querySelectorAll('.tourism-map-location').forEach(item => {
                    item.addEventListener('click', event => {
                        event.preventDefault();
                        const lat = parseFloat(item.dataset.lat);
                        const lng = parseFloat(item.dataset.lng);
                        const name = item.dataset.name;

                        if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                            focusLocation(lat, lng);
                            document.getElementById('directions-to').value = name;
                        }
                    });
                });

                // Directions button
                document.getElementById('get-directions-btn').addEventListener('click', getDirections);
            }

            function focusLocation(lat, lng) {
                if (!map) {
                    return;
                }

                // Zoom deep to show streets and buildings
                map.flyTo([lat, lng], 17, {
                    duration: 1.2,
                    easeLinearity: 0.25
                });
            }

            function getDirections() {
                const fromInput = document.getElementById('directions-from').value;
                const toInput = document.getElementById('directions-to').value;

                if (!fromInput || !toInput) {
                    alert('Please enter both starting point and destination');
                    return;
                }

                // Geocode locations using Leaflet Geocoder
                const geocoder = L.Control.Geocoder.nominatim();

                geocoder.geocode(fromInput, function(results) {
                    if (!results || results.length === 0) {
                        alert('Could not find: ' + fromInput);
                        return;
                    }
                    const fromCoords = [results[0].center.lat, results[0].center.lng];

                    geocoder.geocode(toInput, function(results2) {
                        if (!results2 || results2.length === 0) {
                            alert('Could not find: ' + toInput);
                            return;
                        }
                        const toCoords = [results2[0].center.lat, results2[0].center.lng];

                        // Clear existing routing
                        if (routingControl) {
                            map.removeControl(routingControl);
                        }

                        // Create routing
                        routingControl = L.Routing.control({
                            waypoints: [
                                L.latLng(fromCoords[0], fromCoords[1]),
                                L.latLng(toCoords[0], toCoords[1])
                            ],
                            router: L.Routing.osrmv1({
                                serviceUrl: 'https://router.project-osrm.org/route/v1'
                            }),
                            routeWhileDragging: true,
                            showAlternatives: true,
                            lineOptions: {
                                styles: [{color: 'blue', opacity: 0.7, weight: 5}]
                            }
                        }).addTo(map);

                        // Calculate ETA
                        routingControl.on('routesfound', function(e) {
                            const route = e.routes[0];
                            const distanceKm = (route.summary.totalDistance / 1000).toFixed(2);
                            const durationSeconds = route.summary.totalTime;
                            const hours = Math.floor(durationSeconds / 3600);
                            const minutes = Math.floor((durationSeconds % 3600) / 60);

                            const etaDisplay = document.getElementById('eta-display');
                            const etaTime = document.getElementById('eta-time');
                            const etaDistance = document.getElementById('eta-distance');

                            if (hours > 0) {
                                etaTime.textContent = `${hours}h ${minutes}m`;
                            } else {
                                etaTime.textContent = `${minutes}m`;
                            }
                            etaDistance.textContent = `Distance: ${distanceKm} km`;
                            etaDisplay.classList.remove('hidden');
                        });
                    });
                });
            }

            window.addEventListener('load', initMap);
            window.addEventListener('resize', function () {
                if (map) {
                    map.invalidateSize();
                }
            });
        </script>
