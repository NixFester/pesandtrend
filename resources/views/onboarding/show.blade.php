@extends('layouts.app')

@section('title', 'Detail Pendaftaran — Pesantrends')

@section('content')
<div class="container-app py-8">
    <a href="{{ route('onboarding.index') }}" class="inline-flex items-center gap-1 text-sm text-forest-600 hover:text-forest-800 mb-6">
        <x-app-icon name="arrow-left" class="h-4 w-4"/> Kembali
    </a>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Main info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-forest-100 p-6 card-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-extrabold text-ink">{{ $application->student_name }}</h1>
                    <span class="chip-gold">{{ $application->status->label() }}</span>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-ink-soft">Sekolah:</span> <strong>{{ $application->school->name }}</strong></div>
                    <div><span class="text-ink-soft">Jenjang:</span> <strong>{{ $application->target_jenjang ?? '-' }}</strong></div>
                    <div><span class="text-ink-soft">Orang Tua:</span> <strong>{{ $application->parent_name }}</strong></div>
                    <div><span class="text-ink-soft">Email:</span> <strong>{{ $application->parent_email ?? '-' }}</strong></div>
                    <div><span class="text-ink-soft">Telepon:</span> <strong>{{ $application->parent_phone ?? '-' }}</strong></div>
                    <div><span class="text-ink-soft">Kode:</span> <strong class="font-mono text-xs">{{ $application->public_id }}</strong></div>
                </div>
            </div>

            {{-- Documents --}}
            @if($application->documents->isNotEmpty())
            <div class="rounded-2xl border border-forest-100 p-6 card-shadow">
                <h2 class="font-bold text-ink mb-4">Dokumen</h2>
                <div class="space-y-2">
                    @foreach($application->documents as $doc)
                    <div class="flex items-center justify-between text-sm">
                        <span>{{ $doc->kind->label() }} — {{ $doc->original_name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Rejection --}}
            @if($application->status->value === 'rejected' && $application->rejection_reason)
            <div class="rounded-2xl border border-red-200 bg-red-50 p-6">
                <h3 class="font-bold text-red-700 mb-2">Alasan Penolakan</h3>
                <p class="text-sm text-red-600">{{ $application->rejection_reason }}</p>
            </div>
            @endif
        </div>

        {{-- Sidebar —  payment status --}}
        <div class="space-y-4">
            @if($application->payments->isNotEmpty())
            @php($latestPayment = $application->payments->sortByDesc('created_at')->first())
            <div class="rounded-2xl border border-forest-100 p-6 card-shadow">
                <h3 class="font-bold text-ink mb-3">Pembayaran</h3>
                <div class="text-sm space-y-2">
                    <div><span class="text-ink-soft">Status:</span> <strong>{{ $latestPayment->statusLabel() }}</strong></div>
                    <div><span class="text-ink-soft">Total:</span> <strong>Rp {{ number_format($latestPayment->amount, 0, ',', '.') }}</strong></div>
                    @if($latestPayment->paid_at)
                    <div><span class="text-ink-soft">Dibayar:</span> <strong>{{ $latestPayment->paid_at->translatedFormat('d F Y, H:i') }}</strong></div>
                    @endif
                </div>

                @if($latestPayment->invoice_url && !$latestPayment->isPaid())
                <a href="{{ $latestPayment->invoice_url }}" target="_blank" class="btn-primary w-full mt-4 text-center">Bayar Sekarang</a>
                @endif

                @if($latestPayment->isPaid())
                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('proof.print', ['payment' => $latestPayment->id]) }}" class="btn-outline w-full mt-4 text-center">Cetak Bukti Pembayaran</a>
                @endif
            </div>
            @endif

            {{-- Status timeline --}}
            <div class="rounded-2xl border border-forest-100 p-6 card-shadow">
                <h3 class="font-bold text-ink mb-3">Timeline</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-forest-500"></div>
                        <span>Dibuat: {{ $application->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @if($application->submitted_at)
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-gold-500"></div>
                        <span>Diajukan: {{ $application->submitted_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($application->verified_at)
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-forest-600"></div>
                        <span>Terverifikasi: {{ $application->verified_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
