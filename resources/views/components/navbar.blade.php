@php
    $isHome = request()->routeIs('home');
@endphp
<header id="main-header" class="group fixed inset-x-0 top-0 z-50 transition-all duration-300 {{ $isHome ? 'bg-transparent border-transparent' : 'bg-white/90 backdrop-blur-md border-b border-forest-100/70' }}" data-is-home="{{ $isHome ? 'true' : 'false' }}" {!! $isHome ? 'data-scrolled="false"' : '' !!}>
    <nav class="container-app flex h-16 items-center justify-between gap-4" aria-label="Navigasi utama">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Pesantrends — Beranda">
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
            <a href="{{ route('donations.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('donations.*') ? $navActiveClass : $navLinkClass }}">Bantu Pesantren</a>
            <a href="{{ route('bimbel.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('bimbel.*') ? $navActiveClass : $navLinkClass }}">Bimbel</a>
            <a href="{{ route('articles.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('articles.*') ? $navActiveClass : $navLinkClass }}">Artikel</a>
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

        <button id="menu-toggle" data-menu-toggle class="rounded-lg p-2 transition lg:hidden {{ $isHome ? 'text-white hover:bg-white/10 group-data-[scrolled=true]:text-forest-900 group-data-[scrolled=true]:hover:bg-forest-50' : 'text-forest-900 hover:bg-forest-50' }}" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
            <span data-icon-menu class="block">
                <x-app-icon name="menu" class="h-6 w-6"/>
            </span>
            <span data-icon-close class="hidden">
                <x-app-icon name="x" class="h-6 w-6"/>
            </span>
        </button>
    </nav>

    {{-- Mobile menu — animated disclosure (CSS via .menu-panel utility, JS via nav.js) --}}
    <div
        id="mobile-menu"
        class="menu-panel overflow-hidden border-t border-forest-100 bg-white px-4 safe-top transition-all duration-200 ease-snappy lg:hidden"
        data-menu-panel
    >
        <div class="space-y-4 py-4 max-h-[calc(85dvh-1rem)] overflow-y-auto" data-menu-links>
            <div>
                <p class="px-3 text-[11px] font-extrabold uppercase tracking-wider text-ink-soft/70">Menu Utama</p>
                <div class="mt-1 flex flex-col gap-0.5">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('home') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="home" class="h-4 w-4 {{ request()->routeIs('home') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('schools.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('schools.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="search" class="h-4 w-4 {{ request()->routeIs('schools.*') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Cari Sekolah</span>
                    </a>
                    <a href="{{ route('donations.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('donations.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="heart" class="h-4 w-4 {{ request()->routeIs('donations.*') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Bantu Pesantren</span>
                    </a>
                    <a href="{{ route('bimbel.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('bimbel.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="graduation-cap" class="h-4 w-4 {{ request()->routeIs('bimbel.*') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Bimbel</span>
                    </a>
                    <a href="{{ route('articles.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('articles.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="newspaper" class="h-4 w-4 {{ request()->routeIs('articles.*') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Artikel</span>
                    </a>
                </div>
            </div>

            <div class="border-t border-forest-100/70 pt-3">
                <p class="px-3 text-[11px] font-extrabold uppercase tracking-wider text-ink-soft/70">Eksplorasi &amp; Fitur</p>
                <div class="mt-1 flex flex-col gap-0.5">
                    <a href="{{ route('seo.schools.best') }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('seo.schools.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <span class="flex items-center gap-3">
                            <x-app-icon name="trophy" class="h-4 w-4 text-gold-500"/>
                            <span>Sekolah Terbaik</span>
                        </span>
                        <span class="rounded-full bg-gold-100 px-2 py-0.5 text-[10px] font-extrabold text-gold-700">Top</span>
                    </a>
                    <a href="{{ route('seo.pesantren.best') }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('seo.pesantren.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <span class="flex items-center gap-3">
                            <x-app-icon name="sparkles" class="h-4 w-4 text-forest-600"/>
                            <span>Pesantren Terbaik</span>
                        </span>
                        <span class="rounded-full bg-forest-100 px-2 py-0.5 text-[10px] font-extrabold text-forest-800">Pilihan</span>
                    </a>
                    <a href="{{ route('compare.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('compare.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="scale" class="h-4 w-4 {{ request()->routeIs('compare.*') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Bandingkan Sekolah</span>
                    </a>
                    <a href="{{ route('calculator.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('calculator.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="calculator" class="h-4 w-4 {{ request()->routeIs('calculator.*') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Kalkulator Biaya</span>
                    </a>
                </div>
            </div>

            <div class="border-t border-forest-100/70 pt-3 pb-1">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary w-full justify-center">
                        <x-app-icon name="user" class="h-4 w-4"/>
                        <span>Dashboard Saya</span>
                    </a>
                @else
                    <div class="flex gap-2.5">
                        <a href="{{ route('login') }}" class="btn-outline flex-1 justify-center text-center">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-primary flex-1 justify-center text-center">Daftar Gratis</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
