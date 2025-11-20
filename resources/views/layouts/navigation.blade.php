<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-800 border border-gray-600">
                            <span class="text-sm font-bold text-gray-200 tracking-tight">
                                RM
                            </span>
                        </div>
                        <span class="hidden text-sm font-semibold text-gray-800 sm:inline">
                            Runmates
                        </span>
                    </a>

                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-10 sm:flex sm:items-center gap-2 nav-main">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Accueil') }}
                    </x-nav-link>
                    @php
                        $eventsActive = request()->routeIs('events.*');
                    @endphp
                    <x-dropdown align="left">
                        <x-slot name="trigger">
                            <button class="{{ $eventsActive ? 'bg-gray-200 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }} inline-flex items-center px-3 py-2 text-sm rounded-full">
                                Événements
                                <x-icon-chevron-down class="ms-1 w-4 h-4" />
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link href="{{ route('events.index') }}">
                                Consulter
                            </x-dropdown-link>

                            @if (auth()->user()->isOrganizer())
                                <x-dropdown-link href="{{ route('events.create') }}">
                                    Créer
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>

                    <x-dropdown align="left">
                        @php
                            $sessionsActive = request()->routeIs('running-sessions.*');
                        @endphp
                        <x-slot name="trigger">
                            <button class="{{ $sessionsActive ? 'bg-gray-200 text-gray-900' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }} inline-flex items-center px-3 py-2 text-sm rounded-full">
                                Sessions d’entraînement
                                <x-icon-chevron-down class="ms-1 w-4 h-4" />
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link href="{{ route('running-sessions.index') }}">
                                Consulter
                            </x-dropdown-link>

                            @if (auth()->user()->isSporty())
                                <x-dropdown-link href="{{ route('running-sessions.create') }}">
                                    Créer
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>

                    <x-nav-link 
                        :href="route('runner.profile', auth()->id())" :active="request()->routeIs('runner.profile')">
                        {{ __('Mon profil') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200">
                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden text-xs font-semibold text-gray-700">
                                @php
                                    $authUser = Auth::user();
                                @endphp
                                @if($authUser->profile_photo_path)
                                    <img src="{{ asset('storage/' . $authUser->profile_photo_path) }}"
                                         class="h-full w-full object-cover">
                                @else
                                    {{ strtoupper(substr($authUser->name, 0, 2)) }}
                                @endif
                            </div>
                        
                            <span class="hidden sm:inline">{{ $authUser->name }}</span>
                        </button>

                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Modifier mon profil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <x-dropdown align="right" width="w-80">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center justify-center rounded-full bg-gray-100 px-3 py-2 text-gray-700 text-sm hover:bg-gray-200">
                            <div>🔔 Notifications</div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @php
                            $notifications = auth()->user()?->notifications()->limit(5)->get() ?? collect();
                            $totalCount = auth()->user()?->notifications()->count() ?? 0;
                        @endphp

                        @if ($notifications->isEmpty())
                            <div class="px-4 py-2 text-sm text-gray-500">
                                Aucune notification pour le moment.
                            </div>
                        @else
                            @foreach ($notifications as $notif)
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    {{ $notif->message ?? $notif->content ?? 'Notification' }}
                                    <div class="text-xs text-gray-400">
                                        {{ $notif->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if($totalCount > 5)
                            <a href="{{ route('notifications.index') }}"
                               class="block text-center text-blue-600 hover:underline px-4 py-2 text-sm">
                                (+5) Voir toutes les notifications
                            </a>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="pt-2 pb-3 space-y-1">     
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Accueil
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')">
                Événements
            </x-responsive-nav-link>
            @if (auth()->user()->isOrganizer())
                <x-responsive-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')">
                    Créer un évènement
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('running-sessions.index')" :active="request()->routeIs('running-sessions.index')">
                Sessions d'entraînement
            </x-responsive-nav-link>
            @if (auth()->user()->isSporty())
                <x-responsive-nav-link :href="route('running-sessions.create')" :active="request()->routeIs('running-sessions.create')">
                    Créer une session
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('runner.profile', auth()->id())" :active="request()->routeIs('runner.profile')">
                Mon profil
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')">
                Notifications
            </x-responsive-nav-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Se déconnecter
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
