@extends('layouts.app')
@section('title', 'Pembayaran Donasi - Bantu Pesantren')

@section('content')
    <div class="container-app py-12 max-w-lg mx-auto">
        <h1 class="text-2xl font-extrabold text-ink mb-2">Pembayaran Donasi</h1>
        <p class="text-ink-soft mb-6">{{ $campaign->title }}</p>

        <div class="rounded-2xl border border-forest-100 p-6 card-shadow">
            {{-- Summary --}}
            <div class="mb-6 pb-6 border-b border-forest-100">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">Nama Donatur</dt>
                        <dd class="font-semibold text-ink">{{ $donation->donor_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">Email</dt>
                        <dd class="font-semibold text-ink">{{ $donation->donor_email }}</dd>
                    </div>
                    @if($donation->donor_message)
                    <div class="flex justify-between">
                        <dt class="text-ink-soft">Pesan</dt>
                        <dd class="font-semibold text-ink italic">"{{ $donation->donor_message }}"</dd>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-forest-100 pt-3">
                        <dt class="text-ink-soft font-bold">Jumlah Donasi</dt>
                        <dd class="text-2xl font-extrabold text-forest-900">{{ $donation->formatted_amount }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Xendit Button --}}
            <div class="text-center">
                <a href="{{ route('donations.process-payment', $donation) }}" class="btn-primary w-full text-center block py-4">
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
                <a href="{{ route('donations.simulate-payment', $donation) }}"
                   class="btn-gold w-full text-center block py-3 text-sm">
                    Simulasi Pembayaran Berhasil (Skip Xendit)
                </a>
            </div>
            @endif
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('donations.show', $campaign->slug) }}" class="text-sm font-semibold text-ink-soft hover:text-ink">
                ← Kembali ke Campaign
            </a>
        </div>
    </div>
@endsection
