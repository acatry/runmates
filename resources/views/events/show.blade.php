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
                <!-- Bouton d'inscription -->
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
                            <button class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
                                Se désinscrire de la course
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('events.register', $event) }}">
                            @csrf
                            <button class="px-3 py-1 rounded bg-emerald-600 text-white hover:bg-emerald-500">
                                S’inscrire à la course
                            </button>
                        </form>
                    @endif
                    @if(auth()->id() === $event->organizer_id)
                        <div class="flex gap-2">
                        <a href="{{ route('events.edit', $event) }}"
                           class="inline-block px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-300 text-sm">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('events.delete', $event) }}"
                              class="inline-block"
                              onsubmit="return confirm('Supprimer cet évènement ?');">
                            @csrf
                            @method('DELETE')
                           <button class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-300 text-sm">
                                Supprimer
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Pour les sportifs, on affiche les besoins en bénévoles -->
                @if(auth()->check() && auth()->user()->isSporty() && $event->volunteers_needed)
                    @if(($roles = $event->volunteerRoles)->isNotEmpty())
                        <div class="bg-white p-6 rounded shadow">
                            <h3 class="font-semibold mb-3">Besoins en bénévoles</h3>

                            <ul class="list-disc pl-6 space-y-1">
                                @foreach($roles as $role)
                                    <li>
                                        {{ $role->name }}
                                        @if($role->max_slots)
                                            <span class="text-sm text-gray-600">
                                                (max {{ $role->max_slots }} bénévole{{ $role->max_slots > 1 ? 's' : '' }})
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif


            <!-- Liste des participants -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-3">
                    <span class="text-sm text-gray-600">
                    {{ $attendeeCount }} inscrit{{ $attendeeCount > 1 ? 's' : '' }} :
                    </span>
                </h3>
                @php
                    $attendees = $event->attendees()->orderBy('name')->get();
                @endphp

                @if($attendees->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun participant pour le moment.</p>
                @else
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach($attendees as $user)
                            <li>
                                <a href="{{ route('runner.profile', $user->id) }}" class="text-blue-600 hover:underline">
                                    {{ $user->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Commentaires -->
            <h4 class="font-semibold mb-2">Commentaires :</h4>
            <div class="bg-white p-4 rounded shadow mt-6">
                <!-- Liste des commentaires -->
               @foreach($event->comments as $comment)
                    <p class="mb-1">
                        <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                        <span class="text-xs text-gray-500">({{ $comment->created_at->format('d/m/Y H:i') }})</span>
                    </p>
                @endforeach
            </div>
            
            <form method="POST" action="{{ route('comments.store') }}" class="mt-3">
                @csrf
                <input type="hidden" name="commentable_id" value="{{ $event->id }}">
                <input type="hidden" name="commentable_type" value="App\Models\Event">
            
                <textarea name="content" class="w-full border rounded p-2" rows="2" placeholder="Ecrivez votre commentaire"></textarea>
            
                <button class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">Publier</button>
            </form>

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
