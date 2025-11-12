<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Créer un événement</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('events.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="title" class="block font-semibold">Titre</label>
                        <input type="text" id="title" name="title" class="w-full border rounded px-3 py-2" required>
                        @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="description" class="block font-semibold">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
                        @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="location" class="block font-semibold">Lieu</label>
                        <input type="text" id="location" name="location" class="w-full border rounded px-3 py-2" required>
                        @error('location')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="start_at" class="block font-semibold">Date et heure</label>
                        <input type="datetime-local" id="start_at" name="start_at" class="w-full border rounded px-3 py-2" required>
                        @error('start_at')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <span class="block font-semibold">Cherchez-vous des bénévoles ?</span>
                        <div class="mt-2">
                            <label class="inline-flex items-center gap-2 ml-4">
                                <input type="radio" name="volunteers_needed" value="1" id="volunteers_yes">
                                <span>Oui</span>
                            </label>

                            <label class="inline-flex items-center gap-2 ml-4">
                                <input type="radio" name="volunteers_needed" value="0" id="volunteers_no" checked>
                                <span>Non</span>
                            </label>
                        </div>
                    </div>

                    <div id="volunteer_roles_box" class="mt-4" style="display: none;">
                        <p class="font-semibold mb-2">Types de bénévoles recherchés :</p>

                        @php
                            $roles = [
                                'Ravitaillement',
                                'Signaleur',
                                'Ouverture / fermeture de course',
                                'Distribution de dossards',
                                'Sécurité du parcours',
                                'Photographe'
                            ];
                        @endphp
                        
                        @foreach($roles as $i => $role)
                            <div class="flex items-center gap-3 mb-2">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="roles[{{ $i }}]" value="{{ $role }}">
                                    <span>{{ $role }}</span>
                                </label>
                                                
                                <input
                                    type="number"
                                    name="max[{{ $i }}]"
                                    placeholder="Maximum"
                                    class="border rounded px-2 py-1 w-24"
                                    min="1"
                                >
                            </div>
                        @endforeach

                    </div>
                        
                    <!-- JS pour afficher/masquer la section bénévoles -->
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const yesRadio = document.getElementById('volunteers_yes');
                        const noRadio  = document.getElementById('volunteers_no');
                        const box      = document.getElementById('volunteer_roles_box');
                    
                        function toggleBox() {
                            box.style.display = yesRadio.checked ? 'block' : 'none';
                        }
                        toggleBox();                    
                        yesRadio.addEventListener('change', toggleBox);
                        noRadio.addEventListener('change', toggleBox);
                    });
                    </script>

                    <div class="text-right">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">
                            Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
