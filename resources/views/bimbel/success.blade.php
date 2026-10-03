@extends('layouts.app')
@section('title', 'Pembayaran Berhasil — Bimbel Online')

@section('content')
    <section class="min-h-[60vh] bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-lg text-center">
                <span class="flex h-20 w-20 mx-auto items-center justify-center rounded-full bg-green-100 text-green-600">
                    <x-app-icon name="check-circle" class="h-10 w-10"/>
                </span>

                <h1 class="mt-6 text-2xl font-extrabold text-ink sm:text-3xl">Pembayaran Berhasil!</h1>
                <p class="mt-3 text-sm text-ink-soft sm:text-base">
                    Terima kasih, <strong>{{ $booking->client_name }}</strong>. Pembayaran bimbel dengan
                    <strong>{{ $booking->mentor->name }}</strong> telah kami terima.
                </p>

                <div class="mt-8 rounded-2xl border border-forest-100 bg-cream-50 p-6 text-left">
                    <h3 class="font-extrabold text-ink">Detail Pembayaran</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Kode Booking</dt>
                            <dd class="font-mono font-semibold text-ink">{{ $booking->code }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-forest-100 pt-3">
                            <dt class="text-ink-soft">Jumlah</dt>
                            <dd class="font-extrabold text-forest-800">{{ $booking->formatted_amount }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Nama Klien</dt>
                            <dd class="font-semibold text-ink">{{ $booking->client_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Mentor</dt>
                            <dd class="text-right font-semibold text-ink">{{ $booking->mentor->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Status</dt>
                            <dd class="font-semibold {{ $booking->is_paid ? 'text-green-700' : 'text-gold-700' }}">
                                {{ $booking->is_paid ? 'Berhasil' : ucfirst($booking->status) }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Tanggal</dt>
                            <dd class="font-semibold text-ink">{{ $booking->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" class="btn-primary flex-1 inline-flex items-center justify-center gap-2">
                        <x-app-icon name="brand-whatsapp" class="h-5 w-5" :fill="'currentColor'" />
                        Chat WhatsApp Mentor
                    </a>
                    <a href="{{ $proofUrl }}" class="btn-outline flex-1 inline-flex items-center justify-center gap-2">
                        <x-app-icon name="scroll" class="h-4 w-4"/>
                        Download Bukti Pembayaran
                    </a>
                </div>

                <p class="mt-4 text-xs text-ink-soft">
                    Chat WhatsApp sudah terisi pesan lengkap beserta tautan bukti pembayaran Anda.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('bimbel.show', $booking->mentor->slug) }}" class="btn-outline flex-1">
                        <x-app-icon name="arrow-left" class="h-4 w-4"/>
                        Kembali ke Mentor
                    </a>
                    <a href="{{ route('bimbel.index') }}" class="btn-outline flex-1">
                        Lihat Mentor Lain
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
