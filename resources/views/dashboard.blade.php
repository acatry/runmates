<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Fil d'actualité</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
    
        <h2 class="text-xl font-bold mb-2">Mes prochains événements</h2>
        @forelse($events as $event)
            <div class="p-3 bg-white rounded shadow mb-2">
                <strong>{{ $event->title }}</strong><br>
                <span class="text-sm text-gray-600">
                    {{ $event->start_at->format('d/m/Y H:i') }} — {{ $event->location }}
                </span>
            </div>
        @empty
            <p class="text-gray-600 text-sm">Aucun événement à venir.</p>
        @endforelse
    
        <h2 class="text-xl font-bold mt-6 mb-2">Mes sessions d’entraînement</h2>
        @forelse($sessions as $session)
            <div class="p-3 bg-white rounded shadow mb-2">
                <strong>{{ $session->title }}</strong><br>
                <span class="text-sm text-gray-600">
                    {{ $session->start_at->format('d/m/Y H:i') }} — {{ $session->location }}
                </span>
            </div>
        @empty
            <p class="text-gray-600 text-sm">Aucune session à venir.</p>
        @endforelse
    
    </div>

</x-app-layout>
