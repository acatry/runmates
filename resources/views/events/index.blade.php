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

            @if($events->count() === 0)
                <div class="bg-white p-6 rounded shadow">Aucun événement pour l’instant.</div>
            @else
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($events as $event)
                        <a href="{{ route('events.show', $event) }}"
                           class="block bg-white p-4 rounded shadow hover:shadow-md transition">
                            <h3 class="font-semibold text-lg">{{ $event->title }}</h3>
                            <p class="text-gray-600 text-sm">
                                {{ $event->location }} — {{ $event->start_at->format('d/m/Y H:i') }}
                            </p>
                        </a>
                    @endforeach
                </div>
                <div>{{ $events->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
