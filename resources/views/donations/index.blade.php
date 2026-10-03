@extends('layouts.app')
@section('title', 'Bantu Pesantren — Dukung Pendidikan Islam Indonesia')

@section('content')
    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-forest-950 py-20 sm:py-28">
        <img src="{{ asset('images/hero/architecture.jpg') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-forest-950/80 via-forest-950/90 to-forest-950"></div>
        <span class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-gold-500/10 blur-3xl" aria-hidden="true"></span>

        <div class="container-app relative text-center">
            <div class="mx-auto max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-gold-500/15 px-4 py-1.5 text-xs font-bold text-gold-400">
                    <x-app-icon name="heart" class="h-4 w-4"/>
                    Bantu Pesantren
                </span>
                <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Dukung Pendidikan <span class="text-gold-400">Islam</span> Indonesia
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-white/75 sm:text-base">
                    Setiap donasi Anda membantu pesantren dan sekolah Islam di seluruh Indonesia.
                    Wujudkan harapan ribuan santun untuk pendidikan yang lebih baik.
                </p>
            </div>

            {{-- Stats --}}
            <dl class="mx-auto mt-10 grid max-w-2xl grid-cols-3 gap-4 rounded-2xl border border-white/10 bg-white/5 px-6 py-5 backdrop-blur">
                <div class="text-center">
                    <dd class="text-xl font-extrabold text-gold-400 sm:text-2xl">{{ number_format($stats['total_campaigns']) }}</dd>
                    <dt class="mt-1 text-xs text-white/60">Campaign Aktif</dt>
                </div>
                <div class="text-center border-x border-white/10">
                    <dd class="text-xl font-extrabold text-gold-400 sm:text-2xl">{{ number_format($stats['total_donors']) }}</dd>
                    <dt class="mt-1 text-xs text-white/60">Donatur</dt>
                </div>
                <div class="text-center">
                    <dd class="text-xl font-extrabold text-gold-400 sm:text-2xl">{{ $stats['total_formatted'] }}</dd>
                    <dt class="mt-1 text-xs text-white/60">Total Donasi</dt>
                </div>
            </dl>

            <div class="mt-12 h-px w-[85%] max-w-xl bg-white/10 mx-auto"></div>
        </div>
    </section>

    {{-- Featured Campaigns --}}
    @if($featuredCampaigns->count() > 0)
    <section class="bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-2xl text-center">
                <p class="section-label">Pilihan Utama</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Campaign Unggulan</h2>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @foreach($featuredCampaigns as $campaign)
                    <x-campaign-card :campaign="$campaign" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- All Campaigns --}}
    <section class="bg-cream-50 py-16 sm:py-20">
        <div class="container-app">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="section-label">Galang Dana</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Semua Campaign</h2>
                </div>

                {{-- Category Filter --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('donations.index') }}"
                       class="rounded-full px-4 py-2 text-xs font-bold transition {{ !request('kategori') ? 'bg-forest-900 text-white' : 'bg-white text-ink-soft hover:bg-forest-50' }}">
                        Semua
                    </a>
                    @foreach($categories as $key => $label)
                        <a href="{{ route('donations.index', ['kategori' => $key]) }}"
                           class="rounded-full px-4 py-2 text-xs font-bold transition {{ request('kategori') === $key ? 'bg-forest-900 text-white' : 'bg-white text-ink-soft hover:bg-forest-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if($campaigns->count() > 0)
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($campaigns as $campaign)
                        <x-campaign-card :campaign="$campaign" />
                    @endforeach
                </div>

                <div class="mt-10 flex justify-center">
                    {{ $campaigns->withQueryString()->links() }}
                </div>
            @else
                <div class="mt-12 text-center">
                    <x-empty-state
                        title="Belum Ada Campaign"
                        body="Saat ini belum ada campaign yang aktif. Cek kembali nanti ya."
                        icon="heart" />
                </div>
            @endif
        </div>
    </section>

    {{-- How It Works --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-2xl text-center">
                <p class="section-label">Cara Beramal</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Bagaimana Cara Berdonasi?</h2>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="search" class="h-7 w-7"/>
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">Pilih Campaign</h3>
                    <p class="mt-2 text-sm text-ink-soft">Pilih campaign yang ingin Anda dukung</p>
                </div>
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="heart" class="h-7 w-7"/>
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">Masukkan Nominal</h3>
                    <p class="mt-2 text-sm text-ink-soft">Tentukan jumlah donasi Anda</p>
                </div>
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="credit-card" class="h-7 w-7"/>
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">Bayar via Xendit</h3>
                    <p class="mt-2 text-sm text-ink-soft">Pembayaran aman via Xendit</p>
                </div>
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="badge-check" class="h-7 w-7"/>
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">Donasi Terkirim</h3>
                    <p class="mt-2 text-sm text-ink-soft">Dana langsung ke campaign</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-forest-950 py-16 sm:py-20">
        <div class="container-app text-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Jadilah Bagian dari Kebaikan</h2>
            <p class="mt-3 text-sm text-white/70 sm:text-base">
                Setiap rupiah yang Anda donate sangat berarti untuk masa depan pendidikan Islam.
            </p>
            <a href="{{ route('donations.index') }}" class="btn-gold mt-8 inline-flex items-center gap-2">
                <x-app-icon name="heart" class="h-5 w-5"/>
                Donasi Sekarang
            </a>
        </div>
    </section>
@endsection
