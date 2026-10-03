@extends('layouts.app')
@section('title', 'Donasi Berhasil — Bantu Pesantren')

@section('content')
    <section class="min-h-[60vh] bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-lg text-center">
                <span class="flex h-20 w-20 mx-auto items-center justify-center rounded-full bg-green-100 text-green-600">
                    <x-app-icon name="check-circle" class="h-10 w-10"/>
                </span>

                <h1 class="mt-6 text-2xl font-extrabold text-ink sm:text-3xl">Donasi Berhasil!</h1>
                <p class="mt-3 text-sm text-ink-soft sm:text-base">
                    Terima kasih atas donasi Anda untuk <strong>{{ $donation->campaign->title }}</strong>.
                </p>

                <div class="mt-8 rounded-2xl border border-forest-100 bg-cream-50 p-6 text-left">
                    <h3 class="font-extrabold text-ink">Detail Donasi</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">ID Donasi</dt>
                            <dd class="font-mono font-semibold text-ink">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-forest-100 pt-3">
                            <dt class="text-ink-soft">Jumlah</dt>
                            <dd class="font-extrabold text-forest-800">{{ $donation->formatted_amount }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Nama Donatur</dt>
                            <dd class="font-semibold text-ink">{{ $donation->donor_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Campaign</dt>
                            <dd class="text-right font-semibold text-ink">{{ $donation->campaign->title }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Tanggal</dt>
                            <dd class="font-semibold text-ink">{{ $donation->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('donations.show', $donation->campaign->slug) }}" class="btn-primary flex-1">
                        <x-app-icon name="arrow-left" class="h-4 w-4"/>
                        Kembali ke Campaign
                    </a>
                    <a href="{{ route('donations.index') }}" class="btn-outline flex-1">
                        Lihat Campaign Lain
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
