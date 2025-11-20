<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-primary font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <main class="flex-1">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                    @isset($header)
                        <header class="mb-4">
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-800">
                                {{ $header }}
                            </h1>
                        </header>
                    @endisset

                    @if (session('success'))
                        <div class="mb-4">
                            <div class="rounded-xl border border-emerald-300 bg-emerald-100 px-4 py-3 text-sm text-emerald-800">
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-xl shadow-gray-300/30">
                        <div class="p-4 sm:p-6 lg:p-8">
                            {{ $slot }}
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </body>
</html>
