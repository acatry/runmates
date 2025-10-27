{{-- resources/views/running-sessions/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Publier une session d’entraînement
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('running-sessions.store') }}">
                @csrf

                {{-- Titre --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" name="title" class="w-full border rounded px-3 py-2"
                           value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                </div>

                {{-- Lieu --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Lieu de rendez-vous</label>
                    <input type="text" name="location" class="w-full border rounded px-3 py-2"
                           value="{{ old('location') }}" required>
                </div>

                {{-- Ville + Code postal --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ville</label>
                        <input type="text" name="city" class="w-full border rounded px-3 py-2"
                               value="{{ old('city') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code postal</label>
                        <input type="text" name="zipcode" class="w-full border rounded px-3 py-2"
                               value="{{ old('zipcode') }}">
                    </div>
                </div>

                {{-- Date et heure --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Date et heure</label>
                    <input type="datetime-local" name="start_at" class="w-full border rounded px-3 py-2"
                           value="{{ old('start_at') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Choisissez le point de départ de l'entraînement</label>

                    {{-- Pour stocker les coordonnées --}}
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                    <div id="map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
                </div>

                
                {{-- Distance et allure --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Distance (km)</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.1" name="distance_km_min" class="w-full border rounded px-3 py-2"
                                   placeholder="min" value="{{ old('distance_km_min') }}">
                            <input type="number" step="0.1" name="distance_km_max" class="w-full border rounded px-3 py-2"
                                   placeholder="max" value="{{ old('distance_km_max') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Allure (min/km)</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.1" name="pace_min_per_km_min" class="w-full border rounded px-3 py-2"
                                   placeholder="min" value="{{ old('pace_min_per_km_min') }}">
                            <input type="number" step="0.1" name="pace_min_per_km_max" class="w-full border rounded px-3 py-2"
                                   placeholder="max" value="{{ old('pace_min_per_km_max') }}">
                        </div>
                    </div>
                </div>

                {{-- Bouton d’envoi --}}
                <div class="mt-6">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">
                        Publier la session
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        var map = L.map('map').setView([50.8503, 4.3517], 12); // Bxl
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker;

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
