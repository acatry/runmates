{{-- resources/views/running-sessions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Détails de la session
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">

            <!-- Détails de la session -->
            <h3 class="text-2xl font-bold mb-2">{{ $session->title }} par <a href="{{ route('runner.profile', $session->organizer->id) }}" class="text-blue-600 hover:underline">
            {{ $session->organizer->name }}</a></h3>
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

            <p class="text-sm text-gray-700 mt-4 mb-4">
                Distance : entre {{ $session->distance_km_min ?? '?' }} et {{ $session->distance_km_max ?? '?' }} km<br>
                Allure : entre {{ $session->pace_min_per_km_min ?? '?' }} et {{ $session->pace_min_per_km_max ?? '?' }} min/km
            </p>

            {{-- Bouton pour rejoindre ou quitter --}}
            @php
                $isRegistered = auth()->user()
                    ? $session->attendees()->where('users.id', auth()->id())->exists()
                    : false;
                $attendeeCount = $session->attendees()->count();
            @endphp

            <div class="mb-6 flex items-center justify-between">
                <div>
                    @if($isRegistered)
                        <form method="POST" action="{{ route('running-sessions.leave', $session) }}">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-gray-300 text-gray-800 rounded hover:bg-gray-300 text-sm">
                                Se désinscrire
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('running-sessions.join', $session) }}">
                            @csrf
                            <button class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-300 text-sm">
                                S’inscrire
                            </button>
                        </form>
                    @endif
                    @if(auth()->id() === $session->organizer_id)
                        <a href="{{ route('running-sessions.edit', $session) }}"
                           class="inline-block px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-300 text-sm">
                            Modifier
                        </a>

                        <form method="POST" action="{{ route('running-sessions.delete', $session) }}"
                              class="inline-block"
                              onsubmit="return confirm('Supprimer cette session ?');">
                            @csrf
                            @method('DELETE')

                           <button class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-300 text-sm">
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Liste des participants -->
            <h4 class="font-semibold text-lg mb-2"><span class="text-sm text-gray-600">{{ $attendeeCount }} participant{{ $attendeeCount > 1 ? 's' : '' }}</span> :</h4>
            @php
                $attendees = $session->attendees()->orderBy('name')->get();
            @endphp

            @if($attendees->isEmpty())
                <p class="text-gray-500 text-sm">Aucun participant pour le moment.</p>
            @else
                <ul class="list-disc pl-6 space-y-1 text-gray-700">
                    @foreach($attendees as $user)
                        <li>
                            <a href="{{ route('runner.profile', $user->id) }}" class="text-blue-600 hover:underline">
                                {{ $user->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
            
            <!-- Commentaires -->
            <h4 class="font-semibold mb-2">Commentaires :</h4>
            <div class="bg-white p-4 rounded shadow mt-6">
                <!-- Liste des commentaires -->
               @foreach($session->comments as $comment)
                    <p class="mb-1">
                        <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                        <span class="text-xs text-gray-500">({{ $comment->created_at->format('d/m/Y H:i') }})</span>
                    </p>
                @endforeach
            </div>

            <form method="POST" action="{{ route('comments.store') }}" class="mt-3">
                @csrf
                <input type="hidden" name="commentable_id" value="{{ $session->id }}">
                <input type="hidden" name="commentable_type" value="App\Models\RunningSession">

                <textarea name="content" class="w-full border rounded p-2" rows="2" placeholder="Ecrivez votre commentaire"></textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1"> {{ $message }} </p>
                @enderror

                <button class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">Publier</button>
            </form>


        </div>
    </div>
</x-app-layout>
