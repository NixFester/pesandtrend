@extends('layouts.app')
@section('title', 'Pembayaran Bimbel — Bimbel Online')

@section('content')
    <div class="container-app py-12 max-w-lg mx-auto">
        <h1 class="text-2xl font-extrabold text-ink mb-2">Pembayaran Bimbel</h1>
        <p class="text-ink-soft mb-6">{{ $booking->mentor->name }}</p>

        <div class="rounded-2xl border border-forest-100 p-6 card-shadow">
            {{-- Summary --}}
            <div class="mb-6 pb-6 border-b border-forest-100">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">Kode Booking</dt>
                        <dd class="font-mono font-semibold text-ink">{{ $booking->code }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">Nama Klien</dt>
                        <dd class="font-semibold text-ink">{{ $booking->client_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">Email</dt>
                        <dd class="font-semibold text-ink">{{ $booking->client_email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">No. WhatsApp</dt>
                        <dd class="font-semibold text-ink">{{ $booking->client_whatsapp }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">Mentor</dt>
                        <dd class="font-semibold text-ink">{{ $booking->mentor->name }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-forest-100 pt-3">
                        <dt class="text-ink-soft font-bold">Jumlah Pembayaran</dt>
                        <dd class="text-2xl font-extrabold text-forest-900">{{ $booking->formatted_amount }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Xendit Button --}}
            <div class="text-center">
                <a href="{{ route('bimbel.process-payment', $booking) }}" class="btn-primary w-full text-center block py-4">
                    Bayar via Xendit Gateway
                </a>

                <p class="mt-4 text-xs text-ink-soft">
                    Anda akan dialihkan ke gateway pembayaran Xendit yang aman.
                </p>
            </div>

            {{-- Development Simulation --}}
            @if(app()->environment('local', 'development'))
            <div class="mt-6 pt-6 border-t border-forest-100">
                <p class="text-xs font-semibold text-gold-800 mb-2">⚡ Development Mode:</p>
                <a href="{{ route('bimbel.simulate-payment', $booking) }}"
                   class="btn-gold w-full text-center block py-3 text-sm">
                    Simulasi Pembayaran Berhasil (Skip Xendit)
                </a>
            </div>
            @endif
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('bimbel.show', $booking->mentor->slug) }}" class="text-sm font-semibold text-ink-soft hover:text-ink">
                ← Kembali ke Mentor
            </a>
        </div>
    </div>
@endsection
