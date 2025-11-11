<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Modifier un événement</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('events.update', $event) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block font-semibold">Titre</label>
                        <input type="text" id="title" name="title"
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('title', $event->title) }}" required>
                        @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="description" class="block font-semibold">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border rounded px-3 py-2" required>{{ old('description', $event->description) }}</textarea>
                        @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="location" class="block font-semibold">Lieu</label>
                        <input type="text" id="location" name="location"
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('location', $event->location) }}" required>
                        @error('location')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="start_at" class="block font-semibold">Date et heure</label>
                        <input type="datetime-local" id="start_at" name="start_at"
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('start_at', $event->start_at ? $event->start_at->format('Y-m-d\TH:i') : '') }}" required>
                        @error('start_at')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('events.show', $event) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                            Annuler
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
