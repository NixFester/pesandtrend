@extends('layouts.app')

@section('title', 'Pembayaran — Pesantrends')

@section('content')
<div class="container-app py-8 max-w-lg mx-auto">
    <h1 class="text-2xl font-extrabold text-ink mb-2">Pembayaran Pendaftaran</h1>
    <p class="text-ink-soft mb-6">{{ $application->school->name }} — {{ $application->student_name }}</p>

    @if($application->latestPayment && $application->latestPayment->invoice_url)
    <div class="rounded-2xl border border-forest-100 p-6 card-shadow text-center">
        <p class="text-sm text-ink-soft mb-2">Total Pembayaran</p>
        <p class="text-3xl font-extrabold text-forest-900 mb-6">Rp {{ number_format($application->latestPayment->amount, 0, ',', '.') }}</p>

        <a href="{{ $application->latestPayment->invoice_url }}" target="_blank" class="btn-primary w-full text-center">
            Bayar via Xendit
        </a>

        <p class="text-xs text-ink-soft mt-4">Anda akan dialihkan ke halaman pembayaran Xendit yang aman.</p>
    </div>
    @else
    <div class="rounded-2xl border border-forest-100 p-6 card-shadow text-center">
        <p class="text-ink-soft">Invoice pembayaran belum tersedia. Silakan hubungi admin.</p>
    </div>
    @endif
</div>
@endsection
