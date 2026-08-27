@extends('layouts.app')
@section('title', 'Bandingkan Sekolah — Pesantrends')

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">Bandingkan Sekolah</span>
            </nav>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Perbandingan Detail</h1>
            <p class="mt-2 text-sm text-white/70">Pilih hingga 3 sekolah untuk dibandingkan — biaya, fasilitas, dan program unggulan side-by-side</p>
        </div>
    </section>

    <section class="bg-white py-10 sm:py-14">
        <div class="container-app">
            @include('components.flash')

            @if ($schools->count() < 3)
                {{-- Tambah sekolah --}}
                <div class="relative mb-8" x-data>
                    <button type="button" data-compare-add class="btn-outline">
                        <x-icon name="plus" class="h-4 w-4" :stroke="2.5"/>
                        Tambah Sekolah
                    </button>
                    <div id="compare-add-panel" class="card-shadow-lg absolute left-0 z-30 mt-2 hidden w-80 max-w-[90vw] rounded-2xl border border-forest-100 bg-white p-3">
                        <p class="px-2 pb-2 text-xs font-bold uppercase tracking-wider text-ink-soft">Pilih sekolah</p>
                        <div class="max-h-72 space-y-1 overflow-y-auto">
                            @foreach ($others as $option)
                                <form action="{{ route('compare.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="school_id" value="{{ $option->id }}">
                                    <input type="hidden" name="current" value="{{ $ids }}">
                                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-2.5 py-2 text-left transition hover:bg-forest-50">
                                        <img src="{{ asset($option->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                        <span class="min-w-0">
                                            <span class="block truncate text-sm font-bold text-ink">{{ $option->name }}</span>
                                            <span class="block text-xs text-ink-soft">{{ $option->city }} · Rp{{ number_format($option->spp_monthly, 0, ',', '.') }}/bln</span>
                                        </span>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($schools->isEmpty())
                {{-- Keadaan kosong --}}
                <div class="card-shadow rounded-3xl border border-forest-100 bg-cream-50 px-8 py-16 text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-icon name="scale" class="h-8 w-8"/>
                    </span>
                    <h2 class="mt-5 text-xl font-extrabold text-ink">Pilih Sekolah Pertama</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-ink-soft">
                        Tambahkan sekolah untuk melihat perbandingan detail biaya, fasilitas, dan program unggulan.
                    </p>
                    <div class="mt-7 grid gap-4 sm:grid-cols-3">
                        @foreach ($others->take(3) as $suggestion)
                            <form action="{{ route('compare.add') }}" method="POST" class="card-shadow rounded-2xl bg-white p-4 text-left transition hover:-translate-y-1">
                                @csrf
                                <input type="hidden" name="school_id" value="{{ $suggestion->id }}">
                                <input type="hidden" name="current" value="">
                                <button type="submit" class="w-full">
                                    <img src="{{ asset($suggestion->image) }}" alt="{{ $suggestion->name }}" class="h-28 w-full rounded-xl object-cover">
                                    <p class="mt-3 truncate text-sm font-extrabold text-ink">{{ $suggestion->name }}</p>
                                    <p class="text-xs text-ink-soft">{{ $suggestion->city }}, {{ $suggestion->province }}</p>
                                    <span class="mt-3 inline-flex items-center gap-1.5 rounded-xl bg-forest-50 px-3 py-2 text-xs font-bold text-forest-800">
                                        <x-icon name="plus" class="h-3.5 w-3.5" :stroke="2.5"/>
                                        Pilih
                                    </span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Tabel perbandingan --}}
                <div class="card-shadow-lg overflow-hidden rounded-3xl border border-forest-100">
                    {{-- Baris header sekolah --}}
                    <div class="grid gap-px bg-forest-50 {{ $schools->count() === 1 ? 'sm:grid-cols-2' : ($schools->count() === 2 ? 'sm:grid-cols-3' : 'sm:grid-cols-4') }}">
                        <div class="flex items-center bg-white p-5">
                            <p class="text-sm font-extrabold text-ink">Sekolah <span class="text-ink-soft">({{ $schools->count() }}/3)</span></p>
                        </div>
                        @foreach ($schools as $school)
                            <div class="relative bg-white p-5">
                                @if ($schools->count() > 1)
                                    <form action="{{ route('compare.remove') }}" method="POST" class="absolute right-3 top-3">
                                        @csrf
                                        <input type="hidden" name="school_id" value="{{ $school->id }}">
                                        <input type="hidden" name="current" value="{{ $ids }}">
                                        <button type="submit" class="flex h-7 w-7 items-center justify-center rounded-full bg-forest-50 text-ink-soft transition hover:bg-red-100 hover:text-red-600" title="Hapus dari perbandingan" aria-label="Hapus {{ $school->name }}">
                                            <x-icon name="x" class="h-3.5 w-3.5"/>
                                        </button>
                                    </form>
                                @endif
                                <img src="{{ asset($school->image) }}" alt="{{ $school->name }}" class="h-24 w-full rounded-xl object-cover">
                                <a href="{{ route('schools.show', $school->slug) }}" class="mt-3 block text-sm font-extrabold leading-snug text-ink transition hover:text-forest-700">{{ $school->name }}</a>
                                <p class="mt-1 flex items-center gap-1 text-xs text-ink-soft">
                                    <x-icon name="map-pin" class="h-3 w-3"/>
                                    {{ $school->city }}, {{ $school->province }}
                                </p>
                                <p class="mt-2 flex items-center gap-1 text-xs font-bold text-ink">
                                    <x-icon name="star" class="h-3.5 w-3.5 text-gold-500" fill="#C9A227" :stroke="0"/>
                                    {{ number_format($school->rating, 1) }} ({{ $school->reviews_count }} ulasan)
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Baris data --}}
                    @foreach ([
                        ['label' => 'Jumlah Siswa', 'render' => fn ($s) => Str::of(number_format($s->students_count))->replace(',', '.').' siswa'],
                        ['label' => 'Rasio Guru:Siswa', 'render' => fn ($s) => $s->teacher_ratio],
                        ['label' => 'Berdiri Sejak', 'render' => fn ($s) => $s->founded_year],
                        ['label' => 'Tipe Sekolah', 'render' => fn ($s) => $s->type],
                        ['label' => 'Jenjang', 'render' => fn ($s) => implode(', ', $s->jenjang)],
                        ['label' => 'Akreditasi', 'render' => fn ($s) => $s->accreditation],
                        ['label' => 'Berasrama', 'render' => fn ($s) => $s->is_boarding ? 'Ya' : 'Tidak'],
                        ['label' => 'Uang Pangkal', 'render' => fn ($s) => 'Rp'.number_format($s->uang_pangkal, 0, ',', '.')],
                        ['label' => 'SPP/Bulan', 'render' => fn ($s) => 'Rp'.number_format($s->spp_monthly, 0, ',', '.')],
                        ['label' => 'Asrama/Bulan', 'render' => fn ($s) => $s->asrama_monthly ? 'Rp'.number_format($s->asrama_monthly, 0, ',', '.') : '—'],
                        ['label' => 'Total/bulan', 'render' => fn ($s) => 'Rp'.number_format($s->monthly_total, 0, ',', '.')],
                        ['label' => 'Fasilitas', 'render' => fn ($s) => $s->facilities->take(5)->pluck('name')->implode(', ').($s->facilities->count() > 5 ? '...' : '')],
                        ['label' => 'Program Unggulan', 'render' => fn ($s) => $s->programs->pluck('name')->implode(', ')],
                    ] as $row)
                        <div class="grid gap-px border-t border-forest-100 {{ $schools->count() === 1 ? 'sm:grid-cols-2' : ($schools->count() === 2 ? 'sm:grid-cols-3' : 'sm:grid-cols-4') }}">
                            <div class="bg-forest-50/70 p-5 text-xs font-extrabold uppercase tracking-wide text-forest-900 sm:text-sm sm:normal-case sm:tracking-normal">
                                {{ $row['label'] }}
                            </div>
                            @foreach ($schools as $school)
                                <div class="bg-white p-5 text-sm font-semibold {{ $row['label'] === 'Total/bulan' ? 'text-forest-900' : 'text-ink-soft' }}">
                                    @if ($row['label'] === 'Total/bulan')
                                        <span class="text-base font-extrabold text-forest-900">{{ $row['render']($school) }}</span>
                                    @else
                                        {{ $row['render']($school) }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Baris aksi --}}
                    <div class="grid gap-px border-t border-forest-100 {{ $schools->count() === 1 ? 'sm:grid-cols-2' : ($schools->count() === 2 ? 'sm:grid-cols-3' : 'sm:grid-cols-4') }}">
                        <div class="bg-white p-5"></div>
                        @foreach ($schools as $school)
                            <div class="flex flex-col gap-2 bg-white p-5">
                                <a href="{{ route('schools.show', $school->slug) }}" class="btn-primary w-full !py-2.5 text-xs">Lihat Detail Lengkap</a>
                                <a href="{{ route('calculator.index', [
                                    'pangkal' => $school->uang_pangkal,
                                    'spp' => $school->spp_monthly,
                                    'asrama' => $school->asrama_monthly,
                                    'seragam' => $school->seragam_fee,
                                    'ekskul' => $school->ekskul_fee,
                                    'tour' => $school->study_tour_fee,
                                ]) }}" class="btn-outline w-full !py-2.5 text-xs">Hitung Biaya</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($schools->count() === 1)
                    <p class="mt-6 text-center text-sm text-ink-soft">Tambahkan minimal satu sekolah lagi untuk melihat perbandingan side-by-side.</p>
                @endif
            @endif
        </div>
    </section>
@endsection
