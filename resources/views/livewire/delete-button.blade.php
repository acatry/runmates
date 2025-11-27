<div>
    <button
        type="button"
        wire:click="confirmDelete"
        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 text-sm">
        Supprimer
    </button>

    @if($confirm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-2xl shadow w-80">
                <p class="text-gray-600 mb-4">
                    Voulez-vous vraiment supprimer cet élément ?
                </p>

                <div class="flex justify-end gap-2">
                    <button type="button"
                            wire:click="$set('confirming', false)"
                            class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm">
                        Non
                    </button>

                    <button type="button"
                            wire:click="delete"
                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 text-sm">
                        Oui
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
