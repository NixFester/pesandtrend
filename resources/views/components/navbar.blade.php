@php
    $isHome = request()->routeIs('home');
@endphp
<header id="main-header" class="group fixed inset-x-0 top-0 z-50 transition-all duration-300 {{ $isHome ? 'bg-transparent border-transparent' : 'bg-white/90 backdrop-blur-md border-b border-forest-100/70' }}" data-is-home="{{ $isHome ? 'true' : 'false' }}" {!! $isHome ? 'data-scrolled="false"' : '' !!}>
    <nav class="container-app flex h-16 items-center justify-between gap-4" aria-label="Navigasi utama">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Pesantrends — Beranda">
            @if($isHome)
                <img src="{{ asset('images/logo.svg') }}" alt="Logo Pesantrends" class="h-5 w-auto hidden sm:block sm:group-data-[scrolled=true]:hidden">
                <img src="{{ asset('images/logo-green.svg') }}" alt="Logo Pesantrends" class="h-5 w-auto hidden sm:block sm:group-data-[scrolled=false]:hidden">
            @else
                <img src="{{ asset('images/logo-green.svg') }}" alt="Logo Pesantrends" class="h-5 w-auto hidden sm:block">
            @endif
        </a>

        {{-- Menu toggle button — always visible --}}
        <button id="menu-toggle" data-menu-toggle class="rounded-lg p-2 transition {{ $isHome ? 'text-white hover:bg-white/10 group-data-[scrolled=true]:text-forest-900 group-data-[scrolled=true]:hover:bg-forest-50' : 'text-forest-900 hover:bg-forest-50' }}" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
            <span data-icon-menu class="block">
                <x-app-icon name="menu" class="h-6 w-6"/>
            </span>
            <span data-icon-close class="hidden">
                <x-app-icon name="x" class="h-6 w-6"/>
            </span>
        </button>
    </nav>

    {{-- Full-screen menu panel --}}
    <div
        id="mobile-menu"
        class="menu-panel bg-white px-4 transition-all duration-200 ease-snappy"
        data-menu-panel
    >
        <div class="py-4 overflow-y-auto lg:max-h-[70dvh] max-h-[85dvh]" data-menu-links>
            {{-- Desktop horizontal nav (lg+) --}}
            <div class="hidden lg:block" data-desktop-nav>
                <div class="container-app mx-auto">
                    <div class="flex items-center justify-center gap-2 py-4">
                        @php
                            $navLinkClass = $isHome
                                ? 'text-ink-soft hover:text-forest-600 hover:bg-forest-50'
                                : 'text-ink-soft hover:text-forest-600 hover:bg-forest-50';
                            $navActiveClass = 'text-forest-600 bg-forest-50';
                        @endphp
                        <a href="{{ route('home') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ request()->routeIs('home') ? $navActiveClass : $navLinkClass }}">
                            <div class="rounded-xl bg-gradient-to-br from-forest-100 to-forest-200 p-2.5 transition-colors group-hover:from-forest-200 group-hover:to-forest-300">
                                <x-app-icon name="home" class="h-5 w-5 {{ request()->routeIs('home') ? 'text-forest-600' : 'text-forest-500 group-hover:text-forest-600' }}"/>
                            </div>
                            <span class="text-xs">Beranda</span>
                        </a>
                        {{-- Coming Soon: Beasiswa --}}
                        <div class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all opacity-60 cursor-not-allowed">
                            <div class="rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 p-2.5">
                                <x-app-icon name="graduation-cap" class="h-5 w-5 text-amber-500"/>
                            </div>
                            <span class="relative text-xs text-amber-600">
                                Beasiswa
                                <span class="absolute -top-2 -right-4 rounded-full bg-gold-500 px-1.5 py-0.5 text-[8px] font-extrabold text-white">Soon</span>
                            </span>
                        </div>
                        {{-- Coming Soon: Kemitraan --}}
                        <div class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all opacity-60 cursor-not-allowed">
                            <div class="rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 p-2.5">
                                <x-app-icon name="users" class="h-5 w-5 text-emerald-500"/>
                            </div>
                            <span class="relative text-xs text-emerald-600">
                                Kemitraan
                                <span class="absolute -top-2 -right-4 rounded-full bg-gold-500 px-1.5 py-0.5 text-[8px] font-extrabold text-white">Soon</span>
                            </span>
                        </div>
                        <a href="{{ route('schools.index') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ request()->routeIs('schools.*') ? $navActiveClass : $navLinkClass }}">
                            <div class="rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 p-2.5 transition-colors group-hover:from-blue-200 group-hover:to-blue-300">
                                <x-app-icon name="search" class="h-5 w-5 {{ request()->routeIs('schools.*') ? 'text-blue-600' : 'text-blue-500 group-hover:text-blue-600' }}"/>
                            </div>
                            <span class="text-xs">Cari Sekolah</span>
                        </a>
                        <a href="{{ route('donations.index') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ request()->routeIs('donations.*') ? $navActiveClass : $navLinkClass }}">
                            <div class="rounded-xl bg-gradient-to-br from-rose-100 to-rose-200 p-2.5 transition-colors group-hover:from-rose-200 group-hover:to-rose-300">
                                <x-app-icon name="heart" class="h-5 w-5 {{ request()->routeIs('donations.*') ? 'text-rose-600' : 'text-rose-500 group-hover:text-rose-600' }}"/>
                            </div>
                            <span class="text-xs">Bantu</span>
                        </a>
                        <a href="{{ route('bimbel.index') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ request()->routeIs('bimbel.*') ? $navActiveClass : $navLinkClass }}">
                            <div class="rounded-xl bg-gradient-to-br from-violet-100 to-violet-200 p-2.5 transition-colors group-hover:from-violet-200 group-hover:to-violet-300">
                                <x-app-icon name="graduation-cap" class="h-5 w-5 {{ request()->routeIs('bimbel.*') ? 'text-violet-600' : 'text-violet-500 group-hover:text-violet-600' }}"/>
                            </div>
                            <span class="text-xs">Bimbel</span>
                        </a>
                        <a href="{{ route('articles.index') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ request()->routeIs('articles.*') ? $navActiveClass : $navLinkClass }}">
                            <div class="rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 p-2.5 transition-colors group-hover:from-amber-200 group-hover:to-amber-300">
                                <x-app-icon name="newspaper" class="h-5 w-5 {{ request()->routeIs('articles.*') ? 'text-amber-600' : 'text-amber-500 group-hover:text-amber-600' }}"/>
                            </div>
                            <span class="text-xs">Artikel</span>
                        </a>
                        <a href="{{ route('compare.index') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ request()->routeIs('compare.*') ? $navActiveClass : $navLinkClass }}">
                            <div class="rounded-xl bg-gradient-to-br from-teal-100 to-teal-200 p-2.5 transition-colors group-hover:from-teal-200 group-hover:to-teal-300">
                                <x-app-icon name="scale" class="h-5 w-5 {{ request()->routeIs('compare.*') ? 'text-teal-600' : 'text-teal-500 group-hover:text-teal-600' }}"/>
                            </div>
                            <span class="text-xs">Bandingkan</span>
                        </a>
                        <a href="{{ route('calculator.index') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ request()->routeIs('calculator.*') ? $navActiveClass : $navLinkClass }}">
                            <div class="rounded-xl bg-gradient-to-br from-cyan-100 to-cyan-200 p-2.5 transition-colors group-hover:from-cyan-200 group-hover:to-cyan-300">
                                <x-app-icon name="calculator" class="h-5 w-5 {{ request()->routeIs('calculator.*') ? 'text-cyan-600' : 'text-cyan-500 group-hover:text-cyan-600' }}"/>
                            </div>
                            <span class="text-xs">Kalkulator</span>
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all bg-forest-600 text-white hover:bg-forest-700">
                                <div class="rounded-xl bg-white/20 p-2.5">
                                    <x-app-icon name="user" class="h-5 w-5 text-white"/>
                                </div>
                                <span class="text-xs">Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl px-5 py-3 text-sm font-semibold transition-all {{ $isHome ? 'text-ink-soft hover:text-forest-600 hover:bg-forest-50' : 'text-ink-soft hover:text-forest-600 hover:bg-forest-50' }}">
                                <div class="rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 p-2.5 transition-colors group-hover:from-gray-200 group-hover:to-gray-300">
                                    <x-app-icon name="log-in" class="h-5 w-5 text-gray-500 group-hover:text-gray-600"/>
                                </div>
                                <span class="text-xs">Masuk</span>
                            </a>
                            <a href="{{ route('register') }}" class="group flex flex-col items-center gap-1.5 rounded-2xl bg-gradient-to-br from-forest-500 to-forest-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-forest-500/25 transition-all hover:from-forest-600 hover:to-forest-700 hover:shadow-forest-500/40 hover:-translate-y-0.5">
                                <div class="rounded-xl bg-white/20 p-2.5">
                                    <x-app-icon name="user-plus" class="h-5 w-5"/>
                                </div>
                                <span class="text-xs">Daftar</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mobile vertical nav (<lg) --}}
            <div class="lg:hidden space-y-4" data-mobile-nav>
                <div>
                    <p class="px-3 text-[11px] font-extrabold uppercase tracking-wider text-ink-soft/70">Menu Utama</p>
                <div class="mt-1 flex flex-col gap-0.5">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('home') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">
                        <x-app-icon name="home" class="h-4 w-4 {{ request()->routeIs('home') ? 'text-forest-700' : 'text-ink-soft/60' }}"/>
                        <span>Beranda</span>
                    </a>
                    {{-- Coming Soon: Beasiswa --}}
                    <div class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-bold opacity-50">
                        <span class="flex items-center gap-3">
                            <x-app-icon name="graduation-cap" class="h-4 w-4 text-amber-500"/>
                            <span class="text-ink-soft">Beasiswa</span>
                        </span>
                        <span class="rounded-full bg-gold-500 px-2 py-0.5 text-[10px] font-extrabold text-white">Soon</span>
                    </div>
                    {{-- Coming Soon: Kemitraan --}}
                    <div class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-bold opacity-50">
                        <span class="flex items-center gap-3">
                            <x-app-icon name="users" class="h-4 w-4 text-emerald-500"/>
                            <span class="text-ink-soft">Kemitraan</span>
                        </span>
                        <span class="rounded-full bg-gold-500 px-2 py-0.5 text-[10px] font-extrabold text-white">Soon</span>
                    </div>
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
