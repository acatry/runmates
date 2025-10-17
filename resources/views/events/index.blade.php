<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Événements</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex justify-between items-center">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="border rounded px-3 py-2" placeholder="Rechercher (titre, lieu)">
                    <button class="px-3 py-2 bg-gray-800 text-white rounded">Filtrer</button>
                </form>
                <a href="{{ route('events.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">
                    Créer un événement
                </a>
            </div>

            @if($events->isEmpty())
                <div class="bg-white p-6 rounded shadow">Aucun événement pour l’instant.</div>
            @else
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($events as $event)
                        @php
                            $isRegistered = auth()->user()
                                ? $event->attendees()->where('users.id', auth()->id())->exists()
                                : false;
                        @endphp

                        <div class="bg-white p-4 rounded shadow hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <h3 class="font-semibold text-lg">
                                    <a href="{{ route('events.show', $event) }}" class="hover:underline">
                                        {{ $event->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-3">
                                    {{ $event->location }} — {{ $event->start_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 mt-auto">
                                @if($isRegistered)
                                    <form method="POST" action="{{ route('events.unregister', $event) }}">
                                        @csrf @method('DELETE')
                                        <button class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-gray-300">
                                            Se désinscrire
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('events.register', $event) }}">
                                        @csrf
                                        <button class="px-3 py-1 text-sm rounded bg-emerald-600 text-white hover:bg-emerald-500">
                                            S’inscrire
                                        </button>
                                    </form>
                                @endif

                                <span class="text-xs text-gray-500">
                                    {{ $event->attendees()->count() }} inscrit(s)
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
