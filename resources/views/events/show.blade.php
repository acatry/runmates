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
                    @if(auth()->user()->isSporty())
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
            
            <!-- Pour les sportifs, on affiche les besoins en bénévoles avec possibilité de s'inscrire -->
            <!-- Pour les organisateurs, on on leur donne la possibilité de contacter les bénévoles par mail pour chaque rôle -->
                @if(auth()->check() && auth()->user()->isSporty() || auth()->user()->isOrganizer()  && $event->volunteers_needed)
                    @if(($roles = $event->volunteerRoles)->isNotEmpty())
                        <div class="bg-white p-6 rounded shadow">
                            <h3 class="font-semibold mb-3">Besoins en bénévoles</h3>
                                @php
                                    $currentRoleId = DB::table('event_volunteers')
                                        ->where('event_id', $event->id)
                                        ->where('user_id', auth()->id())
                                        ->value('volunteer_role_id');
                                @endphp

                            <ul class="list-disc pl-6 space-y-1">
                                @foreach($roles as $role)
                                    <li class="flex items-center justify-between">
                                        <div>
                                        {{ $role->name }}
                                        @php
                                            // Nombre d'inscrits sur ce rôle
                                            $count = DB::table('event_volunteers')
                                                ->where('event_id', $event->id)
                                                ->where('volunteer_role_id', $role->id)
                                                ->count();

                                            $isFull = $role->max_slots && $count >= $role->max_slots;
                                        @endphp
                                        @if($role->max_slots)
                                            <span class="text-sm text-gray-600">
                                                ({{ $count }} / {{ $role->max_slots }} bénévoles)
                                            </span>
                                        @endif
                                        @if($isFull)
                                            <span class="ml-2 text-red-600 font-semibold text-sm">
                                                COMPLET
                                            </span>
                                        @endif
                                        </div>
                                        @if(auth()->user()->isSporty())
                                            <div>
                                                @if($currentRoleId === $role->id)
                                                    <form method="POST" action="{{ route('events.volunteers.delete', $event) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="px-3 py-0 bg-gray-200 rounded text-sm">
                                                            Se désinscrire
                                                        </button>
                                                    </form>
                                                @else
                                                    @unless($isFull)
                                                        <form method="POST" action="{{ route('events.volunteers.store', $event) }}">
                                                            @csrf
                                                            <input type="hidden" name="volunteer_role_id" value="{{ $role->id }}">
                                                            <button class="px-3 py-0 bg-emerald-600 text-white rounded text-sm hover:bg-emerald-500">
                                                                S’inscrire
                                                            </button>
                                                        </form>
                                                    @endunless
                                                @endif
                                            </div>
                                        @endif

                                        @if(auth()->user()->isOrganizer() && auth()->id() === $event->organizer_id)
                                            @php
                                                $eventVolunteers = DB::table('event_volunteers') 
                                                    ->join('users', 'event_volunteers.user_id', '=', 'users.id') 
                                                    ->where('event_volunteers.event_id', $event->id) 
                                                    ->where('event_volunteers.volunteer_role_id', $role->id) 
                                                    ->select('users.name', 'users.email') 
                                                    ->get(); 

                                                $volunteerEmails = $eventVolunteers->pluck('email')->toArray(); 
                                                $sendMailTo = count($volunteerEmails) > 0 ? 'mailto:'.implode(',', $volunteerEmails) : null;
                                            @endphp

                                            @if($sendMailTo)
                                                <div class="ml-4 text-right text-xs">
                                                    <a href="{{ $sendMailTo }}"
                                                       class="px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-500 whitespace-nowrap">
                                                        Contacter les bénévoles {{ $role->name }}
                                                    </a>

                                                    @if($eventVolunteers->count() > 0)
                                                        <div class="mt-1 text-gray-600">
                                                            <div x-data="{ open: false }" class="mt-2">                                               
                                                                <button 
                                                                    @click="open = !open" 
                                                                    class="text-blue-600 underline hover:text-blue-800"
                                                                >
                                                                    <span x-show="!open">Afficher les bénévoles</span>
                                                                    <span x-show="open">Masquer les bénévoles</span>
                                                                </button>
                                                                <div x-show="open" class="mt-1 text-gray-600">
                                                                    @foreach($eventVolunteers as $ev)
                                                                        - {{ $ev->name }} ({{ $ev->email }})<br>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
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
                    <p class="text-gray-500 text-sm">Aucun participant à la course pour le moment.</p>
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
                    Retour à la liste
                </a>
                <a href="{{ route('events.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                    Créer un autre événement
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
