<div>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <flux:badge color="blue">LNT Transport</flux:badge>
                    <flux:heading size="xl" level="1" class="mt-2">Go anywhere from the Tourism Map</flux:heading>
                    <flux:subheading>Search a destination, compare transport prices, book your ride, and pay online.</flux:subheading>
                </div>
                <flux:button href="{{ route('dashboard') }}" variant="ghost" icon="arrow-left">Back to dashboard</flux:button>
            </div>

            @if(session('error'))
                <flux:callout variant="danger" icon="exclamation-triangle">{{ session('error') }}</flux:callout>
            @endif

            <div class="grid gap-6 lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1.5fr)]">
                <div class="space-y-5">
                    <flux:card class="space-y-4">
                        <div>
                            <flux:heading size="md">Plan your ride</flux:heading>
                            <flux:subheading>Choose where you are and where you want to go.</flux:subheading>
                        </div>
                        <flux:field>
                            <flux:label>Pick-up point</flux:label>
                            <flux:input id="directions-from" wire:model="origin" placeholder="Use my location or search a place" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Destination</flux:label>
                            <flux:input id="directions-to" wire:model="destination" placeholder="Hotel, park, attraction or address" />
                        </flux:field>
                        <flux:button type="button" id="get-directions-btn" variant="primary" class="w-full" icon="map">Find route and prices</flux:button>
                        <p id="route-error" class="hidden text-sm text-red-600 dark:text-red-400"></p>
                        <div id="eta-display" class="hidden rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950/40">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Your route</p>
                                    <p id="eta-distance" class="mt-1 text-sm font-medium text-zinc-900 dark:text-white"></p>
                                </div>
                                <p id="eta-time" class="text-lg font-bold text-emerald-700 dark:text-emerald-300"></p>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <flux:heading size="md">Choose transport</flux:heading>
                                <flux:subheading>Prices are estimated from the route distance.</flux:subheading>
                            </div>
                            <flux:badge color="green">TZS</flux:badge>
                        </div>
                        <div class="space-y-3">
                            @foreach($transportOptions as $key => $option)
                                <label wire:key="transport-option-{{ $key }}" class="flex cursor-pointer items-start gap-3 rounded-xl border p-3 transition hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 dark:has-[:checked]:border-blue-400 dark:has-[:checked]:bg-blue-950/30">
                                    <input type="radio" name="transportType" wire:model.live="transportType" value="{{ $key }}" class="mt-1 text-blue-600 focus:ring-blue-500">
                                    <span class="min-w-0 flex-1">
                                        <span class="flex items-center justify-between gap-2">
                                            <span class="font-semibold text-zinc-900 dark:text-white">{{ $option['label'] }}</span>
                                            <span class="font-bold text-blue-700 dark:text-blue-300">{{ $option['quote'] ? 'TZS ' . number_format($option['quote'], 0) : 'Quote after route' }}</span>
                                        </span>
                                        <span class="mt-1 block text-xs text-zinc-500 dark:text-zinc-400">{{ $option['description'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </flux:card>

                    <flux:card class="space-y-4">
                        <flux:heading size="md">Passenger and payment</flux:heading>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Travel date</flux:label>
                                <flux:input type="date" wire:model="bookingDate" min="{{ now()->toDateString() }}" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Passengers</flux:label>
                                <flux:input type="number" min="1" max="50" wire:model.live="numberOfPeople" />
                            </flux:field>
                        </div>
                        <flux:field>
                            <flux:label>Payment phone</flux:label>
                            <flux:input wire:model="phone" placeholder="07XX XXX XXX" />
                            <flux:description>Used to open the secure mobile-money/card checkout.</flux:description>
                        </flux:field>
                        <flux:field>
                            <flux:label>Payment method</flux:label>
                            <flux:select wire:model="paymentMethod">
                                <flux:select.option value="mastercard">Mobile money / card checkout</flux:select.option>
                                <flux:select.option value="paypal">PayPal</flux:select.option>
                            </flux:select>
                        </flux:field>
                        <div class="flex items-center justify-between border-t border-zinc-200 pt-4 dark:border-zinc-800">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-zinc-500">Estimated total</p>
                                <p class="text-2xl font-bold text-zinc-950 dark:text-white">TZS {{ number_format($estimatedFare, 0) }}</p>
                            </div>
                            <flux:button type="button" wire:click="createTransportBooking" wire:loading.attr="disabled" variant="primary" icon="credit-card">
                                <span wire:loading.remove>Book and pay</span>
                                <span wire:loading>Starting checkout...</span>
                            </flux:button>
                        </div>
                    </flux:card>
                </div>

                <div class="min-h-[560px] overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div wire:ignore id="map" class="h-[560px] w-full"></div>
                </div>
            </div>

            <flux:card class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <flux:heading size="sm">One booking, one secure checkout</flux:heading>
                    <flux:subheading>The driver receives the ride request after payment confirmation. Your booking remains pending until the gateway confirms payment.</flux:subheading>
                </div>
                <flux:button href="{{ route('bookings.index') }}" variant="ghost" icon="calendar-days">View my bookings</flux:button>
            </flux:card>
        </div>
    </div>

    <script id="tourism-map-data" type="application/json">{!! json_encode($locations, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        (() => {
            let map;
            let routeControl;
            let userLocation;
            const locations = JSON.parse(document.getElementById('tourism-map-data').textContent);

            const getLivewireComponent = () => {
                const root = document.querySelector('[wire\\:id]');
                return root ? Livewire.find(root.getAttribute('wire:id')) : null;
            };

            const setLivewireRoute = (route) => {
                const component = getLivewireComponent();
                if (!component) return;

                component.set('origin', route.origin);
                component.set('destination', route.destination);
                component.call('setRoute', route);
            };

            const locateUser = () => new Promise((resolve, reject) => {
                if (userLocation) return resolve(userLocation);
                if (!navigator.geolocation) return reject(new Error('Location access is unavailable.'));
                navigator.geolocation.getCurrentPosition(
                    ({ coords }) => {
                        userLocation = [coords.latitude, coords.longitude];
                        L.marker(userLocation).addTo(map).bindPopup('Your location');
                        resolve(userLocation);
                    },
                    () => reject(new Error('Allow location access or enter a pick-up point.')),
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
                );
            });

            const geocode = (query) => new Promise((resolve, reject) => {
                const normalized = query.toLowerCase();
                const match = locations.find((location) => location.name.toLowerCase().includes(normalized) || normalized.includes(location.name.toLowerCase()));
                if (match) return resolve([match.lat, match.lng]);
                const geocoder = L.Control.Geocoder.nominatim();
                geocoder.geocode(query, (results) => results?.length ? resolve([results[0].center.lat, results[0].center.lng]) : reject(new Error(`Location not found: ${query}`)));
            });

            const showError = (message) => {
                const error = document.getElementById('route-error');
                error.textContent = message;
                error.classList.remove('hidden');
            };

            const findRoute = async (event) => {
                event?.preventDefault();
                const button = document.getElementById('get-directions-btn');
                const from = document.getElementById('directions-from').value.trim();
                const to = document.getElementById('directions-to').value.trim();
                document.getElementById('route-error').classList.add('hidden');
                if (!to) return showError('Enter a destination first.');
                button.disabled = true;
                button.textContent = 'Finding route...';

                try {
                    const fromCoords = from ? await geocode(from) : await locateUser();
                    const toCoords = await geocode(to);
                    const originLabel = from || 'My current location';
                    document.getElementById('directions-from').value = originLabel;
                    if (routeControl) map.removeControl(routeControl);
                    routeControl = L.Routing.control({
                        waypoints: [L.latLng(...fromCoords), L.latLng(...toCoords)],
                        router: L.Routing.osrmv1({ serviceUrl: 'https://router.project-osrm.org/route/v1' }),
                        addWaypoints: false,
                        routeWhileDragging: false,
                        showAlternatives: false,
                        createMarker: () => null,
                        lineOptions: { styles: [{ color: '#2563eb', opacity: 0.9, weight: 5 }] }
                    }).addTo(map);
                    routeControl.on('routesfound', (event) => {
                        const summary = event.routes[0].summary;
                        const distanceKm = summary.totalDistance / 1000;
                        const durationMinutes = Math.max(1, Math.round(summary.totalTime / 60));
                        document.getElementById('eta-display').classList.remove('hidden');
                        document.getElementById('eta-distance').textContent = `${distanceKm.toFixed(1)} km from pick-up to destination`;
                        document.getElementById('eta-time').textContent = `${durationMinutes} min`;
                        setLivewireRoute({ origin: originLabel, destination: to, originLatitude: fromCoords[0], originLongitude: fromCoords[1], destinationLatitude: toCoords[0], destinationLongitude: toCoords[1], distanceKm, durationMinutes });
                    });
                    routeControl.on('routingerror', () => showError('Could not calculate this route. Try a nearby address.'));
                } catch (error) {
                    showError(error.message || 'Route calculation failed.');
                } finally {
                    button.disabled = false;
                    button.textContent = 'Find route and prices';
                }
            };

            const init = () => {
                if (map) return;
                map = L.map('map').setView([-6.369028, 34.888822], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
                locations.forEach((location) => L.marker([location.lat, location.lng]).addTo(map).bindPopup(`<strong>${location.name}</strong><br>${location.description}`));
                document.getElementById('get-directions-btn').addEventListener('click', findRoute);
                locateUser().then(() => { if (!document.getElementById('directions-from').value) document.getElementById('directions-from').value = 'My current location'; }).catch(() => {});
            };

            window.addEventListener('load', init, { once: true });
        })();
    </script>
</div>
