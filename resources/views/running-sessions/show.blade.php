{{-- resources/views/running-sessions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Détails de la session
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">

            <h3 class="text-2xl font-bold mb-2">{{ $session->title }}</h3>
            <p class="text-gray-600 mb-4">{{ $session->description }}</p>

            <p class="text-sm text-gray-700 mb-2">
                <strong>Lieu :</strong> {{ $session->location }} ({{ $session->city ?? '-' }})<br>
                <strong>Date :</strong> {{ $session->start_at->format('d/m/Y H:i') }}
            </p>

            <p class="text-sm text-gray-700 mb-4">
                Distance : {{ $session->distance_km_min ?? '?' }} - {{ $session->distance_km_max ?? '?' }} km<br>
                Allure : {{ $session->pace_min_per_km_min ?? '?' }} - {{ $session->pace_min_per_km_max ?? '?' }} min/km
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
                    <li>{{ $user->name }}</li>
                @empty
                    <li>Aucun participant pour le moment.</li>
                @endforelse
            </ul>

        </div>
    </div>
</x-app-layout>
