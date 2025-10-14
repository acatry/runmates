<x-guest-layout>
  <div class="min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md w-full text-center space-y-6">
      <h1 class="text-2xl font-bold">Bienvenue sur RunMates</h1>

      <div class="flex items-center justify-center gap-3">
        <a href="{{ route('login') }}"
           class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-700">
          Se connecter
        </a>
        <a href="{{ route('register') }}"
           class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-500">
          S’inscrire
        </a>
      </div>
    </div>
  </div>
</x-guest-layout>
