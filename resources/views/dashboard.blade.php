@extends('layouts.app')
@section('title', 'Dashboard — Pesantrends')

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gold-500 text-xl font-extrabold text-forest-950">
                    {{ Str::substr($user->name, 0, 1) }}
                </span>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white">{{ $user->name }}</h1>
                    <p class="mt-1 flex items-center gap-2 text-xs font-semibold text-white/70">
                        <span class="rounded-full bg-gold-500/20 px-2.5 py-1 text-gold-400">Anggota Aktif</span>
                        <span>{{ $savedSchools->count() }} sekolah tersimpan</span>
                    </p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-ghost-light !py-2.5 text-xs">
                    <x-icon name="log-out" class="h-4 w-4"/>
                    Keluar
                </button>
            </form>
        </div>
    </section>

    <section class="bg-cream-50 py-12 sm:py-14">
        <div class="container-app space-y-12">
            @include('components.flash')

            {{-- Sekolah tersimpan --}}
            <div>
                <div class="flex items-end justify-between gap-4">
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-icon name="bookmark" class="h-5 w-5 text-gold-600"/>
                        Sekolah Tersimpan
                    </h2>
                    <a href="{{ route('schools.index') }}" class="text-sm font-bold text-forest-800 transition hover:text-forest-600">Tambah sekolah →</a>
                </div>

                @if ($savedSchools->count())
                    <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($savedSchools as $school)
                            <div class="card-shadow rounded-2xl bg-white p-5">
                                <div class="flex items-start gap-4">
                                    <img src="{{ asset($school->image) }}" alt="" class="h-16 w-16 rounded-xl object-cover">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('schools.show', $school->slug) }}" class="block truncate text-sm font-extrabold text-ink transition hover:text-forest-700">{{ $school->name }}</a>
                                        <p class="mt-0.5 text-xs text-ink-soft">{{ $school->city }}, {{ $school->province }}</p>
                                        <p class="mt-1.5 text-sm font-extrabold text-forest-900">Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}<span class="text-[11px] font-semibold text-ink-soft">/bln</span></p>
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('schools.show', $school->slug) }}" class="btn-primary flex-1 !py-2.5 text-xs">Lihat Detail</a>
                                    <form action="{{ route('schools.save', $school) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex h-[42px] w-[42px] items-center justify-center rounded-xl border border-forest-100 text-ink-soft transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Hapus dari tersimpan" aria-label="Hapus {{ $school->name }} dari tersimpan">
                                            <x-icon name="x" class="h-4 w-4"/>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card-shadow mt-5 rounded-2xl border border-dashed border-forest-200 bg-white p-10 text-center">
                        <p class="text-sm font-semibold text-ink-soft">Belum ada sekolah tersimpan.</p>
                        <p class="mt-1 text-xs text-ink-soft">Tekan tombol "Simpan Sekolah" di halaman detail sekolah untuk menyimpannya di sini.</p>
                        <a href="{{ route('schools.index') }}" class="btn-primary mt-5">Cari Sekolah</a>
                    </div>
                @endif
            </div>

            {{-- Rekomendasi --}}
            <div>
                <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                    <x-icon name="sparkles" class="h-5 w-5 text-gold-600"/>
                    Rekomendasi Untukmu
                </h2>
                <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($recommended as $school)
                        <x-school-card :school="$school"/>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-10 lg:grid-cols-2">
                {{-- Terakhir dilihat --}}
                @if ($recentlyViewed->count())
                    <div>
                        <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                            <x-icon name="clock" class="h-5 w-5 text-gold-600"/>
                            Terakhir Dilihat
                        </h2>
                        <div class="mt-5 space-y-3">
                            @foreach ($recentlyViewed as $school)
                                <a href="{{ route('schools.show', $school->slug) }}" class="card-shadow flex items-center gap-3 rounded-2xl bg-white p-3 transition hover:-translate-y-0.5">
                                    <img src="{{ asset($school->image) }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-extrabold text-ink">{{ $school->name }}</p>
                                        <p class="text-xs text-ink-soft">{{ $school->city }} · Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}/bln</p>
                                    </div>
                                    <x-icon name="chevron-right" class="h-4 w-4 shrink-0 text-ink-soft"/>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Artikel dibaca --}}
                <div>
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-icon name="newspaper" class="h-5 w-5 text-gold-600"/>
                        Artikel Populer
                    </h2>
                    <div class="mt-5 space-y-3">
                        @foreach ($articlesRead as $article)
                            <a href="{{ route('articles.show', $article->slug) }}" class="card-shadow flex items-center gap-3 rounded-2xl bg-white p-3 transition hover:-translate-y-0.5">
                                <img src="{{ asset($article->image) }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-extrabold text-ink">{{ $article->title }}</p>
                                    <p class="text-xs text-ink-soft">{{ $article->read_minutes }} min baca · {{ number_format($article->views) }} dibaca</p>
                                </div>
                                <x-icon name="chevron-right" class="h-4 w-4 shrink-0 text-ink-soft"/>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
