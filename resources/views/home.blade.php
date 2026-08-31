@extends('layouts.app')
@section('title', 'Pesantrends — Temukan Sekolah Islam Terbaik untuk Putra-Putri Anda')

@section('content')
    {{-- ================= HERO ================= --}}
    <section class="relative overflow-hidden bg-forest-950">
        <img src="{{ asset('images/hero/architecture.jpg') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-25">
        <div class="absolute inset-0 bg-gradient-to-b from-forest-950/70 via-forest-950/80 to-forest-950" aria-hidden="true"></div>
        <span class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-gold-500/10 blur-3xl" aria-hidden="true"></span>

        <div class="container-app relative pb-32 pt-32 sm:pb-44 sm:pt-40">
            <div class="mx-auto max-w-2xl text-center">
                <div class="mb-8 flex justify-center">
                    <span class="inline-flex items-center gap-3 rounded-full border border-gold-400/50 bg-transparent px-5 py-2 text-[10px] font-bold uppercase tracking-[0.15em] text-gold-400 sm:text-[11px]">
                        <span class="text-base font-normal normal-case tracking-normal" dir="rtl">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</span>
                        BISMILLAHIRRAHMANIRRAHIM
                    </span>
                </div>
                <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Temukan Sekolah Islam <span class="text-gold-400">Terbaik</span> untuk Putra-Putri Anda
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-white/75 sm:text-base">
                    Lebih dari 1.240 pesantren &amp; sekolah Islam terverifikasi di seluruh Indonesia
                </p>
            </div>

            {{-- Kotak pencarian --}}
            <form action="{{ route('schools.index') }}" method="GET" class="mx-auto mt-9 max-w-3xl">
                <div class="grid gap-2 rounded-2xl bg-white p-2.5 shadow-2xl sm:grid-cols-[1fr_auto] lg:grid-cols-[2fr_1fr_1fr_auto]">
                    <label class="relative flex items-center sm:col-span-1 lg:col-span-1">
                        <x-app-icon name="search" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                        <input type="text" name="q" placeholder="Nama sekolah, kota, atau program..." aria-label="Cari sekolah"
                               class="w-full rounded-xl border-0 bg-forest-50/60 py-3.5 pl-12 pr-4 text-sm font-medium text-ink placeholder:text-ink-soft/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                    </label>
                    <label class="relative flex items-center">
                        <x-app-icon name="map-pin" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                        <select name="kota" aria-label="Filter kota" class="w-full appearance-none rounded-xl border-0 bg-forest-50/60 py-3.5 pl-12 pr-10 text-sm font-medium text-ink focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                            <option value="">Semua Kota</option>
                            @foreach (\App\Models\School::orderBy('city')->distinct()->pluck('city') as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                        <x-app-icon name="chevron-down" class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60"/>
                    </label>
                    <label class="relative flex items-center">
                        <x-app-icon name="graduation-cap" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                        <select name="jenjang" aria-label="Filter jenjang" class="w-full appearance-none rounded-xl border-0 bg-forest-50/60 py-3.5 pl-12 pr-10 text-sm font-medium text-ink focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                            <option value="">Semua Jenjang</option>
                            @foreach (['SDIT', 'SMPIT', 'SMA Islam', 'MA'] as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                        <x-app-icon name="chevron-down" class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60"/>
                    </label>
                    <button type="submit" class="btn-gold !rounded-xl sm:col-span-2 lg:col-span-1">
                        <x-app-icon name="search" class="h-4 w-4" :stroke="2.5"/>
                        Cari Sekolah Sekarang
                    </button>
                </div>
            </form>

            {{-- Statistik --}}
            <dl class="mx-auto mt-16 grid max-w-3xl grid-cols-3 sm:mt-20">
                @foreach ([['value' => '1.240+', 'label' => 'Sekolah Terverifikasi'], ['value' => '34', 'label' => 'Provinsi'], ['value' => '50rb+', 'label' => 'Orang Tua Terbantu']] as $stat)
                    <div class="flex flex-col items-center justify-center text-center">
                        <dd class="text-3xl font-extrabold text-gold-400 sm:text-4xl">{{ $stat['value'] }}</dd>
                        <dt class="mt-2 text-sm font-medium text-white/60 sm:text-base">{{ $stat['label'] }}</dt>
                    </div>
                @endforeach
            </dl>
            <div class="mx-auto mt-12 h-px w-[85%] max-w-xl bg-white/10 sm:mt-16"></div>
        </div>
    </section>

    {{-- ================= PILIHAN UNGGULAN ================= --}}
    <section class="bg-white py-16 sm:py-20" aria-labelledby="unggulan-heading">
        <div class="container-app">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="section-label">Pilihan Unggulan</p>
                    <h2 id="unggulan-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Sekolah Populer Pilihan Keluarga</h2>
                </div>
                <a href="{{ route('schools.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-forest-50 px-4 py-2.5 text-sm font-bold text-forest-800 transition hover:bg-forest-100">
                    Lihat Semua
                    <x-app-icon name="arrow-right" class="h-4 w-4"/>
                </a>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredSchools->take(3) as $school)
                    <x-school-card :school="$school" compare-ids=""/>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= FITUR UNGGULAN: KALKULATOR BIAYA ================= --}}
    <section class="relative overflow-hidden bg-cream-50 py-16 sm:py-20" aria-labelledby="fitur-heading">
        <div class="container-app">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <p class="section-label">Fitur Unggulan</p>
                    <h2 id="fitur-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Transparansi Biaya, <br class="hidden sm:block">Tanpa yang Tersembunyi</h2>
                    <p class="mt-4 text-sm leading-relaxed text-ink-soft sm:text-[15px]">
                        Rincian biaya lengkap tanpa ada yang tersembunyi. Hitung estimasi total biaya pendidikan
                        sebelum memutuskan — dari uang pangkal hingga study tour tahunan.
                    </p>
                    <ul class="mt-6 space-y-3">
                        @foreach (['Uang pangkal & SPP bulanan', 'Biaya asrama & seragam', 'Kegiatan ekstrakurikuler & study tour tahunan'] as $item)
                            <li class="flex items-center gap-3 text-sm font-semibold text-ink">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-forest-900 text-gold-400">
                                    <x-icon name="check" class="h-3.5 w-3.5" :stroke="3"/>
                                </span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('calculator.index') }}" class="btn-primary mt-8">
                        <x-icon name="calculator" class="h-4 w-4"/>
                        Hitung Total Biaya Lengkap
                    </a>
                </div>

                <div class="card-shadow-lg rounded-3xl bg-white p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <h3 class="flex items-center gap-2 text-base font-extrabold text-ink">
                            <x-icon name="wallet" class="h-5 w-5 text-gold-600"/>
                            Estimasi Biaya per Bulan
                        </h3>
                        <span class="chip">Contoh</span>
                    </div>
                    <dl class="mt-5 space-y-3.5 text-sm">
                        @foreach ([
                            ['label' => 'SPP Bulanan', 'value' => 'Rp1.500.000', 'note' => 'per bulan'],
                            ['label' => 'Biaya Asrama', 'value' => 'Rp500.000', 'note' => 'per bulan'],
                            ['label' => 'Uang Pangkal', 'value' => 'Rp7.500.000', 'note' => 'satu kali'],
                            ['label' => 'Seragam & Perlengkapan', 'value' => 'Rp1.250.000', 'note' => 'satu kali'],
                            ['label' => 'Ekstrakurikuler', 'value' => 'Rp900.000', 'note' => 'per tahun'],
                            ['label' => 'Study Tour Tahunan', 'value' => 'Rp1.100.000', 'note' => 'per tahun'],
                        ] as $row)
                            <div class="flex items-center justify-between border-b border-forest-50 pb-3.5 last:border-0">
                                <dt class="font-semibold text-ink-soft">{{ $row['label'] }}</dt>
                                <dd class="text-right">
                                    <span class="font-extrabold text-ink">{{ $row['value'] }}</span>
                                    <span class="block text-[11px] text-ink-soft">{{ $row['note'] }}</span>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                    <div class="mt-5 flex items-center justify-between rounded-2xl bg-forest-900 px-5 py-4">
                        <span class="text-sm font-bold text-white/80">Total/bulan</span>
                        <span class="text-xl font-extrabold text-gold-400">Rp2.000.000</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= MENGAPA PESANTRENDS ================= --}}
    <section class="bg-white py-16 sm:py-20" aria-labelledby="mengapa-heading">
        <div class="container-app">
            <div class="mx-auto max-w-2xl text-center">
                <p class="section-label">Platform #1 Pilihan Orang Tua Muslim</p>
                <h2 id="mengapa-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Mengapa Pesantrends?</h2>
                <p class="mt-3 text-sm text-ink-soft">Dipercaya 50.000+ orang tua di seluruh Indonesia</p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['icon' => 'badge-check', 'title' => 'Data Terverifikasi', 'desc' => 'Setiap sekolah melewati proses verifikasi ketat oleh tim kami'],
                    ['icon' => 'wallet', 'title' => 'Biaya Transparan', 'desc' => 'Rincian biaya lengkap tanpa ada yang tersembunyi'],
                    ['icon' => 'scale', 'title' => 'Perbandingan Mudah', 'desc' => 'Bandingkan hingga 3 sekolah secara side-by-side'],
                    ['icon' => 'heart-pulse', 'title' => 'Konsultasi Gratis', 'desc' => 'Tim ahli pendidikan Islam siap membantu pilihan Anda'],
                    ['icon' => 'shield-check', 'title' => 'Reputasi Terpercaya', 'desc' => 'Dipercaya 50.000+ orang tua di seluruh Indonesia'],
                    ['icon' => 'trending-up', 'title' => 'Informasi Real-time', 'desc' => 'Update jadwal pendaftaran & berita terbaru'],
                ] as $feature)
                    <div class="card-shadow group rounded-2xl border border-forest-50 bg-white p-6 transition hover:-translate-y-1 hover:border-forest-200">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-forest-900 text-gold-400 transition group-hover:scale-105">
                            <x-icon :name="$feature['icon']" class="h-6 w-6"/>
                        </span>
                        <h3 class="mt-4 text-base font-extrabold text-ink">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= PROGRAM UNGGULAN ================= --}}
    <section class="relative overflow-hidden bg-forest-950 py-16 sm:py-20" aria-labelledby="program-heading">
        <span class="pointer-events-none absolute -left-32 top-0 h-80 w-80 rounded-full bg-gold-500/10 blur-3xl" aria-hidden="true"></span>
        <div class="container-app relative">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-400">Program Unggulan</p>
                    <h2 id="program-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Pilih Program yang Tepat</h2>
                </div>
                <a href="{{ route('schools.index') }}" class="btn-ghost-light !py-2.5 text-xs">Jelajahi Semua Program</a>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($flagshipPrograms as $program)
                    <div class="group rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur transition hover:border-gold-400/40 hover:bg-white/10">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold-500/15 text-gold-400">
                            <x-icon :name="$program->icon" class="h-6 w-6"/>
                        </span>
                        <h3 class="mt-4 text-base font-extrabold text-white">{{ $program->name }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/65">{{ $program->description }}</p>
                        <a href="{{ route('schools.index', ['q' => $program->name]) }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-gold-400 transition hover:gap-2.5">
                            Lihat sekolah terkait
                            <x-icon name="arrow-right" class="h-3.5 w-3.5"/>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= TESTIMONI ================= --}}
    <section class="bg-cream-50 py-16 sm:py-20" aria-labelledby="testimoni-heading">
        <div class="container-app">
            <div class="mx-auto max-w-2xl text-center">
                <p class="section-label">Testimoni</p>
                <h2 id="testimoni-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">50.000+ Orang Tua Sudah Percaya</h2>
                <p class="mt-3 text-sm text-ink-soft">Bergabung dengan keluarga Muslim Indonesia</p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <figure class="card-shadow relative rounded-2xl bg-white p-7">
                        <span class="absolute -top-4 left-6 flex h-9 w-9 items-center justify-center rounded-xl bg-gold-500 text-forest-950 shadow-md">
                            <x-icon name="quote" class="h-4.5 w-4.5 h-[18px] w-[18px]" fill="currentColor" :stroke="0"/>
                        </span>
                        <div class="flex gap-1 pt-2" aria-label="Rating {{ $testimonial->rating }} dari 5">
                            @for ($i = 0; $i < $testimonial->rating; $i++)
                                <x-icon name="star" class="h-4 w-4 text-gold-500" fill="#C9A227" :stroke="0"/>
                            @endfor
                        </div>
                        <blockquote class="mt-4 text-sm leading-relaxed text-ink-soft">"{{ $testimonial->quote }}"</blockquote>
                        <figcaption class="mt-5 flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-forest-900 text-sm font-extrabold text-gold-400">
                                {{ Str::substr($testimonial->name, 0, 1) }}
                            </span>
                            <div>
                                <p class="text-sm font-extrabold text-ink">{{ $testimonial->name }}</p>
                                <p class="text-xs text-ink-soft">{{ $testimonial->role }}</p>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= CTA ================= --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="card-shadow-lg relative overflow-hidden rounded-3xl bg-gradient-to-br from-forest-900 to-forest-950 px-8 py-14 text-center sm:px-14">
                <img src="{{ asset('images/articles/two-boys.png') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-15">
                <span class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-gold-500/20 blur-3xl" aria-hidden="true"></span>
                <div class="relative mx-auto max-w-xl">
                    <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Mulai Cari Sekolah Terbaik</h2>
                    <p class="mt-3 text-sm leading-relaxed text-white/70 sm:text-[15px]">
                        Dapatkan informasi lengkap 1.240+ pesantren dan sekolah Islam terverifikasi.
                        Gratis, tanpa biaya tersembunyi.
                    </p>
                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ route('schools.index') }}" class="btn-gold">
                            <x-icon name="search" class="h-4 w-4" :stroke="2.5"/>
                            Cari Sekolah
                        </a>
                        <a href="{{ route('compare.index') }}" class="btn-ghost-light">
                            <x-icon name="scale" class="h-4 w-4"/>
                            Mulai Membandingkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= ARTIKEL ================= --}}
    <section class="bg-cream-50 py-16 sm:py-20" aria-labelledby="artikel-heading">
        <div class="container-app">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="section-label">Belajar dari Para Ahli</p>
                    <h2 id="artikel-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Artikel &amp; Panduan</h2>
                </div>
                <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-forest-800 card-shadow transition hover:bg-forest-50">
                    Lihat Semua
                    <x-icon name="arrow-right" class="h-4 w-4"/>
                </a>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <x-article-card :article="$article"/>
                @endforeach
            </div>
        </div>
    </section>

@endsection
