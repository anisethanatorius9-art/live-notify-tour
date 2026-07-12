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
                        <span class="text-xs text-zinc-500">{{ $location['description'] }}</span>
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

    <script>
        let map;
        let markers = [];

        function initMap() {
            const tanzaniaCenter = {
                lat: -6.369028,
                lng: 34.888822
            };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 6,
                center: tanzaniaCenter,
                styles: [{
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{
                        visibility: "off"
                    }]
                }]
            });

            const locations = JSON.parse(document.getElementById('tourism-map-data').textContent);

            locations.forEach(location => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: location.lat,
                        lng: location.lng
                    },
                    map: map,
                    title: location.name,
                    animation: google.maps.Animation.DROP
                });

                const infowindow = new google.maps.InfoWindow({
                    content: `<div style="color: black; padding: 4px;"><strong>${location.name}</strong><p style="margin-top: 4px; font-size: 13px;">${location.description}</p></div>`
                });

                marker.addListener("click", () => {
                    infowindow.open(map, marker);
                });

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
            map.setCenter({
                lat: lat,
                lng: lng
            });
            map.setZoom(10);
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
</div>
