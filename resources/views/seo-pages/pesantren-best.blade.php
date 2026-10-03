@extends('layouts.app')
@section('title', 'Pesantren Terbaik di Indonesia | Pesantrends')

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">Pesantren Terbaik</span>
            </nav>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Pesantren & Pondok Terbaik di Indonesia</h1>
            <p class="mt-2 text-sm text-white/70">Temukan pilihan pesantren berkualitas dengan program pendidikan agama dan umum yang komprehensif</p>
        </div>
    </section>

    <section class="bg-white py-10">
        <div class="container-app">
            <div class="mb-8">
                <h2 class="text-lg font-bold text-forest-900">Pilih Kota</h2>
                <p class="text-sm text-ink-soft/70">Temukan pesantren dan pondok pesantern terbaik di kota pilihan Anda</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($cities as $city)
                    <a href="{{ route('seo.pesantren.city', $city->slug) }}"
                       class="group relative overflow-hidden rounded-2xl border border-forest-100 bg-white p-6 transition-all hover:border-forest-300 hover:shadow-lg hover:shadow-forest-900/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-forest-900 group-hover:text-forest-700">{{ $city->name }}</h3>
                                <p class="mt-1 text-sm text-ink-soft/60">
                                    {{ $city->schools_count }} pesantren
                                    @if($city->featured_schools_count > 0)
                                        <span class="text-gold-500">• {{ $city->featured_schools_count }} pilihan utama</span>
                                    @endif
                                </p>
                            </div>
                            <svg class="h-5 w-5 text-forest-400 transition-transform group-hover:translate-x-1 group-hover:text-forest-600"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-2xl border border-forest-100 bg-forest-50/50 p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-forest-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <h3 class="mt-4 font-semibold text-forest-700">Belum Ada Kota</h3>
                        <p class="mt-1 text-sm text-ink-soft/60">Kota akan segera ditambahkan. Silakan hubungi admin.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-forest-50/50 py-10">
        <div class="container-app">
            <h2 class="mb-6 text-lg font-bold text-forest-900">Jenis-jenis Program Pesantren</h2>
            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gold-100">
                        <svg class="h-6 w-6 text-gold-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-forest-900">Pesantren Tahfidz</h3>
                    <p class="mt-2 text-sm text-ink-soft/70">Program fokus menghafal Al-Quran dengan metode yang terstruktur dan bimbingan ustadz berpengalaman.</p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gold-100">
                        <svg class="h-6 w-6 text-gold-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-forest-900">Pesantren Terintegrasi Sekolah</h3>
                    <p class="mt-2 text-sm text-ink-soft/70">Kombinasi pendidikan formal (SD-SMA) dengan pengasuhan pesantren dalam satu lingkungan.</p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gold-100">
                        <svg class="h-6 w-6 text-gold-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-forest-900">Pesantren Kilat</h3>
                    <p class="mt-2 text-sm text-ink-soft/70">Program intensif selama periode tertentu seperti libur sekolah untuk pembelajaran agama intensif.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
