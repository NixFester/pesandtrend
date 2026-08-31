@extends('layouts.app')
@section('title', 'Cari Sekolah — Pesantrends')

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">Cari Sekolah</span>
            </nav>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Cari Sekolah Islam Terbaik</h1>
            <p class="mt-2 text-sm text-white/70">Temukan pesantren &amp; sekolah Islam terverifikasi sesuai kebutuhan keluarga Anda</p>
        </div>
    </section>

    <section class="bg-white py-10">
        <div class="container-app">
            @include('components.flash')

            {{-- Filter bar --}}
            <form method="GET" class="card-shadow grid gap-2 rounded-2xl border border-forest-100 bg-white p-3 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr_auto]">
                <label class="relative flex items-center">
                    <x-icon name="search" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama sekolah, kota, atau program..." aria-label="Cari sekolah"
                           class="w-full rounded-xl border-0 bg-forest-50/60 py-3 pl-12 pr-4 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                </label>
                <label class="relative flex items-center">
                    <x-icon name="map-pin" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                    <select name="kota" aria-label="Filter kota" class="w-full appearance-none rounded-xl border-0 bg-forest-50/60 py-3 pl-12 pr-10 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                        <option value="">Semua Kota</option>
                        @foreach ($cities as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['kota'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-icon name="chevron-down" class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60"/>
                </label>
                <label class="relative flex items-center">
                    <x-icon name="graduation-cap" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                    <select name="jenjang" aria-label="Filter jenjang" class="w-full appearance-none rounded-xl border-0 bg-forest-50/60 py-3 pl-12 pr-10 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                        <option value="">Semua Jenjang</option>
                        @foreach ($jenjang as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['jenjang'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-icon name="chevron-down" class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60"/>
                </label>
                <label class="relative flex items-center">
                    <x-icon name="home" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                    <select name="tipe" aria-label="Filter tipe sekolah" class="w-full appearance-none rounded-xl border-0 bg-forest-50/60 py-3 pl-12 pr-10 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                        <option value="">Semua Sekolah</option>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['tipe'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-icon name="chevron-down" class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60"/>
                </label>
                <button type="submit" class="btn-primary">
                    <x-icon name="filter" class="h-4 w-4"/>
                    Terapkan
                </button>
            </form>

            {{-- Pencarian populer --}}
            <div class="mt-5 flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-ink-soft">Pencarian Populer:</span>
                @foreach ($popularSearches as $term)
                    <a href="{{ route('schools.index', ['q' => $term]) }}" class="chip transition hover:bg-forest-100">{{ $term }}</a>
                @endforeach
                <a href="{{ route('schools.index', ['urut' => 'rating']) }}" class="chip-gold transition hover:bg-gold-200">
                    <x-icon name="star" class="h-3 w-3" fill="#C9A227" :stroke="0"/>
                    Rating Tertinggi
                </a>
            </div>

            {{-- Hasil --}}
            <div class="mt-8 flex items-center justify-between">
                <p class="text-sm font-bold text-ink">
                    {{ $schools->total() }} sekolah{{ $schools->total() > 1 ? '' : '' }} ditemukan
                </p>
                <form method="GET" class="flex items-center gap-2" id="sort-form">
                    @foreach (['q', 'kota', 'jenjang', 'tipe'] as $field)
                        @if (! empty($filters[$field]))
                            <input type="hidden" name="{{ $field }}" value="{{ $filters[$field] }}">
                        @endif
                    @endforeach
                    <input type="hidden" name="lat" id="sort-lat" value="{{ $filters['lat'] ?? '' }}">
                    <input type="hidden" name="lng" id="sort-lng" value="{{ $filters['lng'] ?? '' }}">
                    <label for="urut" class="text-xs font-semibold text-ink-soft">Urutkan</label>
                    <select id="urut" name="urut" onchange="handleSort(this)" class="rounded-xl border-forest-100 bg-white py-2.5 pl-3 pr-8 text-xs font-bold text-forest-900 focus:border-forest-500 focus:outline-none focus:ring-2 focus:ring-forest-500/20">
                        <option value="">Paling Relevan</option>
                        <option value="rating" {{ ($filters['urut'] ?? '') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="murah" {{ ($filters['urut'] ?? '') === 'murah' ? 'selected' : '' }}>Biaya Termurah</option>
                        <option value="populer" {{ ($filters['urut'] ?? '') === 'populer' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="terdekat" {{ ($filters['urut'] ?? '') === 'terdekat' ? 'selected' : '' }}>📍 Terdekat</option>
                    </select>
                </form>
                <script>
                function handleSort(el) {
                    if (el.value === 'terdekat' && navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(pos) {
                            document.getElementById('sort-lat').value = pos.coords.latitude;
                            document.getElementById('sort-lng').value = pos.coords.longitude;
                            document.getElementById('sort-form').submit();
                        }, function() { document.getElementById('sort-form').submit(); }, { timeout: 5000 });
                    } else {
                        document.getElementById('sort-form').submit();
                    }
                }
                </script>
            </div>

            @if ($schools->count())
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($schools as $school)
                        <x-school-card-search :school="$school"/>
                    @endforeach
                </div>

                <nav class="mt-10 flex flex-wrap items-center justify-center gap-1.5" aria-label="Navigasi halaman">
                    {{ $schools->links() }}
                </nav>
            @else
                <div class="card-shadow mt-6 rounded-2xl border border-forest-100 bg-cream-50 p-14 text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-forest-100 text-forest-700">
                        <x-icon name="search" class="h-7 w-7"/>
                    </span>
                    <h2 class="mt-4 text-lg font-extrabold text-ink">Sekolah tidak ditemukan</h2>
                    <p class="mx-auto mt-2 max-w-sm text-sm text-ink-soft">Coba kata kunci lain atau hapus beberapa filter untuk melihat lebih banyak hasil.</p>
                    <a href="{{ route('schools.index') }}" class="btn-primary mt-6">Lihat Semua Sekolah</a>
                </div>
            @endif

            {{-- Terakhir dilihat --}}
            @if ($recentlyViewed->count())
                <div class="mt-14">
                    <h2 class="flex items-center gap-2 text-base font-extrabold text-ink">
                        <x-icon name="clock" class="h-5 w-5 text-forest-700"/>
                        Terakhir Dilihat
                    </h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($recentlyViewed as $school)
                            <a href="{{ route('schools.show', $school->slug) }}" class="card-shadow group flex items-center gap-3 rounded-2xl bg-white p-3 transition hover:-translate-y-0.5">
                                <img src="{{ asset($school->image) }}" alt="" class="h-14 w-14 rounded-xl object-cover" loading="lazy">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-extrabold text-ink group-hover:text-forest-800">{{ $school->name }}</p>
                                    <p class="text-xs text-ink-soft">{{ $school->city }}, {{ $school->province }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
