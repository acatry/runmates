<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Vos notifications</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8">
        <div class="bg-white p-6 rounded shadow">

            @if ($notifications->isEmpty())
                <p class="text-gray-500">Aucune notification pour le moment.</p>
            @else
                @foreach ($notifications as $notif)
                    <div class="border-b py-2">
                        <div>{{ $notif->message ?? $notif->content ?? 'Notification' }}</div>
                        <small class="text-gray-500">
                            {{ $notif->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                @endforeach
            @endif

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
