@extends('layouts.app')
@section('title', 'Bimbel Online — Les Privat Bersama Mentor Terbaik')

@section('content')
    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-forest-950 py-20 sm:py-28">
        <img src="{{ asset('images/hero/architecture.jpg') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-forest-950/80 via-forest-950/90 to-forest-950"></div>
        <span class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-gold-500/10 blur-3xl" aria-hidden="true"></span>

        <div class="container-app relative text-center">
            <div class="mx-auto max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-gold-500/15 px-4 py-1.5 text-xs font-bold text-gold-400">
                    <x-app-icon name="graduation-cap" class="h-4 w-4"/>
                    Bimbel Online
                </span>
                <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Les Privat Bersama <span class="text-gold-400">Mentor</span> Terbaik
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-white/75 sm:text-base">
                    Belajar intensif bersama mentor berpengalaman. Pilih mentor, bayar aman via Xendit,
                    lalu mulai belajar via WhatsApp.
                </p>
            </div>

            {{-- Stats --}}
            <dl class="mx-auto mt-10 grid max-w-2xl grid-cols-3 gap-4 rounded-2xl border border-white/10 bg-white/5 px-6 py-5 backdrop-blur">
                <div class="text-center">
                    <dd class="text-xl font-extrabold text-gold-400 sm:text-2xl">{{ number_format($stats['total_mentors']) }}</dd>
                    <dt class="mt-1 text-xs text-white/60">Mentor Aktif</dt>
                </div>
                <div class="text-center border-x border-white/10">
                    <dd class="text-xl font-extrabold text-gold-400 sm:text-2xl">{{ number_format($stats['total_bookings']) }}</dd>
                    <dt class="mt-1 text-xs text-white/60">Total Booking</dt>
                </div>
                <div class="text-center">
                    <dd class="text-xl font-extrabold text-gold-400 sm:text-2xl">{{ $stats['total_formatted'] }}</dd>
                    <dt class="mt-1 text-xs text-white/60">Total Pembayaran</dt>
                </div>
            </dl>

            <div class="mt-12 h-px w-[85%] max-w-xl bg-white/10 mx-auto"></div>
        </div>
    </section>

    {{-- Mentor Catalog --}}
    <section class="bg-cream-50 py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-2xl text-center">
                <p class="section-label">Katalog Mentor</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Pilih Mentor Anda</h2>
            </div>

            @if($mentors->count() > 0)
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($mentors as $mentor)
                        <x-mentor-card :mentor="$mentor" />
                    @endforeach
                </div>

                <div class="mt-10 flex justify-center">
                    {{ $mentors->links() }}
                </div>
            @else
                <div class="mt-12 text-center">
                    <x-empty-state
                        title="Belum Ada Mentor"
                        body="Saat ini belum ada mentor yang tersedia. Cek kembali nanti ya."
                        icon="user" />
                </div>
            @endif
        </div>
    </section>

    {{-- How It Works --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-2xl text-center">
                <p class="section-label">Cara Kerja</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Bagaimana Cara Memesan?</h2>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="search" class="h-7 w-7"/>
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">1. Pilih Mentor</h3>
                    <p class="mt-2 text-sm text-ink-soft">Telusuri katalog dan pilih mentor sesuai kebutuhan belajar Anda.</p>
                </div>
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="credit-card" class="h-7 w-7"/>
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">2. Bayar via Xendit</h3>
                    <p class="mt-2 text-sm text-ink-soft">Isi data diri Anda, lalu bayar dengan metode pembayaran favorit.</p>
                </div>
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="check-circle" class="h-7 w-7"/>
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">3. Dapatkan Bukti</h3>
                    <p class="mt-2 text-sm text-ink-soft">Unduh bukti pembayaran PDF setelah transaksi berhasil.</p>
                </div>
                <div class="text-center">
                    <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-forest-900 text-gold-400">
                        <x-app-icon name="brand-whatsapp" class="h-7 w-7" :fill="'currentColor'" />
                    </span>
                    <h3 class="mt-4 font-extrabold text-ink">4. Mulai Belajar</h3>
                    <p class="mt-2 text-sm text-ink-soft">Hubungi mentor via WhatsApp dan mulai sesi belajar Anda.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
