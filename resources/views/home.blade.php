@extends('layouts.app')
@section('title', 'Pesantrends — Temukan Sekolah Islam Terbaik untuk Putra-Putri Anda')

@section('content')
    {{-- ================= HERO ================= --}}
    <section class="relative overflow-hidden bg-forest-950">
        <img src="{{ asset('images/hero/architecture.jpg') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-60" width="1600" height="900" loading="eager" fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-b from-forest-950/70 via-forest-950/80 to-forest-950" aria-hidden="true"></div>
        <span class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-gold-500/10 blur-3xl" aria-hidden="true"></span>

        <div class="container-app relative pt-5 sm:pt-40">
            <div class="mx-auto max-w-2xl text-center">
                {{-- Logo --}}
                <div class="mb-6 flex justify-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Pesantrends" class="h-10 w-auto">
                </div>

                <div class="mb-6 flex justify-center">
                    <span class="inline-flex items-center gap-3 rounded-full border border-gold-400/50 bg-transparent px-5 py-2 text-[10px] font-bold uppercase tracking-[0.15em] text-gold-400 sm:text-[11px]">
                        <span class="text-base font-normal normal-case tracking-normal" dir="rtl">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</span>
                        
                    </span>
                </div>

                <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Temukan Sekolah Islam <span class="text-gold-400">Terbaik</span> untuk Putra-Putri Anda
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-white/75 sm:text-base">
                    Lebih dari 1.240 pesantren &amp; sekolah Islam terverifikasi di seluruh Indonesia
                </p>

                <form method="GET" action="{{ route('schools.index') }}" class="mt-8 grid w-full gap-2 rounded-2xl bg-white p-2.5 shadow-2xl">
                    <label class="relative flex min-h-12 items-center">
                        <x-app-icon name="search" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                        <input type="search" name="q" placeholder="Nama sekolah, kota, atau program..." aria-label="Cari sekolah"
                               class="w-full rounded-xl border-0 bg-forest-50/60 py-3.5 pl-12 pr-4 text-sm font-medium text-ink placeholder:text-ink-soft/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                    </label>
                    <label class="relative flex min-h-12 items-center">
                        <x-app-icon name="map-pin" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                        <select name="kota" aria-label="Filter kota" class="w-full appearance-none rounded-xl border-0 bg-forest-50/60 py-3.5 pl-12 pr-10 text-sm font-medium text-ink focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                            <option value="">Semua Kota</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                        <x-app-icon name="chevron-down" class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60"/>
                    </label>
                    <label class="relative flex min-h-12 items-center">
                        <x-app-icon name="graduation-cap" class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60"/>
                        <select name="jenjang" aria-label="Filter jenjang" class="w-full appearance-none rounded-xl border-0 bg-forest-50/60 py-3.5 pl-12 pr-10 text-sm font-medium text-ink focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30">
                            <option value="">Semua Jenjang</option>
                            @foreach ($jenjang as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-app-icon name="chevron-down" class="pointer-events-none absolute right-3 h-4 w-4 text-ink-soft/60"/>
                    </label>
                    <button type="submit" class="btn-gold min-h-12 justify-center !rounded-xl">
                        <x-app-icon name="search" class="h-4 w-4" :stroke="2.5"/>
                        Cari Sekolah Sekarang
                    </button>
                </form>
            </div>

            {{-- Statistik --}}
            <dl class="mx-auto mt-12 grid max-w-2xl grid-cols-1 gap-4 xs:flex-row xs:divide-x xs:divide-white/15 rounded-2xl border border-white/10 bg-white/5 px-4 py-4 backdrop-blur sm:grid-cols-3 sm:gap-0 sm:divide-x sm:divide-white/15 sm:rounded-2xl sm:border sm:border-white/10 sm:bg-white/5 sm:px-6 sm:py-4 sm:backdrop-blur">
                @foreach ([['value' => '1.240+', 'label' => 'Sekolah Terverifikasi'], ['value' => '34', 'label' => 'Provinsi'], ['value' => '50rb+', 'label' => 'Orang Tua Terbantu']] as $stat)
                    <div class="px-3 py-2 text-center sm:px-6 sm:py-0">
                        <dt class="order-2 mt-1 text-[11px] font-semibold text-white/70 xs:order-1 xs:mt-0 sm:text-xs">{{ $stat['label'] }}</dt>
                        <dd class="order-1 text-xl font-extrabold text-gold-400 xs:order-2 sm:text-2xl">{{ $stat['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
            <div class="mx-auto mt-12 h-px w-[85%] max-w-xl bg-white/10 sm:mt-16"></div>
        </div>
    </section>

    {{-- ================= MENU SECTION ================= --}}
    <section id="homepage-menu" class="bg-forest-950 py-5 sm:py-6" aria-labelledby="homepage-menu-heading">
        <div class="container-app lg:flex lg:items-center" x-data="{ expanded: false }">
            <h2 id="homepage-menu-heading" class="sr-only">Menu utama</h2>
            @php
                $menuItems = [
                    [
                        'label' => 'Bantu Pesantren',
                        'icon' => 'bantu_pesantren.svg',
                        'route' => route('donations.index'),
                        'comingSoon' => false,
                    ],
                    [
                        'label' => 'Bimbel Masuk Pesantren',
                        'icon' => 'bimbel.svg',
                        'route' => route('bimbel.index'),
                        'comingSoon' => false,
                    ],
                    [
                        'label' => 'Info Beasiswa',
                        'icon' => 'info_beasiswa.svg',
                        'route' => null,
                        'comingSoon' => true,
                    ],
                    [
                        'label' => 'Kemitraan',
                        'icon' => 'kemitraan.svg',
                        'route' => null,
                        'comingSoon' => true,
                    ],
                ];
            @endphp

            <div class="grid grid-cols-2 gap-x-3 lg:contents">
                @foreach ($menuItems as $item)
                    @if ($item['route'])
                        <a href="{{ $item['route'] }}" class="group flex min-h-36 cursor-pointer flex-col items-center rounded-2xl px-1 py-2 text-center transition-colors duration-200 hover:bg-white/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold-400 lg:min-w-0 lg:flex-1">
                    @else
                        <div class="flex min-h-36 flex-col items-center rounded-2xl px-1 py-2 text-center lg:min-w-0 lg:flex-1">
                    @endif
                        <span class="flex h-16 w-16 items-center justify-center rounded-full border border-white/10 bg-white/5 transition-colors duration-200 xs:h-[88px] xs:w-[88px] @if ($item['route']) group-hover:border-white/20 group-hover:bg-white/10 @endif">
                            <img src="{{ asset('images/icon/'.$item['icon']) }}" alt="" aria-hidden="true" class="h-8 w-8 object-contain">
                        </span>
                        <span class="mt-3 text-[11px] font-medium leading-tight text-white/75 xs:text-sm">
                            {{ $item['label'] }}
                            @if ($item['comingSoon'])
                                <span class="block">(segera)</span>
                            @endif
                        </span>
                    @if ($item['route'])
                        </a>
                    @else
                        </div>
                    @endif
                @endforeach
            </div>

            <button
                type="button"
                class="mt-2 flex min-h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white/75 transition-colors duration-200 hover:bg-white/5 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold-400 lg:hidden"
                aria-controls="homepage-menu-more"
                :aria-expanded="expanded.toString()"
                @click="expanded = !expanded"
            >
                <span>Lainnya</span>
                <x-app-icon name="chevron-down" class="h-4 w-4 transition-transform duration-200" ::class="{ 'rotate-180': expanded }"/>
            </button>

            <div
                id="homepage-menu-more"
                x-show="expanded"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-1 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="translate-y-1 opacity-0"
                class="mt-2 grid grid-cols-2 gap-x-3 gap-y-2 lg:!mt-0 lg:!contents"
                x-cloak
            >
                <a href="{{ route('compare.index') }}" class="group flex min-h-36 cursor-pointer flex-col items-center rounded-2xl px-1 py-2 text-center transition-colors duration-200 hover:bg-white/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold-400 lg:min-w-0 lg:flex-1">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition-colors duration-200 group-hover:bg-white/10 lg:h-[88px] lg:w-[88px]">
                        <x-app-icon name="scale" class="h-6 w-6 lg:h-7 lg:w-7"/>
                    </span>
                    <span class="mt-2 text-[11px] font-medium leading-tight text-white/75 xs:text-sm lg:mt-3">Bandingkan Sekolah</span>
                </a>
                <a href="{{ route('calculator.index') }}" class="group flex min-h-36 cursor-pointer flex-col items-center rounded-2xl px-1 py-2 text-center transition-colors duration-200 hover:bg-white/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold-400 lg:min-w-0 lg:flex-1">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition-colors duration-200 group-hover:bg-white/10 lg:h-[88px] lg:w-[88px]">
                        <x-app-icon name="calculator" class="h-6 w-6 lg:h-7 lg:w-7"/>
                    </span>
                    <span class="mt-2 text-[11px] font-medium leading-tight text-white/75 xs:text-sm lg:mt-3">Kalkulator Biaya</span>
                </a>
                <a href="{{ route('seo.schools.best') }}" class="group flex min-h-36 cursor-pointer flex-col items-center rounded-2xl px-1 py-2 text-center transition-colors duration-200 hover:bg-white/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold-400 lg:min-w-0 lg:flex-1">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition-colors duration-200 group-hover:bg-white/10 lg:h-[88px] lg:w-[88px]">
                        <x-app-icon name="trophy" class="h-6 w-6 lg:h-7 lg:w-7"/>
                    </span>
                    <span class="mt-2 text-[11px] font-medium leading-tight text-white/75 xs:text-sm lg:mt-3">Sekolah Terbaik</span>
                </a>
                <a href="{{ route('seo.pesantren.best') }}" class="group flex min-h-36 cursor-pointer flex-col items-center rounded-2xl px-1 py-2 text-center transition-colors duration-200 hover:bg-white/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold-400 lg:min-w-0 lg:flex-1">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition-colors duration-200 group-hover:bg-white/10 lg:h-[88px] lg:w-[88px]">
                        <x-app-icon name="sparkles" class="h-6 w-6 lg:h-7 lg:w-7"/>
                    </span>
                    <span class="mt-2 text-[11px] font-medium leading-tight text-white/75 xs:text-sm lg:mt-3">Pesantren Pilihan</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ================= DAFTAR SEKOLAH CTA ================= --}}
    <section class="bg-cream-50 py-10 sm:py-12">
        <div class="container-app">
            <div class="card-shadow-lg relative overflow-hidden rounded-2xl bg-gradient-to-br from-forest-800 to-forest-900 px-6 py-8 sm:px-10 sm:py-10">
                <img src="{{ asset('images/articles/two-boys.png') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-30">
                <span class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-gold-500/20 blur-3xl" aria-hidden="true"></span>
                <div class="relative mx-auto max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gold-500/20 px-3 py-1 text-xs font-bold text-gold-400">
                        <x-app-icon name="building-office" class="h-3.5 w-3.5"/>
                        Untuk Sekolah &amp; Pesantren
                    </span>
                    <h2 class="mt-3 text-xl font-extrabold tracking-tight text-white sm:text-2xl">Tingkatkan Visibilitas Sekolah Anda</h2>
                    <p class="mt-2 text-sm leading-relaxed text-white/70">
                        Jangkau 50.000+ orang tua yang sedang mencari sekolah Islam terbaik untuk putra-putrinya.
                        Daftarkan sekolah Anda di Pesantrends dan tingkatkan kepercayaan calon murid baru.
                    </p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="#" class="btn-gold inline-flex items-center justify-center gap-2">
                            <x-app-icon name="building-office" class="h-4 w-4" :stroke="2.5"/>
                            Daftarkan Sekolah / Pesantren
                        </a>
                    </div>
                </div>
            </div>
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
                        sebelum memutuskan — dari uang pangkal hingga wisata edukasi tahunan.
                    </p>
                    <ul class="mt-6 space-y-3">
                        @foreach (['Uang pangkal & SPP bulanan', 'Biaya asrama & seragam', 'Kegiatan ekstrakurikuler & wisata edukasi tahunan'] as $item)
                            <li class="flex items-center gap-3 text-sm font-semibold text-ink">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-forest-900 text-gold-400">
                                    <x-app-icon name="check" class="h-3.5 w-3.5" :stroke="3"/>
                                </span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('calculator.index') }}" class="btn-primary mt-8">
                        <x-app-icon name="calculator" class="h-4 w-4"/>
                        Hitung Total Biaya Lengkap
                    </a>
                </div>

                <div class="card-shadow-lg rounded-3xl bg-white p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <h3 class="flex items-center gap-2 text-base font-extrabold text-ink">
                            <x-app-icon name="wallet" class="h-5 w-5 text-gold-600"/>
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
                            ['label' => 'Wisata Edukasi Tahunan', 'value' => 'Rp1.100.000', 'note' => 'per tahun'],
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

            <div class="mt-10 flex gap-4 overflow-x-auto pb-2 sm:overflow-x-visible sm:grid sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 sm:pb-0">
                @foreach ([
                    ['icon' => 'badge-check', 'title' => 'Data Terverifikasi', 'desc' => 'Setiap sekolah melewati proses verifikasi ketat oleh tim kami'],
                    ['icon' => 'wallet', 'title' => 'Biaya Transparan', 'desc' => 'Rincian biaya lengkap tanpa ada yang tersembunyi'],
                    ['icon' => 'scale', 'title' => 'Perbandingan Mudah', 'desc' => 'Bandingkan hingga 3 sekolah secara berdampingan'],
                    ['icon' => 'heart-pulse', 'title' => 'Konsultasi Gratis', 'desc' => 'Tim ahli pendidikan Islam siap membantu pilihan Anda'],
                    ['icon' => 'shield-check', 'title' => 'Reputasi Terpercaya', 'desc' => 'Dipercaya 50.000+ orang tua di seluruh Indonesia'],
                    ['icon' => 'trending-up', 'title' => 'Informasi Real-time', 'desc' => 'Update jadwal pendaftaran & berita terbaru'],
                ] as $feature)
                    <div class="card-shadow group min-w-[220px] snap-start rounded-2xl border border-forest-50 bg-white p-6 transition hover:-translate-y-1 hover:border-forest-200 sm:min-w-0">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-forest-900 text-gold-400 transition group-hover:scale-105">
                            <x-app-icon :name="$feature['icon']" class="h-6 w-6"/>
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

            <div class="mt-10 flex gap-4 overflow-x-auto pb-2 sm:overflow-x-visible sm:grid sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 sm:pb-0">
                @foreach ($flagshipPrograms as $program)
                    <div class="group min-w-[220px] snap-start rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur transition hover:border-gold-400/40 hover:bg-white/10 sm:min-w-0">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gold-500/15 text-gold-400">
                            <x-app-icon :name="$program->icon" class="h-6 w-6"/>
                        </span>
                        <h3 class="mt-4 text-base font-extrabold text-white">{{ $program->name }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/65">{{ $program->description }}</p>
                        <a href="{{ route('schools.index', ['q' => $program->name]) }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-gold-400 transition hover:gap-2.5">
                            Lihat sekolah terkait
                            <x-app-icon name="arrow-right" class="h-3.5 w-3.5"/>
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
                            <x-app-icon name="quote" class="h-4.5 w-4.5 h-[18px] w-[18px]" fill="currentColor" :stroke="0"/>
                        </span>
                        <div class="flex gap-1 pt-2" aria-label="Rating {{ $testimonial->rating }} dari 5">
                            @for ($i = 0; $i < $testimonial->rating; $i++)
                                <x-app-icon name="star" class="h-4 w-4 text-gold-500" fill="#C9A227" :stroke="0"/>
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
                <img src="{{ asset('images/articles/two-boys.png') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-40">
                <span class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-gold-500/20 blur-3xl" aria-hidden="true"></span>
                <div class="relative mx-auto max-w-xl">
                    <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Mulai Cari Sekolah Terbaik</h2>
                    <p class="mt-3 text-sm leading-relaxed text-white/70 sm:text-[15px]">
                        Dapatkan informasi lengkap 1.240+ pesantren dan sekolah Islam terverifikasi.
                        Gratis, tanpa biaya tersembunyi.
                    </p>
                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ route('schools.index') }}" class="btn-gold">
                            <x-app-icon name="search" class="h-4 w-4" :stroke="2.5"/>
                            Cari Sekolah
                        </a>
                        <a href="{{ route('compare.index') }}" class="btn-ghost-light">
                            <x-app-icon name="scale" class="h-4 w-4"/>
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
                    <x-app-icon name="arrow-right" class="h-4 w-4"/>
                </a>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <x-article-card :article="$article"/>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= NEWSLETTER ================= --}}
    <section class="bg-white pb-16 sm:pb-20">
        <div class="container-app">
            <div class="card-shadow-lg grid items-center gap-8 overflow-hidden rounded-3xl border border-forest-100 bg-cream-50 p-8 sm:p-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-gold-100 px-3.5 py-1.5 text-xs font-bold text-gold-800">
                        <x-app-icon name="newspaper" class="h-3.5 w-3.5"/>
                        Berlangganan Kabar
                    </span>
                    <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-ink">Dapatkan Artikel Pilihan Setiap Minggu</h2>
                    <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                        Tips memilih sekolah, informasi beasiswa, dan panduan pendaftaran langsung ke email Anda.
                    </p>
                </div>
                <form action="{{ route('newsletter.store') }}" method="POST" class="flex flex-col gap-3 sm:flex-row">
                    @csrf
                    <label class="relative flex-1">
                        <x-app-icon name="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-soft/60"/>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Anda..." required aria-label="Alamat email"
                               class="input-field !py-4 pl-12">
                    </label>
                    <button type="submit" class="btn-primary !py-4">
                        <x-app-icon name="send" class="h-4 w-4"/>
                        Langganan
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
