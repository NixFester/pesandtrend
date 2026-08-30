{{-- Mobile bottom navigation bar — visible on small screens only --}}
<nav class="fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-t border-forest-100 md:hidden safe-area-bottom" id="mobile-bottom-nav">
    <div class="flex items-center justify-around h-16">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium {{ request()->routeIs('home') ? 'text-forest-700' : 'text-ink-soft/60' }}">
            <x-app-icon name="home" class="h-5 w-5"/>
            <span>Beranda</span>
        </a>
        <a href="{{ route('schools.index') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium {{ request()->routeIs('schools.*') ? 'text-forest-700' : 'text-ink-soft/60' }}">
            <x-app-icon name="search" class="h-5 w-5"/>
            <span>Cari</span>
        </a>
        @auth
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium {{ request()->routeIs('dashboard') ? 'text-forest-700' : 'text-ink-soft/60' }}">
            <x-app-icon name="bookmark" class="h-5 w-5"/>
            <span>Simpan</span>
        </a>
        <a href="{{ route('onboarding.index') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium {{ request()->routeIs('onboarding.*') ? 'text-forest-700' : 'text-ink-soft/60' }}">
            <x-app-icon name="send" class="h-5 w-5"/>
            <span>Daftar</span>
        </a>
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium text-ink-soft/60">
            <x-app-icon name="user" class="h-5 w-5"/>
            <span>Akun</span>
        </a>
        @else
        <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium text-ink-soft/60">
            <x-app-icon name="bookmark" class="h-5 w-5"/>
            <span>Simpan</span>
        </a>
        <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium text-ink-soft/60">
            <x-app-icon name="send" class="h-5 w-5"/>
            <span>Daftar</span>
        </a>
        <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 text-xs font-medium text-ink-soft/60">
            <x-app-icon name="user" class="h-5 w-5"/>
            <span>Masuk</span>
        </a>
        @endauth
    </div>
</nav>
{{-- Spacer so content isn't hidden behind the fixed nav --}}
<div class="h-16 md:hidden"></div>
