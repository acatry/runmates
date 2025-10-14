<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $event->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600">
                    {{ $event->location }} — {{ $event->start_at->format('d/m/Y H:i') }}
                </p>
                <div class="mt-4 whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('events.index') }}" class="px-3 py-2 bg-gray-100 rounded">← Retour</a>
                <a href="{{ route('events.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">
                    Créer un autre événement
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
