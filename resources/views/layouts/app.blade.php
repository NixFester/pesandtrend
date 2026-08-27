<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pesantrends — Platform terpercaya untuk menemukan, membandingkan, dan memilih sekolah Islam terbaik di Indonesia.">
    <title>@yield('title', 'Pesantrends — Temukan Sekolah Islam Terbaik')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-white">
    @include('components.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')
</body>
</html>
