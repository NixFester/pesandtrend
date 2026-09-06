<<<<<<< Updated upstream
@php
    $isHome = request()->routeIs('home');
@endphp
<header id="main-header" class="group fixed inset-x-0 top-0 z-50 transition-all duration-300 {{ $isHome ? 'bg-transparent border-transparent' : 'bg-white/90 backdrop-blur-md border-b border-forest-100/70' }}" data-is-home="{{ $isHome ? 'true' : 'false' }}" {!! $isHome ? 'data-scrolled="false"' : '' !!}>
=======
{{-- Main sticky navbar with animated mobile menu disclosure (vanilla JS, no Alpine) --}}
<header
    data-header
    class="sticky top-0 z-50 border-b border-forest-100/70 bg-white/90 backdrop-blur-md"
>
>>>>>>> Stashed changes
    <nav class="container-app flex h-16 items-center justify-between gap-4" aria-label="Navigasi utama">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Pesantrends — Beranda">
<<<<<<< Updated upstream
            <img src="{{ asset('images/icon.svg') }}" alt="Icon Pesantrends" class="h-9 w-auto">
            @if($isHome)
                <img src="{{ asset('images/logo.svg') }}" alt="Logo Pesantrends" class="h-5 w-auto hidden sm:block sm:group-data-[scrolled=true]:hidden">
                <img src="{{ asset('images/logo-green.svg') }}" alt="Logo Pesantrends" class="h-5 w-auto hidden sm:block sm:group-data-[scrolled=false]:hidden">
            @else
                <img src="{{ asset('images/logo-green.svg') }}" alt="Logo Pesantrends" class="h-5 w-auto hidden sm:block">
            @endif
        </a>

        <div class="hidden items-center gap-1 lg:flex">
            @php
                $navLinkClass = $isHome 
                    ? 'text-white/90 hover:text-white hover:bg-white/10 group-data-[scrolled=true]:text-ink-soft group-data-[scrolled=true]:hover:bg-forest-50 group-data-[scrolled=true]:hover:text-forest-900' 
                    : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900';
                $navActiveClass = $isHome 
                    ? 'bg-white/20 text-white group-data-[scrolled=true]:bg-forest-50 group-data-[scrolled=true]:text-forest-900' 
                    : 'bg-forest-50 text-forest-900';
            @endphp
            <a href="{{ route('home') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('home') ? $navActiveClass : $navLinkClass }}">Beranda</a>
            <a href="{{ route('schools.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('schools.*') ? $navActiveClass : $navLinkClass }}">Cari Sekolah</a>
            <a href="{{ route('compare.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('compare.*') ? $navActiveClass : $navLinkClass }}">Bandingkan</a>
            <a href="{{ route('articles.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('articles.*') ? $navActiveClass : $navLinkClass }}">Artikel</a>
=======
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-forest-900 text-gold-400">
                <x-app-icon name="book-open" class="h-5 w-5" :stroke="2.2"/>
            </span>
            <span class="text-lg font-extrabold tracking-tight text-forest-900">Pesant<span class="text-gold-600">rends</span>
            </span>
        </a>

        {{-- Desktop nav links — shown from md (768px) up --}}
        <div class="hidden items-center gap-1 md:flex">
            <a href="{{ route('home') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('home') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Beranda</a>
            <a href="{{ route('schools.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('schools.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Cari Sekolah</a>
            <a href="{{ route('compare.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('compare.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Bandingkan</a>
            <a href="{{ route('articles.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('articles.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Artikel</a>
>>>>>>> Stashed changes
        </div>

        {{-- Desktop CTA cluster — shown from lg (1024px) up --}}
        <div class="hidden items-center gap-3 lg:flex">
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-xl bg-forest-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-forest-700">
                    <x-app-icon name="user" class="h-4 w-4"/>
                    {{ Str::before(auth()->user()->name, ' ') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $isHome ? 'text-white hover:bg-white/10 group-data-[scrolled=true]:text-forest-900 group-data-[scrolled=true]:hover:bg-forest-50' : 'text-forest-900 hover:bg-forest-50' }}">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary !px-4 !py-2.5">Daftar Gratis</a>
            @endauth
        </div>

<<<<<<< Updated upstream
        <button id="menu-toggle" class="rounded-lg p-2 transition lg:hidden {{ $isHome ? 'text-white hover:bg-white/10 group-data-[scrolled=true]:text-forest-900 group-data-[scrolled=true]:hover:bg-forest-50' : 'text-forest-900 hover:bg-forest-50' }}" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
            <x-app-icon name="menu" class="h-6 w-6"/>
=======
        {{-- Mobile hamburger with menu→X morph via data-state toggle --}}
        <button
            id="menu-toggle"
            type="button"
            class="flex h-10 w-10 items-center justify-center rounded-lg text-forest-900 transition hover:bg-forest-50 md:hidden"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="mobile-menu"
            data-menu-toggle
        >
            <span class="relative h-5 w-5" aria-hidden="true">
                {{-- Hamburger icon (visible when closed) --}}
                <svg data-icon-menu class="absolute inset-0 h-5 w-5 transition-all duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="6" y2="6"/>
                    <line x1="4" x2="20" y1="12" y2="12"/>
                    <line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
                {{-- X icon (visible when open) --}}
                <svg data-icon-close class="absolute inset-0 h-5 w-5 hidden transition-all duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </span>
>>>>>>> Stashed changes
        </button>
    </nav>

    {{-- Mobile menu — animated disclosure (CSS via .menu-panel utility, JS via nav.js) --}}
    <div
        id="mobile-menu"
        class="menu-panel overflow-hidden border-t border-forest-100 bg-white px-4 safe-top transition-all duration-200 ease-snappy md:hidden"
        data-menu-panel
    >
        <div class="flex flex-col gap-1 pb-5" data-menu-links>
            <a href="{{ route('home') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('home') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Beranda</a>
            <a href="{{ route('schools.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('schools.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Cari Sekolah</a>
            <a href="{{ route('compare.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('compare.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Bandingkan Sekolah</a>
            <a href="{{ route('articles.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('articles.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Artikel</a>
            <a href="{{ route('calculator.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('calculator.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Kalkulator Biaya</a>
        </div>
        <div class="flex gap-3 pb-1">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary flex-1">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-outline flex-1">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary flex-1">Daftar Gratis</a>
            @endauth
        </div>
    </div>
</header>
