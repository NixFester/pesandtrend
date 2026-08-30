@extends('layouts.app')

@section('title', 'Pembayaran — Pesantrends')

@section('content')
<div class="container-app py-8 max-w-lg mx-auto">
    <h1 class="text-2xl font-extrabold text-ink mb-2">Pembayaran Pendaftaran</h1>
    <p class="text-ink-soft mb-6">{{ $application->school->name }} — {{ $application->student_name }}</p>

    @if($application->latestPayment && $application->latestPayment->invoice_url)
    <div class="rounded-2xl border border-forest-100 p-6 card-shadow text-center space-y-4">
        <div>
            <p class="text-sm text-ink-soft mb-1">Total Pembayaran</p>
            <p class="text-3xl font-extrabold text-forest-900">Rp {{ number_format($application->latestPayment->amount, 0, ',', '.') }}</p>
        </div>

        <a href="{{ $application->latestPayment->invoice_url }}" class="btn-primary w-full text-center block">
            Bayar via Xendit Gateway
        </a>

        <div class="pt-4 border-t border-forest-100">
            <p class="text-xs font-semibold text-gold-800 mb-2">⚡ Mode Pengujian / Sandbox:</p>
            <a href="{{ route('onboarding.pay', ['application' => $application, 'simulate' => 1]) }}" class="btn-gold w-full text-center block text-xs py-2.5">
                Simulasi Pembayaran Lunas (Instan)
            </a>
        </div>

        <p class="text-xs text-ink-soft">Anda akan dialihkan ke gateway pembayaran Xendit yang aman.</p>
    </div>
    @else
    <div class="rounded-2xl border border-forest-100 p-6 card-shadow text-center">
        <p class="text-ink-soft">Invoice pembayaran belum tersedia. Silakan hubungi admin.</p>
    </div>
    @endif
</div>
@endsection
