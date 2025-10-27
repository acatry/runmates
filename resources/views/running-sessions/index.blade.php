{{-- resources/views/running-sessions/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Liste des sessions d’entraînement
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Barre de recherche simple --}}
            <form method="GET" class="mb-6 flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-full"
                       placeholder="Rechercher (titre, ville, lieu)">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">Rechercher</button>
            </form>

            {{-- Lien vers le formulaire de création --}}
            <div class="mb-4 text-right">
                <a href="{{ route('running-sessions.create') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                    + Publier une session
                </a>
            </div>

            {{-- Si aucune session trouvée --}}
            @if($sessions->count() === 0)
                <div class="bg-white p-6 rounded shadow text-center text-gray-600">
                    Aucune session trouvée pour le moment.
                </div>
            @else
                {{-- Liste des sessions --}}
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($sessions as $session)
                        @php
                            // Vérifie si l'utilisateur est inscrit à cette session
                            $isRegistered = auth()->check() && $session->attendees()
                                ->where('users.id', auth()->id())->exists();
                        @endphp

                        <div class="bg-white p-4 rounded shadow">
                            <h3 class="font-bold text-lg">{{ $session->title }}</h3>
                            <p class="text-sm text-gray-600">
                                 {{ $session->location }} ({{ $session->city ?? 'Ville inconnue' }})<br>
                                 {{ $session->start_at->format('d/m/Y H:i') }}
                            </p>

                            {{-- Détails supplémentaires --}}
                            <p class="mt-2 text-sm text-gray-500">
                                Distance : {{ $session->distance_km_min ?? '?' }} - {{ $session->distance_km_max ?? '?' }} km<br>
                                Allure : {{ $session->pace_min_per_km_min ?? '?' }} - {{ $session->pace_min_per_km_max ?? '?' }} min/km
                            </p>

                            {{-- Boutons d'inscription / désinscription --}}
                            <div class="mt-3 flex items-center gap-2">
                                @if($isRegistered)
                                    <form method="POST" action="{{ route('running-sessions.leave', $session) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                                            Se désinscrire
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('running-sessions.join', $session) }}">
                                        @csrf
                                        <button class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-500 text-sm">
                                            S’inscrire
                                        </button>
                                    </form>
                                @endif

                                <span class="text-xs text-gray-500">
                                    {{ $session->attendees()->count() }} participant(s)
                                </span>
                            </div>

                            {{-- Lien vers la page de détails --}}
                            <a href="{{ route('running-sessions.show', $session) }}"
                               class="inline-block mt-3 text-indigo-600 hover:underline text-sm">
                                Voir les détails
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
