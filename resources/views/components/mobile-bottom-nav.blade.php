{{-- Mobile bottom navigation — fixed to bottom of viewport on small screens --}}
@php
    $navItems = [
        [
            'route' => route('home'),
            'icon' => 'home',
            'label' => 'Beranda',
            'active' => request()->routeIs('home'),
            'auth' => null,
        ],
        [
            'route' => route('schools.index'),
            'icon' => 'search',
            'label' => 'Cari',
            'active' => request()->routeIs('schools.*'),
            'auth' => null,
        ],
        [
            'route' => route('donations.index'),
            'icon' => 'heart',
            'label' => 'Bantu',
            'active' => request()->routeIs('donations.*'),
            'auth' => null,
        ],
        [
            'route' => route('bimbel.index'),
            'icon' => 'graduation-cap',
            'label' => 'Bimbel',
            'active' => request()->routeIs('bimbel.*'),
            'auth' => null,
        ],
        [
            'route' => auth()->check() ? route('dashboard') : route('login'),
            'icon' => 'user',
            'label' => auth()->check() ? 'Akun' : 'Login',
            'active' => request()->routeIs('dashboard') || request()->routeIs('login'),
            'auth' => null,
        ],
    ];
@endphp

<nav class="fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-t border-forest-100 md:hidden safe-bottom" id="mobile-bottom-nav" aria-label="Navigasi utama">
    {{-- Spacer: 4rem (h-16) + safe-bottom so content isn't hidden --}}
    <div class="h-16"></div>

    <div class="absolute inset-x-0 top-0 flex h-full">
        @foreach ($navItems as $item)
            @php
                $href = $item['auth'] === 'required' && !auth()->check() ? route('login') : $item['route'];
                $showActive = $item['active'];
            @endphp
            <a
                href="{{ $href }}"
                class="relative flex flex-1 flex-col items-center justify-center gap-0.5 py-2 text-xs font-medium transition-colors duration-150
                    {{ $showActive ? 'text-forest-700' : 'text-ink-soft/60' }}"
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
