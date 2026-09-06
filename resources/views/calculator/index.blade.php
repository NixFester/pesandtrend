@extends('layouts.app')
@section('title', 'Kalkulator Biaya — Pesantrends')

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">Kalkulator Biaya</span>
            </nav>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Kalkulator Biaya Pendidikan</h1>
            <p class="mt-2 text-sm text-white/70">Hitung total biaya lengkap — transparan, tanpa yang tersembunyi</p>
        </div>
    </section>

    <section class="bg-cream-50 py-12 sm:py-16">
        <div class="container-app">
            @include('components.flash')

            <div class="grid gap-8 lg:grid-cols-[1fr_380px]">
                {{-- Form --}}
                <form method="GET" action="{{ route('calculator.index') }}" class="card-shadow-lg space-y-6 rounded-3xl bg-white p-6 sm:p-8">
                    <div>
                        <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                            <x-app-icon name="wallet" class="h-5 w-5 text-gold-600"/>
                            Masukkan Rincian Biaya
                        </h2>
                        <p class="mt-1.5 text-sm text-ink-soft">Gunakan angka dari halaman detail sekolah, atau isi perkiraan Anda sendiri.</p>
                    </div>

                    {{-- Preset --}}
                    <div class="rounded-2xl border border-forest-100 bg-forest-50/50 p-4">
                        <label for="school-preset" class="text-xs font-extrabold uppercase tracking-wide text-forest-900">Isi otomatis dari data sekolah</label>
                        <select id="school-preset" data-school-preset="{{ route('calculator.index') }}" class="input-field mt-2">
                            <option value="">— Pilih sekolah —</option>
                            @foreach ($schools as $preset)
                                <option value="{{ $preset->id }}" data-preset="{{ json_encode(['pangkal' => $preset->uang_pangkal, 'spp' => $preset->spp_monthly, 'asrama' => $preset->asrama_monthly, 'seragam' => $preset->seragam_fee, 'ekskul' => $preset->ekskul_fee, 'tour' => $preset->study_tour_fee]) }}">{{ $preset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ([
                            ['name' => 'pangkal', 'label' => 'Uang Pangkal', 'note' => 'Satu kali'],
                            ['name' => 'spp', 'label' => 'SPP Bulanan', 'note' => 'Per bulan'],
                            ['name' => 'asrama', 'label' => 'Biaya Asrama', 'note' => 'Per bulan'],
                            ['name' => 'seragam', 'label' => 'Seragam &amp; Perlengkapan', 'note' => 'Sekali'],
                            ['name' => 'ekskul', 'label' => 'Ekstrakurikuler', 'note' => 'Per tahun'],
                            ['name' => 'tour', 'label' => 'Study Tour Tahunan', 'note' => 'Per tahun'],
                        ] as $field)
                            <div>
                                <label for="{{ $field['name'] }}" class="flex items-center justify-between text-sm font-extrabold text-ink">
                                    {{ $field['label'] }}
                                    <span class="text-[11px] font-semibold text-ink-soft">{{ $field['note'] }}</span>
                                </label>
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-ink-soft">Rp</span>
                                    <input id="{{ $field['name'] }}" type="text" inputmode="numeric" data-rupiah-input
                                           name="{{ $field['name'] }}"
                                           value="{{ number_format($input[$field['name']], 0, ',', '.') }}"
                                           class="input-field !py-3.5 pl-10 font-bold" required>
                                </div>
                            </div>
                        @endforeach

                        <div>
                            <label for="durasi" class="flex items-center justify-between text-sm font-extrabold text-ink">
                                Durasi Perhitungan
                                <span class="text-[11px] font-semibold text-ink-soft">bulan</span>
                            </label>
                            <select id="durasi" name="durasi" class="input-field !py-3.5 mt-2 font-bold">
                                @foreach ([3, 6, 12] as $months)
                                    <option value="{{ $months }}" {{ $input['durasi'] === $months ? 'selected' : '' }}>{{ $months }} bulan</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        <x-app-icon name="calculator" class="h-4 w-4"/>
                        Hitung Total Biaya
                    </button>
                </form>

                {{-- Desktop result sidebar (always visible on lg+) --}}
                <aside class="space-y-6 hidden lg:block">
                    <div class="card-shadow-lg sticky top-16 rounded-3xl bg-forest-950 p-7">
                        <h2 class="flex items-center gap-2.5 text-base font-extrabold text-white">
                            <x-app-icon name="trending-up" class="h-5 w-5 text-gold-400"/>
                            Hasil Perhitungan
                        </h2>

                        <div class="mt-5 rounded-2xl bg-gold-500/15 p-5 text-center">
                            <p class="text-xs font-bold uppercase tracking-wider text-gold-300">Total/bulan rata-rata</p>
                            <p class="mt-1.5 text-3xl font-extrabold text-gold-400">Rp{{ number_format($result['avgMonthly'], 0, ',', '.') }}</p>
                            <p class="mt-1 text-[11px] text-white/60">termasuk biaya tahunan &amp; sekali bayar</p>
                        </div>

                        <dl class="mt-5 space-y-3.5 text-sm">
                            <div class="flex items-center justify-between border-b border-white/10 pb-3.5">
                                <dt class="font-semibold text-white/70">Biaya bulanan berulang</dt>
                                <dd class="font-extrabold text-white">Rp{{ number_format($result['monthly'], 0, ',', '.') }}/bln</dd>
                            </div>
                            <div class="flex items-center justify-between border-b border-white/10 pb-3.5">
                                <dt class="font-semibold text-white/70">Total berulang ({{ $input['durasi'] }} bln)</dt>
                                <dd class="font-extrabold text-white">Rp{{ number_format($result['yearlyRecurring'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-b border-white/10 pb-3.5">
                                <dt class="font-semibold text-white/70">Biaya sekali bayar &amp; tahunan</dt>
                                <dd class="font-extrabold text-white">Rp{{ number_format($result['oneTime'], 0, ',', '.') }}</dd>
                            </div>
                        </dl>

                        <div class="mt-5 flex items-center justify-between rounded-2xl bg-white/10 px-5 py-4">
                            <span class="text-xs font-bold text-white/80">Total {{ $input['durasi'] }} bulan</span>
                            <span class="text-xl font-extrabold text-gold-400">Rp{{ number_format($result['total'], 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('schools.index') }}" class="btn-gold mt-5 w-full">
                            <x-app-icon name="search" class="h-4 w-4" :stroke="2.5"/>
                            Cari Sekolah Lainnya
                        </a>
                    </div>

                    <div class="card-shadow rounded-2xl border border-gold-200 bg-gold-50 p-6">
                        <h3 class="flex items-center gap-2 text-sm font-extrabold text-gold-800">
                            <x-app-icon name="info" class="h-4 w-4"/>
                            Tips Menghemat
                        </h3>
                        <ul class="mt-3 space-y-2 text-xs leading-relaxed text-gold-800/80">
                            <li>• Pembayaran tahunan biasanya mendapat diskon 5–10%</li>
                            <li>• Cari program beasiswa hafiz dan prestasi di halaman detail sekolah</li>
                            <li>• Biaya seragam di tahun berikutnya umumnya lebih kecil</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
