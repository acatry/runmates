{{-- resources/views/running-sessions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Détails de la session
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">

            {{-- Informations principales --}}
            <h3 class="text-2xl font-bold mb-2">{{ $session->title }}</h3>
            <p class="text-gray-600 mb-4">{{ $session->description }}</p>

            <p class="text-sm text-gray-700 mb-2">
                <strong>Lieu :</strong> {{ $session->location }} ({{ $session->city ?? '-' }})<br>
                <strong>Date :</strong> {{ $session->start_at->format('d/m/Y H:i') }}
            </p>

            {{-- Carte Leaflet --}}
            @if($session->latitude && $session->longitude)
                {{-- Import du CSS Leaflet ici pour le style --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

                <div id="map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>

                {{-- Import du JS Leaflet tout en bas de la page --}}
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Initialisation de la carte
                        var map = L.map('map').setView([{{ $session->latitude }}, {{ $session->longitude }}], 14);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        // Ajout du marqueur
                        L.marker([{{ $session->latitude }}, {{ $session->longitude }}]).addTo(map);
                    });
                </script>
            @else
                <p class="text-sm text-gray-500 mt-2">
                    Aucun point de rencontre défini sur la carte.
                </p>
            @endif

            {{-- Infos complémentaires --}}
            <p class="text-sm text-gray-700 mt-4 mb-4">
                Distance : entre {{ $session->distance_km_min ?? '?' }} et {{ $session->distance_km_max ?? '?' }} km<br>
                Allure : entre {{ $session->pace_min_per_km_min ?? '?' }} et {{ $session->pace_min_per_km_max ?? '?' }} min/km
            </p>

            {{-- Bouton pour rejoindre ou quitter --}}
            @php
                $isRegistered = auth()->check() && $session->attendees()->where('users.id', auth()->id())->exists();
            @endphp

            <div class="mb-6">
                @if($isRegistered)
                    <form method="POST" action="{{ route('running-sessions.leave', $session) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                            Se désinscrire
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('running-sessions.join', $session) }}">
                        @csrf
                        <button class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-500">
                            S’inscrire à cette session
                        </button>
                    </form>
                @endif
            </div>

            {{-- Liste des participants --}}
            <h4 class="font-semibold text-lg mb-2">Participants :</h4>
            <ul class="list-disc list-inside text-gray-700">
                @forelse($session->attendees as $user)
                    <li>
                        <a href="{{ route('runner.profile', $user->id) }}" class="text-blue-600 hover:underline">
                            {{ $user->name }}
                        </a>
                    </li>
                @empty
                    <li>Aucun participant pour le moment.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
