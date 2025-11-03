<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Événements</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Barre de recherche --}}
            <form method="GET" class="mb-6 flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       class="border rounded px-3 py-2 w-full"
                       placeholder="Rechercher (titre, lieu)">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Rechercher
                </button>
            </form>

            {{-- Bouton pour créer un évènement --}}
            <div class="mb-4 text-right">
                <a href="{{ route('events.create') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                    + Publier un événement
                </a>
            </div>

            {{-- Si aucun événement --}}
            @if($events->isEmpty())
                <div class="bg-white p-6 rounded shadow text-center text-gray-600">
                    Aucun événement pour l’instant.
                </div>
            @else

                {{-- Liste des événements (grid) --}}
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($events as $event)
                        @php
                            $isRegistered = auth()->user()
                                ? $event->attendees()->where('users.id', auth()->id())->exists()
                                : false;
                        @endphp

                        <div class="bg-white p-4 rounded shadow hover:shadow-md transition">
                            <h3 class="font-bold text-lg">
                                <a href="{{ route('events.show', $event) }}" class="hover:underline">
                                    {{ $event->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $event->location }}<br>
                                {{ $event->start_at->format('d/m/Y H:i') }}
                            </p>

                            {{-- Boutons inscription / désinscription --}}
                            <div class="mt-3 flex items-center gap-2">
                                @if($isRegistered)
                                    <form method="POST" action="{{ route('events.unregister', $event) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                                            Se désinscrire
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('events.register', $event) }}">
                                        @csrf
                                        <button class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-500 text-sm">
                                            S’inscrire
                                        </button>
                                    </form>
                                @endif

                                <span class="text-xs text-gray-500">
                                    {{ $event->attendees()->count() }} inscrit(s)
                                </span>
                            </div>

                            {{-- Lien vers détails --}}
                            <a href="{{ route('events.show', $event) }}"
                               class="inline-block mt-3 text-indigo-600 hover:underline text-sm">
                                Voir les détails
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
