@extends('layouts.app')

@section('title', 'Pendaftaran Saya — Pesantrends')

@section('content')
<div class="container-app py-8">
    <x-flash/>

    <h1 class="text-2xl font-extrabold text-ink mb-6">Pendaftaran Saya</h1>

    @if($applications->isEmpty())
    <div class="text-center py-16">
        <x-app-icon name="send" class="h-12 w-12 text-forest-300 mx-auto mb-4"/>
        <p class="text-ink-soft mb-4">Belum ada pendaftaran.</p>
        <a href="{{ route('schools.index') }}" class="btn-primary">Cari Sekolah</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($applications as $app)
        <a href="{{ route('onboarding.show', $app) }}" class="block rounded-2xl border border-forest-100 p-5 hover:border-forest-300 transition card-shadow">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="font-bold text-ink">{{ $app->student_name }}</h3>
                    <p class="text-sm text-ink-soft">{{ $app->school->name }}</p>
                </div>
                <span class="chip {{ $app->status->color() === 'success' ? 'bg-forest-100 text-forest-800' : ($app->status->color() === 'danger' ? 'bg-red-100 text-red-800' : ($app->status->color() === 'warning' ? 'bg-gold-100 text-gold-800' : 'bg-gray-100 text-gray-600')) }}">
                    {{ $app->status->label() }}
                </span>
            </div>
            @if($app->latestPayment)
            <p class="mt-2 text-xs text-ink-soft">
                Pembayaran: <span class="font-semibold">{{ $app->latestPayment->statusLabel() }}</span>
            </p>
            @endif
            <p class="mt-1 text-xs text-ink-soft/60">{{ $app->created_at->translatedFormat('d F Y') }}</p>
        </a>
        @endforeach
    </div>

    <div class="mt-6">{{ $applications->links() }}</div>
    @endif
</div>
@endsection
