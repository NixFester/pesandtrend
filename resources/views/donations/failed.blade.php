@extends('layouts.app')
@section('title', 'Donasi Gagal — Bantu Pesantren')

@section('content')
    <section class="min-h-[60vh] bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-lg text-center">
                <span class="flex h-20 w-20 mx-auto items-center justify-center rounded-full bg-red-100 text-red-600">
                    <x-app-icon name="alert-circle" class="h-10 w-10"/>
                </span>

                <h1 class="mt-6 text-2xl font-extrabold text-ink sm:text-3xl">Donasi Tidak Berhasil</h1>
                <p class="mt-3 text-sm text-ink-soft sm:text-base">
                    Maaf, donasi Anda untuk <strong>{{ $donation->campaign->title }}</strong> tidak dapat diproses.
                    Silakan coba lagi.
                </p>

                <div class="mt-8 rounded-2xl border border-red-100 bg-red-50 p-6 text-left">
                    <h3 class="font-extrabold text-red-800">Kemungkinan Penyebab:</h3>
                    <ul class="mt-3 space-y-2 text-sm text-red-700">
                        <li class="flex items-start gap-2">
                            <x-app-icon name="x" class="mt-0.5 h-4 w-4 shrink-0"/>
                            Pembayaran melebihi batas waktu
                        </li>
                        <li class="flex items-start gap-2">
                            <x-app-icon name="x" class="mt-0.5 h-4 w-4 shrink-0"/>
                            Saldo atau limit kartu tidak mencukupi
                        </li>
                        <li class="flex items-start gap-2">
                            <x-app-icon name="x" class="mt-0.5 h-4 w-4 shrink-0"/>
                            Transaksi diblokir oleh bank
                        </li>
                    </ul>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('donations.show', $donation->campaign->slug) }}" class="btn-primary flex-1">
                        <x-app-icon name="refresh" class="h-4 w-4"/>
                        Coba Lagi
                    </a>
                    <a href="{{ route('donations.index') }}" class="btn-outline flex-1">
                        Lihat Campaign Lain
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
