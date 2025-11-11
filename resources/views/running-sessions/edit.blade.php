<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Modifier la session</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('running-sessions.update', $session) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium">Titre</label>
                    <input type="text" name="title" class="w-full border rounded px-3 py-2"
                           value="{{ $session->title }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ $session->description }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Lieu</label>
                    <input type="text" name="location" class="w-full border rounded px-3 py-2"
                           value="{{ $session->location }}" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium">Ville</label>
                        <input type="text" name="city" class="w-full border rounded px-3 py-2"
                               value="{{ $session->city }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Code postal</label>
                        <input type="text" name="zipcode" class="w-full border rounded px-3 py-2"
                               value="{{ $session->zipcode }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Modifier le point de départ
                    </label>
                    <input type="hidden" name="latitude" id="latitude" value="{{ $session->latitude }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ $session->longitude }}">

                    <div id="map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Date et heure</label>
                    <input type="datetime-local" name="start_at" class="w-full border rounded px-3 py-2"
                           value="{{ $session->start_at->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Distance (km)</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.1" name="distance_km_min" class="w-full border rounded px-3 py-2"
                                   value="{{ $session->distance_km_min }}">
                            <input type="number" step="0.1" name="distance_km_max" class="w-full border rounded px-3 py-2"
                                   value="{{ $session->distance_km_max }}">
                        </div>
                    </div>
                <div>
                        <label class="block text-sm font-medium text-gray-700">Allure (min/km)</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.1" name="pace_min_per_km_min" class="w-full border rounded px-3 py-2"
                                   value="{{ $session->pace_min_per_km_min }}">
                            <input type="number" step="0.1" name="pace_min_per_km_max" class="w-full border rounded px-3 py-2"
                                   value="{{ $session->pace_min_per_km_max }}">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">
                        Enregistrer
                    </button>
                    <a href="{{ route('running-sessions.show', $session) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        var defaultLat = 50.8503, defaultLng = 4.3517;
        var currentLat = {{ $session->latitude ?? 'null' }};
        var currentLng = {{ $session->longitude ?? 'null' }};

        var startLat = (currentLat !== null) ? currentLat : defaultLat;
        var startLng = (currentLng !== null) ? currentLng : defaultLng;
        var zoom    = (currentLat !== null) ? 14 : 12;

        var map = L.map('map').setView([startLat, startLng], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = null;
        if (currentLat !== null && currentLng !== null) {
            marker = L.marker([currentLat, currentLng]).addTo(map);
        }
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng]).addTo(map);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    </script>
</x-app-layout>
