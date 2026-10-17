{{-- Mobile bottom navigation — fixed to bottom of viewport on small screens --}}
@php
    $navItems = [
        [
            'route' => route('home'),
            'icon' => 'home',
            'label' => 'Beranda',
            'active' => request()->routeIs('home'),
        ],
        [
            'route' => route('schools.index'),
            'icon' => 'search',
            'label' => 'Cari',
            'active' => request()->routeIs('schools.*'),
        ],
        [
            'route' => route('articles.index'),
            'icon' => 'newspaper',
            'label' => 'Artikel',
            'active' => request()->routeIs('articles.*'),
        ],
        [
            'route' => route('login'),
            'icon' => 'user',
            'label' => 'Login',
            'active' => request()->routeIs('login'),
        ],
    ];
@endphp

<nav class="fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-t border-forest-100 md:hidden safe-bottom" id="mobile-bottom-nav" aria-label="Navigasi utama">
    {{-- Spacer: 4rem (h-16) + safe-bottom so content isn't hidden --}}
    <div class="h-16"></div>

    <div class="absolute inset-x-0 top-0 flex h-full">
        @foreach ($navItems as $item)
            @php
                $showActive = $item['active'];
            @endphp
            <a
                href="{{ $item['route'] }}"
                class="relative flex flex-1 flex-col items-center justify-center gap-0.5 py-2 text-xs font-medium transition-colors duration-150
                    @if ($showActive)
                        text-forest-700
                    @else
                        text-ink-soft/60
                    @endif"
                @if ($showActive) aria-current="page" @endif
            >
                {{-- Active indicator: top dot --}}
                @if ($showActive)
                    <span class="absolute top-1.5 h-1 w-1 rounded-full bg-forest-700" aria-hidden="true"></span>
                @endif
                <x-app-icon name="{{ $item['icon'] }}" class="h-5 w-5" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
