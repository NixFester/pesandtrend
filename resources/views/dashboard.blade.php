@extends('layouts.app')
@section('title', 'Dashboard — Pesantrends')

@section('content')
    {{-- ═══════════ HERO HEADER ═══════════ --}}
    <section class="bg-forest-950 py-10 sm:py-12">
        <div class="container-app flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gold-500 text-xl font-extrabold text-forest-950">
                    {{ Str::substr($user->name, 0, 1) }}
                </span>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white">{{ $user->name }}</h1>
                    <p class="mt-1 flex items-center gap-2 text-xs font-semibold text-white/70">
                        <span class="rounded-full bg-gold-500/20 px-2.5 py-1 text-gold-400">Anggota Aktif</span>
                        <span>Bergabung {{ $user->created_at->translatedFormat('d M Y') }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.edit') }}" class="btn-ghost-light !py-2.5 text-xs">
                    <x-app-icon name="settings" class="h-4 w-4"/>
                    Edit Profil
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-ghost-light !py-2.5 text-xs">
                        <x-app-icon name="log-out" class="h-4 w-4"/>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="bg-cream-50 py-10 sm:py-14">
        <div class="container-app space-y-10">
            @include('components.flash')

            {{-- ═══════════ QUICK STATS ═══════════ --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'Total Pendaftaran', 'value' => $stats['total_applications'], 'icon' => 'send', 'color' => 'bg-forest-50 text-forest-700'],
                    ['label' => 'Pendaftaran Aktif', 'value' => $stats['active_applications'], 'icon' => 'clock', 'color' => 'bg-gold-50 text-gold-700'],
                    ['label' => 'Sekolah Tersimpan', 'value' => $stats['saved_schools'], 'icon' => 'bookmark', 'color' => 'bg-forest-50 text-forest-700'],
                    ['label' => 'Pembayaran Lunas', 'value' => $stats['paid_applications'], 'icon' => 'credit-card', 'color' => 'bg-gold-50 text-gold-700'],
                ] as $stat)
                <div class="card-shadow flex items-center gap-4 rounded-2xl bg-white p-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $stat['color'] }}">
                        <x-app-icon :name="$stat['icon']" class="h-5 w-5"/>
                    </span>
                    <div>
                        <p class="text-2xl font-extrabold text-ink">{{ $stat['value'] }}</p>
                        <p class="text-xs font-semibold text-ink-soft">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ═══════════ QUICK ACTIONS ═══════════ --}}
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ([
                    ['label' => 'Cari Sekolah', 'route' => route('schools.index'), 'icon' => 'search', 'bg' => 'bg-forest-900 text-white hover:bg-forest-700'],
                    ['label' => 'Daftar Sekolah', 'route' => route('onboarding.apply'), 'icon' => 'send', 'bg' => 'bg-gold-500 text-forest-950 hover:bg-gold-400'],
                    ['label' => 'Bandingkan', 'route' => route('compare.index'), 'icon' => 'scale', 'bg' => 'bg-white text-forest-900 hover:bg-forest-50 border border-forest-100'],
                    ['label' => 'Kalkulator Biaya', 'route' => route('calculator.index'), 'icon' => 'calculator', 'bg' => 'bg-white text-forest-900 hover:bg-forest-50 border border-forest-100'],
                ] as $action)
                <a href="{{ $action['route'] }}" class="card-shadow flex items-center gap-3 rounded-2xl {{ $action['bg'] }} p-4 transition-all duration-200 hover:-translate-y-0.5">
                    <x-app-icon :name="$action['icon']" class="h-5 w-5 shrink-0"/>
                    <span class="text-sm font-bold">{{ $action['label'] }}</span>
                </a>
                @endforeach
            </div>

            {{-- ═══════════ PENDAFTARAN SAYA ═══════════ --}}
            <div>
                <div class="flex items-end justify-between gap-4 mb-5">
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-app-icon name="send" class="h-5 w-5 text-gold-600"/>
                        Pendaftaran Saya
                    </h2>
                    <a href="{{ route('onboarding.apply') }}" class="text-sm font-bold text-forest-800 transition hover:text-forest-600">+ Buat Pendaftaran Baru</a>
                </div>

                @if($applications->isNotEmpty())
                <div class="space-y-4">
                    @foreach($applications as $app)
                    @php
                        $statusSteps = ['draft', 'submitted', 'document_review', 'verified', 'payment_pending', 'paid', 'completed'];
                        $currentIdx = array_search($app->status->value, $statusSteps);
                        if ($currentIdx === false) $currentIdx = 0;
                        $stepLabels = ['Draf', 'Diajukan', 'Review', 'Verifikasi', 'Bayar', 'Lunas', 'Selesai'];
                        $isRejected = $app->status->value === 'rejected';
                        $isCancelled = $app->status->value === 'cancelled';
                    @endphp
                    <div class="card-shadow rounded-2xl bg-white p-5 sm:p-6">
                        {{-- Header --}}
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base font-extrabold text-ink">{{ $app->student_name }}</span>
                                    <span class="chip-status-{{ $app->status->color() }}">{{ $app->status->label() }}</span>
                                </div>
                                <p class="text-xs text-ink-soft">
                                    {{ $app->school->name }} · Kode: <span class="font-mono">{{ $app->public_id }}</span>
                                    · {{ $app->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($app->latestPayment && !$app->latestPayment->isPaid() && $app->latestPayment->invoice_url)
                                <a href="{{ $app->latestPayment->invoice_url }}" target="_blank" class="btn-gold !py-2 text-xs">Bayar Sekarang</a>
                                @endif
                                @if($app->latestPayment?->isPaid())
                                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('proof.print', ['payment' => $app->latestPayment->id]) }}" class="btn-outline !py-2 text-xs">
                                    <x-app-icon name="printer" class="h-3.5 w-3.5"/>
                                    Cetak Bukti
                                </a>
                                @endif
                                <a href="{{ route('onboarding.show', $app) }}" class="btn-primary !py-2 text-xs">Lihat Detail</a>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        @if(!$isRejected && !$isCancelled)
                        <div class="mt-5">
                            <div class="flex items-center gap-0.5">
                                @foreach($stepLabels as $i => $stepLabel)
                                @php
                                    $isCompleted = $i <= $currentIdx;
                                    $isCurrent = $i === $currentIdx;
                                @endphp
                                <div class="flex-1">
                                    <div class="relative h-1.5 rounded-full {{ $isCompleted ? 'bg-forest-500' : 'bg-forest-100' }} transition-colors"></div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-2 flex justify-between">
                                @foreach($stepLabels as $i => $stepLabel)
                                @php
                                    $isCompleted = $i <= $currentIdx;
                                    $isCurrent = $i === $currentIdx;
                                @endphp
                                <span class="text-[10px] font-semibold {{ $isCurrent ? 'text-forest-700' : ($isCompleted ? 'text-forest-500' : 'text-ink-soft/50') }}">
                                    {{ $stepLabel }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Payment info --}}
                        @if($app->latestPayment)
                        <div class="mt-4 flex items-center gap-4 rounded-xl bg-forest-50 px-4 py-3 text-xs">
                            <x-app-icon name="credit-card" class="h-4 w-4 text-forest-600 shrink-0"/>
                            <span class="text-ink-soft">Pembayaran:</span>
                            <span class="font-bold text-ink">{{ $app->latestPayment->statusLabel() }}</span>
                            <span class="text-ink-soft">·</span>
                            <span class="font-extrabold text-forest-800">Rp{{ number_format($app->latestPayment->amount, 0, ',', '.') }}</span>
                            @if($app->latestPayment->paid_at)
                            <span class="text-ink-soft">· Dibayar {{ $app->latestPayment->paid_at->translatedFormat('d M Y') }}</span>
                            @endif
                        </div>
                        @endif

                        {{-- Rejection reason --}}
                        @if($isRejected && $app->rejection_reason)
                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs">
                            <span class="font-bold text-red-700">Alasan Penolakan:</span>
                            <span class="text-red-600 ml-1">{{ $app->rejection_reason }}</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="card-shadow rounded-2xl border border-dashed border-forest-200 bg-white p-8 text-center">
                    <x-app-icon name="send" class="mx-auto h-10 w-10 text-forest-200"/>
                    <p class="mt-3 text-sm font-semibold text-ink-soft">Belum ada riwayat pendaftaran sekolah.</p>
                    <p class="mt-1 text-xs text-ink-soft/70">Mulai cari sekolah idaman dan ajukan pendaftaran.</p>
                    <a href="{{ route('onboarding.apply') }}" class="btn-primary mt-4 inline-block text-xs">Daftar Sekolah Sekarang</a>
                </div>
                @endif
            </div>

            {{-- ═══════════ SEKOLAH TERSIMPAN ═══════════ --}}
            <div>
                <div class="flex items-end justify-between gap-4">
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-app-icon name="bookmark" class="h-5 w-5 text-gold-600"/>
                        Sekolah Tersimpan
                    </h2>
                    <a href="{{ route('schools.index') }}" class="text-sm font-bold text-forest-800 transition hover:text-forest-600">Tambah sekolah →</a>
                </div>

                @if ($savedSchools->count())
                    <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($savedSchools as $school)
                            <div class="card-shadow rounded-2xl bg-white p-5">
                                <div class="flex items-start gap-4">
                                    <img src="{{ asset($school->image) }}" alt="" class="h-16 w-16 rounded-xl object-cover">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('schools.show', $school->slug) }}" class="block truncate text-sm font-extrabold text-ink transition hover:text-forest-700">{{ $school->name }}</a>
                                        <p class="mt-0.5 text-xs text-ink-soft">{{ $school->city }}, {{ $school->province }}</p>
                                        <p class="mt-1.5 text-sm font-extrabold text-forest-900">Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}<span class="text-[11px] font-semibold text-ink-soft">/bln</span></p>
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('schools.show', $school->slug) }}" class="btn-primary flex-1 !py-2.5 text-xs">Lihat Detail</a>
                                    <form action="{{ route('schools.save', $school) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex h-[42px] w-[42px] items-center justify-center rounded-xl border border-forest-100 text-ink-soft transition hover:border-red-300 hover:bg-red-50 hover:text-red-600" title="Hapus dari tersimpan" aria-label="Hapus {{ $school->name }} dari tersimpan">
                                            <x-app-icon name="x" class="h-4 w-4"/>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card-shadow mt-5 rounded-2xl border border-dashed border-forest-200 bg-white p-8 text-center">
                        <x-app-icon name="bookmark" class="mx-auto h-10 w-10 text-forest-200"/>
                        <p class="mt-3 text-sm font-semibold text-ink-soft">Belum ada sekolah tersimpan.</p>
                        <p class="mt-1 text-xs text-ink-soft/70">Tekan tombol "Simpan Sekolah" di halaman detail sekolah.</p>
                        <a href="{{ route('schools.index') }}" class="btn-primary mt-4 inline-block text-xs">Cari Sekolah</a>
                    </div>
                @endif
            </div>

            {{-- ═══════════ REKOMENDASI ═══════════ --}}
            <div>
                <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                    <x-app-icon name="sparkles" class="h-5 w-5 text-gold-600"/>
                    Rekomendasi Untukmu
                </h2>
                @if($savedSchools->count())
                <p class="mt-1 text-xs text-ink-soft">Berdasarkan sekolah yang kamu simpan di {{ $savedSchools->pluck('city')->unique()->implode(', ') }}.</p>
                @endif
                <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($recommended as $school)
                        <x-school-card :school="$school"/>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════ TERAKHIR DILIHAT + ARTIKEL ═══════════ --}}
            <div class="grid gap-10 lg:grid-cols-2">
                {{-- Terakhir dilihat --}}
                @if ($recentlyViewed->count())
                    <div>
                        <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                            <x-app-icon name="clock" class="h-5 w-5 text-gold-600"/>
                            Terakhir Dilihat
                        </h2>
                        <div class="mt-5 space-y-3">
                            @foreach ($recentlyViewed as $school)
                                <a href="{{ route('schools.show', $school->slug) }}" class="card-shadow flex items-center gap-3 rounded-2xl bg-white p-3 transition hover:-translate-y-0.5">
                                    <img src="{{ asset($school->image) }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-extrabold text-ink">{{ $school->name }}</p>
                                        <p class="text-xs text-ink-soft">{{ $school->city }} · Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}/bln</p>
                                    </div>
                                    <x-app-icon name="chevron-right" class="h-4 w-4 shrink-0 text-ink-soft"/>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Artikel populer --}}
                <div>
                    <h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
                        <x-app-icon name="newspaper" class="h-5 w-5 text-gold-600"/>
                        Artikel Populer
                    </h2>
                    <div class="mt-5 space-y-3">
                        @foreach ($articlesRead as $article)
                            <a href="{{ route('articles.show', $article->slug) }}" class="card-shadow flex items-center gap-3 rounded-2xl bg-white p-3 transition hover:-translate-y-0.5">
                                <img src="{{ asset($article->image) }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-extrabold text-ink">{{ $article->title }}</p>
                                    <p class="text-xs text-ink-soft">{{ $article->read_minutes }} min baca · {{ number_format($article->views) }} dibaca</p>
                                </div>
                                <x-app-icon name="chevron-right" class="h-4 w-4 shrink-0 text-ink-soft"/>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ═══════════ PROFIL & PENGATURAN ═══════════ --}}
            <div class="card-shadow rounded-2xl bg-white p-6 sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-forest-900 text-xl font-extrabold text-gold-400">
                            {{ Str::substr($user->name, 0, 1) }}
                        </span>
                        <div>
                            <h2 class="text-lg font-extrabold text-ink">{{ $user->name }}</h2>
                            <div class="mt-1 grid gap-x-6 gap-y-1 text-xs text-ink-soft sm:grid-cols-2">
                                <span>{{ $user->email }}</span>
                                <span>{{ $user->phone ?? 'Belum ada nomor HP' }}</span>
                                <span>WhatsApp: {{ $user->whatsapp ?? '—' }}</span>
                                <span>Bergabung: {{ $user->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn-outline !py-2.5 text-xs">
                        <x-app-icon name="edit" class="h-4 w-4"/>
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
