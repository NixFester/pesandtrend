@extends('layouts.app')
@section('title', 'Test Pembayaran - Bantu Pesantren')

@section('content')
    <section class="min-h-[70vh] bg-white py-16 sm:py-20">
        <div class="container-app">
            <div class="mx-auto max-w-lg">
                {{-- Header --}}
                <div class="mb-8 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gold-100">
                        <x-app-icon name="credit-card" class="h-8 w-8 text-gold-600"/>
                    </div>
                    <h1 class="mt-4 text-2xl font-extrabold text-ink">Test Pembayaran</h1>
                    <p class="mt-2 text-sm text-ink-soft">Halaman simulasi pembayaran Xendit (Development Only)</p>
                </div>

                {{-- Donation Summary --}}
                <div class="card-shadow rounded-2xl border border-forest-100 bg-cream-50 p-6">
                    <div class="mb-4 pb-4 border-b border-forest-100">
                        <p class="text-xs font-semibold uppercase tracking-wider text-ink-soft">Campaign</p>
                        <p class="mt-1 font-extrabold text-ink">{{ $campaign->title }}</p>
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Nama Donatur</dt>
                            <dd class="font-semibold text-ink">{{ $donation->donor_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-soft">Email</dt>
                            <dd class="font-semibold text-ink">{{ $donation->donor_email }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-forest-100 pt-3">
                            <dt class="text-ink-soft">Jumlah</dt>
                            <dd class="text-xl font-extrabold text-forest-800">{{ $donation->formatted_amount }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Payment Methods --}}
                <div class="mt-6 card-shadow rounded-2xl border border-forest-100 bg-white p-6">
                    <h2 class="font-extrabold text-ink">Metode Pembayaran</h2>
                    <p class="mt-1 text-xs text-ink-soft">Pilih metode pembayaran untuk simulasi</p>

                    <form action="{{ route('donations.process-test-payment', $donation) }}" method="POST" class="mt-6 space-y-3">
                        @csrf

                        <div class="space-y-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-forest-200 p-4 transition hover:border-forest-400 has-[:checked]:border-forest-900 has-[:checked]:bg-forest-50">
                                <input type="radio" name="method" value="bank_transfer" class="accent-forest-900" checked>
                                <div class="flex-1">
                                    <p class="font-semibold text-ink">Transfer Bank</p>
                                    <p class="text-xs text-ink-soft">BCA, Mandiri, BNI, BRI</p>
                                </div>
                            </label>

                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-forest-200 p-4 transition hover:border-forest-400 has-[:checked]:border-forest-900 has-[:checked]:bg-forest-50">
                                <input type="radio" name="method" value="ewallet" class="accent-forest-900">
                                <div class="flex-1">
                                    <p class="font-semibold text-ink">E-Wallet</p>
                                    <p class="text-xs text-ink-soft">GoPay, OVO, DANA, ShopeePay</p>
                                </div>
                            </label>

                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-forest-200 p-4 transition hover:border-forest-400 has-[:checked]:border-forest-900 has-[:checked]:bg-forest-50">
                                <input type="radio" name="method" value="credit_card" class="accent-forest-900">
                                <div class="flex-1">
                                    <p class="font-semibold text-ink">Kartu Kredit</p>
                                    <p class="text-xs text-ink-soft">Visa, Mastercard, JCB</p>
                                </div>
                            </label>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn-primary w-full !py-4">
                                <x-app-icon name="check-circle" class="h-5 w-5"/>
                                Bayar Sekarang (Test)
                            </button>
                        </div>

                        <p class="text-center text-xs text-ink-soft">
                            Ini adalah halaman test. Klik "Bayar Sekarang" untuk mensimulasikan pembayaran berhasil.
                        </p>
                    </form>
                </div>

                {{-- Cancel --}}
                <div class="mt-4 text-center">
                    <a href="{{ route('donations.show', $campaign->slug) }}" class="text-sm font-semibold text-ink-soft hover:text-ink">
                        ← Kembali ke Campaign
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
