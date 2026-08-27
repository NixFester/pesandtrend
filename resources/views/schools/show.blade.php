@extends('layouts.app')
@section('title', $school->name.' — Pesantrends')

@section('content')
    {{-- Hero detail --}}
    <section class="relative overflow-hidden bg-forest-950">
        <img src="{{ asset($school->image) }}" alt="{{ $school->name }}" class="absolute inset-0 h-full w-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-t from-forest-950 via-forest-950/70 to-forest-950/40" aria-hidden="true"></div>

        <div class="container-app relative py-14 sm:py-20">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <a href="{{ route('schools.index') }}" class="transition hover:text-white">Cari Sekolah</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">{{ $school->name }}</span>
            </nav>

            <div class="mt-5 flex flex-wrap items-start justify-between gap-6">
                <div class="max-w-2xl">
                    <div class="flex flex-wrap gap-2">
                        @if ($school->badge)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gold-500 px-3 py-1.5 text-[11px] font-extrabold text-forest-950">
                                <x-icon name="calendar" class="h-3 w-3" :stroke="2.5"/>
                                {{ $school->badge }}
                            </span>
                        @endif
                        @if ($school->is_verified)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1.5 text-[11px] font-bold text-white backdrop-blur">
                                <x-icon name="badge-check" class="h-3.5 w-3.5 text-gold-400"/>
                                Terverifikasi
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1.5 text-[11px] font-bold text-white backdrop-blur">
                            Akreditasi {{ $school->accreditation }}
                        </span>
                    </div>

                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">{{ $school->name }}</h1>
                    <p class="mt-3 flex items-center gap-1.5 text-sm font-medium text-white/80">
                        <x-icon name="map-pin" class="h-4 w-4 text-gold-400"/>
                        {{ $school->address }}
                    </p>

                    <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-semibold text-white/90">
                        <span class="flex items-center gap-1.5">
                            <x-icon name="star" class="h-4 w-4 text-gold-400" fill="#C9A227" :stroke="0"/>
                            {{ number_format($school->rating, 1) }}
                            <span class="font-medium text-white/60">({{ $school->reviews_count }} ulasan)</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <x-icon name="users" class="h-4 w-4 text-gold-400"/>
                            {{ Str::of(number_format($school->students_count))->replace(',', '.') }} siswa
                        </span>
                        <span class="flex items-center gap-1.5">
                            <x-icon name="graduation-cap" class="h-4 w-4 text-gold-400"/>
                            {{ implode(' · ', $school->jenjang) }}
                        </span>
                    </div>
                </div>

                <div class="card-shadow-lg w-full max-w-xs rounded-2xl bg-white p-6">
                    <p class="text-xs font-semibold text-ink-soft">Mulai dari</p>
                    <p class="mt-1 text-2xl font-extrabold text-forest-900">
                        Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}
                        <span class="text-sm font-semibold text-ink-soft">/bln</span>
                    </p>
                    <p class="mt-1 text-xs text-ink-soft">
                        @if ($school->asrama_monthly > 0)
                            + Rp{{ number_format($school->asrama_monthly, 0, ',', '.') }} asrama/bln
                        @else
                            Tanpa biaya asrama
                        @endif
                    </p>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('calculator.index', [
                            'pangkal' => $school->uang_pangkal,
                            'spp' => $school->spp_monthly,
                            'asrama' => $school->asrama_monthly,
                            'seragam' => $school->seragam_fee,
                            'ekskul' => $school->ekskul_fee,
                            'tour' => $school->study_tour_fee,
                        ]) }}" class="btn-primary w-full">
                            <x-icon name="calculator" class="h-4 w-4"/>
                            Hitung Total Biaya
                        </a>
                        <form action="{{ route('schools.save', $school) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-outline w-full">
                                <x-icon name="heart" class="h-4 w-4"/>
                                {{ $isSaved ? 'Tersimpan — Hapus' : 'Simpan Sekolah' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistik singkat --}}
    <section class="border-b border-forest-50 bg-white">
        <div class="container-app grid grid-cols-2 gap-4 py-8 sm:grid-cols-4">
            @foreach ([
                ['label' => 'Jumlah Siswa', 'value' => Str::of(number_format($school->students_count))->replace(',', '.').' siswa', 'icon' => 'users'],
                ['label' => 'Rasio Guru:Siswa', 'value' => $school->teacher_ratio, 'icon' => 'graduation-cap'],
                ['label' => 'Berdiri Sejak', 'value' => $school->founded_year, 'icon' => 'calendar'],
                ['label' => 'Tipe', 'value' => $school->type, 'icon' => 'building'],
            ] as $stat)
                <div class="flex items-center gap-3.5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-forest-50 text-forest-800">
                        <x-icon :name="$stat['icon']" class="h-5 w-5"/>
                    </span>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-ink-soft">{{ $stat['label'] }}</p>
                        <p class="text-base font-extrabold text-ink">{{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="bg-cream-50 py-12 sm:py-16">
        <div class="container-app grid gap-10 lg:grid-cols-[1fr_340px]">
            {{-- Kolom utama --}}
            <div class="space-y-10">
                @include('components.flash')

                {{-- Tentang --}}
                <div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-icon name="info" class="h-5 w-5 text-gold-600"/>
                        Tentang Sekolah
                    </h2>
                    <div class="article-body mt-4">
                        @foreach (explode("\n\n", $school->description) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach ($school->tags as $tag)
                            <span class="chip">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>

                {{-- Program Utama --}}
                <div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-icon name="book-open" class="h-5 w-5 text-gold-600"/>
                        Program Utama
                    </h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach ($school->programs as $program)
                            <div class="flex items-start gap-3 rounded-2xl border border-forest-50 bg-cream-50 p-4">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-forest-900 text-gold-400">
                                    <x-icon :name="$program->icon" class="h-5 w-5"/>
                                </span>
                                <div>
                                    <p class="text-sm font-extrabold text-ink">{{ $program->name }}</p>
                                    @if ($program->description)
                                        <p class="mt-1 text-xs leading-relaxed text-ink-soft">{{ Str::limit($program->description, 90) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-icon name="building" class="h-5 w-5 text-gold-600"/>
                        Fasilitas
                    </h2>
                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach ($school->facilities as $facility)
                            <div class="flex items-center gap-2.5 rounded-xl bg-forest-50 px-4 py-3">
                                <x-icon name="check-circle" class="h-4 w-4 shrink-0 text-forest-700"/>
                                <span class="text-sm font-bold text-forest-900">{{ $facility->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Prestasi --}}
                <div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-icon name="trophy" class="h-5 w-5 text-gold-600"/>
                        Prestasi &amp; Penghargaan
                    </h2>
                    <ul class="mt-5 space-y-3.5">
                        @foreach ($school->achievements as $achievement)
                            <li class="flex items-center gap-3.5 rounded-2xl border border-gold-200 bg-gold-50 px-5 py-4">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gold-500 text-forest-950">
                                    <x-icon name="trophy" class="h-5 w-5"/>
                                </span>
                                <p class="text-sm font-bold text-ink">{{ $achievement->title }}</p>
                                @if ($achievement->year)
                                    <span class="ml-auto shrink-0 rounded-full bg-white px-2.5 py-1 text-xs font-extrabold text-gold-700">{{ $achievement->year }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                {{-- Rincian biaya --}}
                <div class="card-shadow-lg rounded-2xl bg-white p-6">
                    <h2 class="flex items-center gap-2.5 text-base font-extrabold text-ink">
                        <x-icon name="wallet" class="h-5 w-5 text-gold-600"/>
                        Rincian Biaya
                    </h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        @foreach ([
                            ['label' => 'Uang Pangkal', 'value' => 'Rp'.number_format($school->uang_pangkal, 0, ',', '.'), 'note' => 'satu kali'],
                            ['label' => 'SPP Bulanan', 'value' => 'Rp'.number_format($school->spp_monthly, 0, ',', '.'), 'note' => 'per bulan'],
                            ['label' => 'Biaya Asrama', 'value' => $school->asrama_monthly ? 'Rp'.number_format($school->asrama_monthly, 0, ',', '.') : '—', 'note' => 'per bulan'],
                            ['label' => 'Seragam & Perlengkapan', 'value' => 'Rp'.number_format($school->seragam_fee, 0, ',', '.'), 'note' => 'satu kali'],
                            ['label' => 'Ekstrakurikuler', 'value' => 'Rp'.number_format($school->ekskul_fee, 0, ',', '.'), 'note' => 'per tahun'],
                            ['label' => 'Study Tour Tahunan', 'value' => 'Rp'.number_format($school->study_tour_fee, 0, ',', '.'), 'note' => 'per tahun'],
                        ] as $row)
                            <div class="flex items-center justify-between border-b border-forest-50 pb-3 last:border-0">
                                <dt class="font-semibold text-ink-soft">{{ $row['label'] }}</dt>
                                <dd class="text-right">
                                    <span class="font-extrabold text-ink">{{ $row['value'] }}</span>
                                    <span class="block text-[10px] text-ink-soft">{{ $row['note'] }}</span>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                    <div class="mt-4 flex items-center justify-between rounded-2xl bg-forest-900 px-5 py-4">
                        <span class="text-xs font-bold text-white/80">Estimasi total/bulan</span>
                        <span class="text-lg font-extrabold text-gold-400">Rp{{ number_format($school->monthly_total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('calculator.index', [
                        'pangkal' => $school->uang_pangkal,
                        'spp' => $school->spp_monthly,
                        'asrama' => $school->asrama_monthly,
                        'seragam' => $school->seragam_fee,
                        'ekskul' => $school->ekskul_fee,
                        'tour' => $school->study_tour_fee,
                    ]) }}" class="btn-primary mt-4 w-full">
                        <x-icon name="calculator" class="h-4 w-4"/>
                        Hitung Total Biaya Lengkap
                    </a>
                </div>

                {{-- Statistik alumni --}}
                @if ($school->alumni_stats)
                    <div class="card-shadow rounded-2xl bg-forest-950 p-6">
                        <h2 class="flex items-center gap-2.5 text-base font-extrabold text-white">
                            <x-icon name="trending-up" class="h-5 w-5 text-gold-400"/>
                            Statistik Alumni
                        </h2>
                        <div class="mt-4 space-y-3.5">
                            @foreach ($school->alumni_stats as $label => $value)
                                <div class="rounded-2xl bg-white/5 px-4 py-3">
                                    <p class="text-lg font-extrabold text-gold-400">{{ $value }}</p>
                                    <p class="mt-0.5 text-xs font-semibold text-white/70">{{ $label }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Bandingkan --}}
                <div class="card-shadow rounded-2xl border border-forest-100 bg-white p-6">
                    <h2 class="text-base font-extrabold text-ink">Bandingkan dengan sekolah lain</h2>
                    <p class="mt-2 text-xs leading-relaxed text-ink-soft">Tambahkan sekolah ini ke perbandingan dan lihat perbedaannya side-by-side.</p>
                    <form action="{{ route('compare.add') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="school_id" value="{{ $school->id }}">
                        <input type="hidden" name="current" value="{{ $ids ?? '' }}">
                        <button type="submit" class="btn-outline w-full">
                            <x-icon name="scale" class="h-4 w-4"/>
                            Tambah ke Perbandingan
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </section>

    {{-- Sekolah terkait --}}
    @if ($related->count())
        <section class="bg-white py-14" aria-labelledby="terkait-heading">
            <div class="container-app">
                <div class="flex items-end justify-between gap-4">
                    <h2 id="terkait-heading" class="text-xl font-extrabold tracking-tight text-ink">Sekolah Serupa</h2>
                    <a href="{{ route('schools.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-forest-800 transition hover:gap-2.5">
                        Lihat Semua
                        <x-icon name="arrow-right" class="h-4 w-4"/>
                    </a>
                </div>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $school)
                        <x-school-card :school="$school"/>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
