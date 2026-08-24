<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <flux:card class="space-y-2 flex-1">
            <flux:heading size="xl" level="1">Tourism Destinations Map (LTN)</flux:heading>
            <flux:subheading>Explore top tourist attractions across Tanzania on our interactive live map.</flux:subheading>
        </flux:card>
        <a href="{{ route('dashboard') }}" class="ml-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
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
                <flux:heading size="md">🎯 Featured Attractions</flux:heading>
                <div class="space-y-3 max-h-[600px] overflow-y-auto">
                    @foreach($locations as $location)
                    <div class="tourism-map-location p-3 rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/30 transition border-2 border-zinc-200 dark:border-zinc-700 hover:border-blue-400"
                        data-lat="{{ $location['lat'] }}"
                        data-lng="{{ $location['lng'] }}"
                        data-name="{{ $location['name'] }}">
                        <div>
                            <!-- Title & Rating -->
                            <div class="flex items-start justify-between mb-2">
                                <p class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ $location['name'] }}</p>
                                <span class="text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded">⭐ {{ $location['rating'] }}</span>
                            </div>

                            <!-- Description -->
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 line-clamp-2 mb-2">{{ $location['description'] }}</p>

                            <!-- Travel Info -->
                            <div class="text-xs mb-2 space-y-1 bg-zinc-100 dark:bg-zinc-800 p-2 rounded">
                                <p>🚗 <strong>{{ $location['distance'] }}</strong></p>
                                <p>⏱️ {{ $location['hours'] }}</p>
                            </div>

                            <!-- Attractions Tags -->
                            <div class="flex gap-1 mb-2 flex-wrap">
                                @foreach($location['attractions'] as $attraction)
                                <span class="text-xs bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-2 py-1 rounded font-medium">{{ $attraction }}</span>
                                @endforeach
                            </div>

                            <!-- Price & Difficulty -->
                            <div class="grid grid-cols-2 gap-2 mb-2 text-xs">
                                <div class="bg-green-100 dark:bg-green-900/30 p-1 rounded text-center">
                                    <p class="font-semibold text-green-700 dark:text-green-300">{{ $location['price'] }}</p>
                                    <p class="text-green-600 dark:text-green-400">Pricing</p>
                                </div>
                                <div class="bg-red-100 dark:bg-red-900/30 p-1 rounded text-center">
                                    <p class="font-semibold text-red-700 dark:text-red-300">{{ $location['difficulty'] }}</p>
                                    <p class="text-red-600 dark:text-red-400">Level</p>
                                </div>
                            </div>

                            <!-- Accommodation -->
                            <div class="mb-2">
                                <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">🏨 Stays:</p>
                                <div class="flex gap-1 flex-wrap">
                                    @foreach($location['accommodation'] as $stay)
                                    <span class="text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-2 py-0.5 rounded">{{ $stay }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Services -->
                            <div>
                                <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">🛠️ Services:</p>
                                <div class="flex gap-1 flex-wrap">
                                    @foreach($location['services'] as $service)
                                    <span class="text-xs bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-2 py-0.5 rounded">{{ $service }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Best Time -->
                            <p class="text-xs mt-2 text-zinc-600 dark:text-zinc-400">📅 Best: <strong>{{ $location['bestTime'] }}</strong></p>
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
        {
            !!json_encode($locations, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!
        }
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
        let tourismLocations = [];

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

            requestUserLocation();

            tourismLocations = JSON.parse(document.getElementById('tourism-map-data').textContent);

            tourismLocations.forEach(location => {
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
                markers.push({
                    marker,
                    location
                });
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

            document.getElementById('get-directions-btn').addEventListener('click', getDirections);
        }

        function requestUserLocation() {
            if (!navigator.geolocation) {
                return;
            }

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

                const fromInput = document.getElementById('directions-from');
                if (fromInput && !fromInput.value.trim()) {
                    fromInput.value = 'Your Location';
                }
            }, function(error) {
                console.warn('Geolocation unavailable:', error);
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            });
        }

        function focusLocation(lat, lng) {
            if (!map) {
                return;
            }

            map.flyTo([lat, lng], 17, {
                duration: 1.5,
                easeLinearity: 0.3
            });

            markers.forEach(m => {
                if (Math.abs(m.location.lat - lat) < 0.01 && Math.abs(m.location.lng - lng) < 0.01) {
                    m.marker.openPopup();
                }
            });
        }

        function getUserLocationCoords() {
            return new Promise((resolve, reject) => {
                if (userLocation) {
                    resolve(userLocation);
                    return;
                }

                if (!navigator.geolocation) {
                    reject(new Error('Location access is not available in this browser.'));
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    position => resolve([position.coords.latitude, position.coords.longitude]),
                    error => reject(error), {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            });
        }

        function findStoredLocationCoordinates(text) {
            if (!text) {
                return null;
            }

            const query = text.toString().trim().toLowerCase();
            return tourismLocations.find(location => {
                const name = location.name.toLowerCase();
                return name === query || name.includes(query) || query.includes(name);
            }) || null;
        }

        function geocodeLocation(text) {
            return new Promise((resolve, reject) => {
                const directMatch = findStoredLocationCoordinates(text);
                if (directMatch) {
                    resolve([directMatch.lat, directMatch.lng]);
                    return;
                }

                const geocoder = L.Control.Geocoder && typeof L.Control.Geocoder.nominatim === 'function' ?
                    L.Control.Geocoder.nominatim() :
                    null;

                if (!geocoder) {
                    reject(new Error('Geocoder is unavailable.'));
                    return;
                }

                const timeout = setTimeout(() => {
                    reject(new Error('Location lookup timed out.'));
                }, 7000);

                geocoder.geocode(text, function(results) {
                    clearTimeout(timeout);
                    if (!results || results.length === 0) {
                        reject(new Error('Location not found: ' + text));
                        return;
                    }
                    resolve([results[0].center.lat, results[0].center.lng]);
                });
            });
        }

        async function getDirections() {
            const fromInput = document.getElementById('directions-from').value.trim();
            const toInput = document.getElementById('directions-to').value.trim();

            if (!toInput) {
                alert('⚠️ Please enter a destination');
                return;
            }

            const btn = document.getElementById('get-directions-btn');
            const originalText = btn.textContent;
            btn.textContent = '⏳ Finding route...';
            btn.disabled = true;

            try {
                let fromCoords;
                const shouldUseLiveLocation = !fromInput || /your location|my location/i.test(fromInput);

                if (shouldUseLiveLocation) {
                    try {
                        fromCoords = await getUserLocationCoords();
                        if (!fromInput) {
                            document.getElementById('directions-from').value = 'Your Location';
                        }
                    } catch (error) {
                        console.warn('Live location unavailable, falling back to manual input:', error);
                        if (!fromInput) {
                            alert('Please allow location access or enter a starting location.');
                            btn.textContent = originalText;
                            btn.disabled = false;
                            return;
                        }
                        fromCoords = await geocodeLocation(fromInput);
                    }
                } else {
                    fromCoords = await geocodeLocation(fromInput);
                }

                const toCoords = await geocodeLocation(toInput);

                if (routingControl) {
                    map.removeControl(routingControl);
                }

                map.flyToBounds([
                    [fromCoords[0], fromCoords[1]],
                    [toCoords[0], toCoords[1]]
                ], {
                    padding: [60, 60],
                    maxZoom: 14
                });

                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(fromCoords[0], fromCoords[1]),
                        L.latLng(toCoords[0], toCoords[1])
                    ],
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1'
                    }),
                    routeWhileDragging: true,
                    showAlternatives: false,
                    lineOptions: {
                        styles: [{
                                color: '#3B82F6',
                                opacity: 0.8,
                                weight: 5
                            },
                            {
                                color: '#FFFFFF',
                                opacity: 0.3,
                                weight: 8
                            }
                        ],
                        extendToWaypoints: true,
                        missingRouteTolerance: 2
                    },
                    createMarker: function(i, wp) {
                        const icon = i === 0 ? 'green' : 'red';
                        return L.marker(wp.latLng, {
                            icon: L.icon({
                                iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${icon}.png`,
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                            })
                        });
                    }
                }).addTo(map);

                routingControl.on('routesfound', function(e) {
                    const route = e.routes[0];
                    const distanceKm = (route.summary.totalDistance / 1000).toFixed(2);
                    const durationSeconds = route.summary.totalTime;
                    const hours = Math.floor(durationSeconds / 3600);
                    const minutes = Math.floor((durationSeconds % 3600) / 60);

                    const etaDisplay = document.getElementById('eta-display');
                    const etaTime = document.getElementById('eta-time');
                    const etaDistance = document.getElementById('eta-distance');

                    let etaText = '';
                    if (hours > 0) {
                        etaText = `${hours}h ${minutes}m`;
                    } else {
                        etaText = `${minutes}m`;
                    }

                    etaTime.textContent = etaText;
                    etaDistance.textContent = `📍 Distance: ${distanceKm} km`;
                    etaDisplay.classList.remove('hidden');

                    btn.textContent = '✅ Route Found!';
                    setTimeout(() => {
                        btn.textContent = originalText;
                        btn.disabled = false;
                    }, 2000);
                });

                routingControl.on('routingerror', function(e) {
                    console.error('Routing error:', e);
                    alert('⚠️ Could not calculate route. Please try different locations.');
                    btn.textContent = originalText;
                    btn.disabled = false;
                });
            } catch (error) {
                console.error('Error creating route:', error);
                alert('⚠️ Route calculation failed. Please check your internet connection or location permission.');
                btn.textContent = originalText;
                btn.disabled = false;
            }
        }

        window.addEventListener('load', initMap);
        window.addEventListener('resize', function() {
            if (map) {
                map.invalidateSize();
            }
        });
    </script>
