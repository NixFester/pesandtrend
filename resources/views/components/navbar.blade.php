<header class="sticky top-0 z-50 border-b border-forest-100/70 bg-white/90 backdrop-blur-md">
    <nav class="container-app flex h-16 items-center justify-between gap-4" aria-label="Navigasi utama">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Pesantrends — Beranda">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-forest-900 text-gold-400">
                <x-app-icon name="book-open" class="h-5 w-5" :stroke="2.2"/>
            </span>
            <span class="text-lg font-extrabold tracking-tight text-forest-900">Pesant<span class="text-gold-600">rends</span></span>
        </a>

        <div class="hidden items-center gap-1 lg:flex">
            <a href="{{ route('home') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('home') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Beranda</a>
            <a href="{{ route('schools.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('schools.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Cari Sekolah</a>
            <a href="{{ route('compare.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('compare.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Bandingkan</a>
            <a href="{{ route('articles.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs('articles.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft hover:bg-forest-50 hover:text-forest-900' }}">Artikel</a>
        </div>

        <div class="hidden items-center gap-3 lg:flex">
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-xl bg-forest-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-forest-700">
                    <x-app-icon name="user" class="h-4 w-4"/>
                    {{ Str::before(auth()->user()->name, ' ') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-bold text-forest-900 transition hover:bg-forest-50">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary !px-4 !py-2.5">Daftar Gratis</a>
            @endauth
        </div>

        <button id="menu-toggle" class="rounded-lg p-2 text-forest-900 transition hover:bg-forest-50 lg:hidden" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
            <x-app-icon name="menu" class="h-6 w-6"/>
        </button>
    </nav>

    <div id="mobile-menu" class="hidden border-t border-forest-100 bg-white px-4 pb-5 pt-3 lg:hidden">
        <div class="flex flex-col gap-1">
            <a href="{{ route('home') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold {{ request()->routeIs('home') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Beranda</a>
            <a href="{{ route('schools.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold {{ request()->routeIs('schools.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Cari Sekolah</a>
            <a href="{{ route('compare.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold {{ request()->routeIs('compare.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Bandingkan Sekolah</a>
            <a href="{{ route('articles.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold {{ request()->routeIs('articles.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Artikel</a>
            <a href="{{ route('calculator.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-bold {{ request()->routeIs('calculator.*') ? 'bg-forest-50 text-forest-900' : 'text-ink-soft' }}">Kalkulator Biaya</a>
        </div>
        <div class="mt-4 flex gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary flex-1">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-outline flex-1">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary flex-1">Daftar Gratis</a>
            @endauth
        </div>
    </div>
</header>
