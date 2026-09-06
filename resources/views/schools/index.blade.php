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

            {{-- Search + filter bar: inline on lg+, mobile triggers sheet --}}
            <form method="GET" action="{{ route('schools.index') }}" class="card-shadow grid gap-2 rounded-2xl border border-forest-100 bg-white p-3 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr_auto]">
                <label class="relative flex items-center">
                    <svg class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama sekolah, kota, atau program..." aria-label="Cari sekolah"
                           class="w-full rounded-xl border-0 bg-forest-50/60 py-3 pl-12 pr-4 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                </label>

                {{-- Desktop: extra filter selects inline; hidden on mobile --}}
                <div class="hidden lg:contents">
                    <label class="relative flex items-center">
                        <svg class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                        <select name="kota" aria-label="Filter kota" class="input-field appearance-none pr-10">
                            <option value="">Semua Kota</option>
                            @foreach ($cities as $value => $label)
                                <option value="{{ $value }}" {{ ($filters['kota'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </label>
                    <label class="relative flex items-center">
                        <svg class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
                        <select name="jenjang" aria-label="Filter jenjang" class="input-field appearance-none pr-10">
                            <option value="">Semua Jenjang</option>
                            @foreach ($jenjang as $value => $label)
                                <option value="{{ $value }}" {{ ($filters['jenjang'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </label>
                    <label class="relative flex items-center">
                        <svg class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 9 9-7 9 7v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <select name="tipe" aria-label="Filter tipe sekolah" class="input-field appearance-none pr-10">
                            <option value="">Semua Sekolah</option>
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}" {{ ($filters['tipe'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </label>
                </div>

                <button type="submit" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Terapkan
                </button>
            </form>

            {{-- Mobile: results count + sort + filter sheet trigger --}}
            <div class="mt-4 flex items-center justify-between gap-3 md:hidden">
                <p class="text-sm font-semibold text-ink-soft">{{ $schools->total() }} ditemukan</p>
                <div class="flex gap-2">
                    <select name="urut" onchange="handleSort(this)" class="rounded-xl border border-forest-100 bg-white py-2.5 pl-3 pr-8 text-xs font-bold text-forest-900 focus:border-forest-500 focus:outline-none focus:ring-2 focus:ring-forest-500/20">
                        <option value="">Relevan</option>
                        <option value="rating" {{ ($filters['urut'] ?? '') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="murah" {{ ($filters['urut'] ?? '') === 'murah' ? 'selected' : '' }}>Biaya Termurah</option>
                        <option value="populer" {{ ($filters['urut'] ?? '') === 'populer' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="terdekat" {{ ($filters['urut'] ?? '') === 'terdekat' ? 'selected' : '' }}>Terdekat</option>
                    </select>
                    <button type="button" class="btn-outline !py-2.5 !text-xs" data-sheet-open="schools-filter-sheet">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter
                    </button>
                </div>
            </div>

            {{-- Mobile filter sheet --}}
            <x-mobile-sheet id="schools-filter-sheet" title="Saring Sekolah">
                <form method="GET" action="{{ route('schools.index') }}" class="space-y-4">
                    <input type="hidden" name="q" value="{{ $filters['q'] ?? '' }}">
                    <x-form.select name="kota" label="Kota" :options="$cities" :value="($filters['kota'] ?? '')"/>
                    <x-form.select name="jenjang" label="Jenjang" :options="$jenjang" :value="($filters['jenjang'] ?? '')"/>
                    <x-form.select name="tipe" label="Tipe Sekolah" :options="$types" :value="($filters['tipe'] ?? '')"/>
                    <button type="submit" class="btn-primary w-full">Terapkan</button>
                </form>
            </x-mobile-sheet>

            {{-- Pencarian populer --}}
            <div class="mt-5 flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-ink-soft">Pencarian Populer:</span>
                @foreach ($popularSearches as $term)
                    <a href="{{ route('schools.index', ['q' => $term]) }}" class="chip transition hover:bg-forest-100">{{ $term }}</a>
                @endforeach
                <a href="{{ route('schools.index', ['urut' => 'rating']) }}" class="chip-gold transition hover:bg-gold-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="#C9A227" stroke="none" aria-hidden="true"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                    Rating Tertinggi
                </a>
            </div>

            {{-- Hasil + sort (desktop only — mobile has inline sort above) --}}
            <div class="mt-8 flex items-center justify-between">
                <p class="text-sm font-bold text-ink">
                    {{ $schools->total() }} sekolah ditemukan
                </p>
                <form method="GET" class="hidden items-center gap-2 md:flex" id="sort-form">
                    @foreach (['q', 'kota', 'jenjang', 'tipe'] as $field)
                        @if (! empty($filters[$field]))
                            <input type="hidden" name="{{ $field }}" value="{{ $filters[$field] }}">
                        @endif
                    @endforeach
                    <input type="hidden" name="lat" id="sort-lat" value="{{ $filters['lat'] ?? '' }}">
                    <input type="hidden" name="lng" id="sort-lng" value="{{ $filters['lng'] ?? '' }}">
                    <label for="urut-desktop" class="text-xs font-semibold text-ink-soft">Urutkan</label>
                    <select id="urut-desktop" name="urut" onchange="handleSortDesktop(this)" class="rounded-xl border-forest-100 bg-white py-2.5 pl-3 pr-8 text-xs font-bold text-forest-900 focus:border-forest-500 focus:outline-none focus:ring-2 focus:ring-forest-500/20">
                        <option value="">Paling Relevan</option>
                        <option value="rating" {{ ($filters['urut'] ?? '') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="murah" {{ ($filters['urut'] ?? '') === 'murah' ? 'selected' : '' }}>Biaya Termurah</option>
                        <option value="populer" {{ ($filters['urut'] ?? '') === 'populer' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="terdekat" {{ ($filters['urut'] ?? '') === 'terdekat' ? 'selected' : '' }}>Terdekat</option>
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
                function handleSortDesktop(el) { handleSort(el); }
                </script>
            </div>

            @if ($schools->count())
                <div class="mt-6 auto-grid-cards">
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-forest-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Terakhir Dilihat
                    </h2>
                    <div class="mt-5 auto-grid-cards">
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
