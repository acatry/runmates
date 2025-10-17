<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $event->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Détails de l'événement -->
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-600 text-sm mb-2">
                    <strong>Lieu :</strong> {{ $event->location }}<br>
                    <strong>Date :</strong> {{ $event->start_at->format('d/m/Y H:i') }}
                </p>

                <div class="mt-4">
                    <h3 class="font-semibold mb-1">Description</h3>
                    <p class="whitespace-pre-line text-gray-700">
                        {{ $event->description }}
                    </p>
                </div>
            </div>

            <!-- Bouton d'inscription -->
            <div class="bg-white p-6 rounded shadow flex items-center justify-between">
                @php
                    $isRegistered = auth()->user()
                        ? $event->attendees()->where('users.id', auth()->id())->exists()
                        : false;
                    $attendeeCount = $event->attendees()->count();
                @endphp

                <div>
                    @if($isRegistered)
                        <form method="POST" action="{{ route('events.unregister', $event) }}">
                            @csrf @method('DELETE')
                            <button class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                                Se désinscrire
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('events.register', $event) }}">
                            @csrf
                            <button class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-500">
                                S’inscrire
                            </button>
                        </form>
                    @endif
                </div>

                <span class="text-sm text-gray-600">
                    {{ $attendeeCount }} inscrit{{ $attendeeCount > 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Liste des participants -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-3">Participants</h3>
                @php
                    $attendees = $event->attendees()->orderBy('name')->get();
                @endphp

                @if($attendees->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun participant pour le moment.</p>
                @else
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach($attendees as $user)
                            <li>
                                {{ $user->name }}
                                <span class="text-gray-400 text-sm">({{ $user->email }})</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Retour -->
            <div class="flex justify-between">
                <a href="{{ route('events.index') }}" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">
                    ← Retour à la liste
                </a>
                <a href="{{ route('events.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                    Créer un autre événement
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
