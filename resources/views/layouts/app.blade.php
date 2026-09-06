<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-app-head :title="$title ?? null" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-white">
    {{-- Accessibility: skip to main content --}}
    <x-skip-link href="#main-content" />

    @include('components.navbar')

    <main id="main-content" tabindex="-1" class="flex-1 {{ request()->routeIs('home') ? '' : 'pt-16' }}">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('components.footer')
    <x-mobile-bottom-nav />
    @stack('scripts')
    @livewireScripts
</body>
</html>
