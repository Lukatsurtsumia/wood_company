<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Wood Company') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 font-semibold text-lg text-amber-900">
                    <x-application-logo class="h-8 w-auto fill-current text-amber-800" />
                    {{ config('app.name', 'Wood Company') }}
                </a>

                <nav class="flex items-center gap-6 text-sm font-medium text-gray-600">
                    <a href="{{ route('products.index') }}" wire:navigate class="hover:text-amber-800 {{ request()->routeIs('products.*') ? 'text-amber-800' : '' }}">
                        Products
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-amber-800">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="hover:text-amber-800">Log in</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-gray-100 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-sm text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'Wood Company') }}. All rights reserved.
            </div>
        </footer>
    </body>
</html>
