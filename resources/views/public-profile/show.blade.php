<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">

        <div class="bg-white p-6 rounded shadow">

            {{-- Photo --}}
            @if($user->profile_photo_path)
                <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                     class="w-24 h-24 rounded-full object-cover mx-auto mb-4">
            @else
                <div class="w-24 h-24 bg-gray-300 rounded-full mx-auto mb-4"></div>
            @endif

            {{-- Nom --}}
            <h2 class="text-2xl font-bold text-center mb-1">{{ $user->name }}</h2>

            {{-- Ville / âge --}}
            <p class="text-center text-gray-600 mb-4">
                @if($user->city) {{ $user->city }} @endif
                @if($user->age) · {{ $user->age }} ans @endif
            </p>

            {{-- Description --}}
            @if($user->description)
                <p class="text-gray-700 mb-6 text-center">{{ $user->description }}</p>
            @endif

            {{-- Sessions à venir --}}
            <h3 class="text-xl font-semibold mb-2">Prochains entraînements</h3>

            @forelse($futureSessions as $session)
                <a href="{{ route('running-sessions.show', $session) }}"
                   class="block p-3 border rounded mb-2 hover:bg-gray-50">
                    <div class="font-medium">{{ $session->title }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $session->start_at->format('d/m/Y H:i') }} —
                        {{ $session->city ?? 'Lieu à venir' }}
                    </div>
                </a>
            @empty
                <p class="text-gray-500">Aucun entraînement prévu.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
