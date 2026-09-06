@extends('layouts.app')
@section('title', 'Artikel & Panduan — Pesantrends')

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">Artikel</span>
            </nav>
            <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Artikel &amp; Panduan</h1>
                    <p class="mt-2 text-sm text-white/70">Wawasan terbaik untuk orang tua cerdas — {{ $totalArticles }} artikel tersedia</p>
                </div>
                <form method="GET" class="relative w-full max-w-sm">
                    <x-app-icon name="search" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-soft/60"/>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari artikel..." aria-label="Cari artikel"
                           class="w-full rounded-2xl border-0 bg-white/10 py-3.5 pl-12 pr-4 text-sm font-medium text-white placeholder:text-white/50 backdrop-blur focus:bg-white/20 focus:outline-none focus:ring-2 focus:ring-gold-400/50">
                </form>
            </div>
        </div>
    </section>

    <section class="bg-white py-10 sm:py-14">
        <div class="container-app">
            @include('components.flash')

            {{-- Kategori --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('articles.index') }}" class="{{ ! $currentCategory ? 'bg-forest-900 text-white' : 'bg-forest-50 text-forest-800 hover:bg-forest-100' }} rounded-full px-4 py-2 text-xs font-bold transition">Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('articles.index', ['kategori' => $category]) }}" class="{{ $currentCategory === $category ? 'bg-forest-900 text-white' : 'bg-forest-50 text-forest-800 hover:bg-forest-100' }} rounded-full px-4 py-2 text-xs font-bold transition">{{ $category }}</a>
                @endforeach
            </div>

            @if ($articles->count())
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($articles as $article)
                        <x-article-card :article="$article"/>
                    @endforeach
                </div>
                <nav class="mt-10 flex flex-wrap items-center justify-center gap-1.5" aria-label="Navigasi halaman">
                    {{ $articles->links() }}
                </nav>
            @else
                <div class="card-shadow mt-8 rounded-2xl border border-forest-100 bg-cream-50 p-14 text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-forest-100 text-forest-700">
                        <x-app-icon name="newspaper" class="h-7 w-7"/>
                    </span>
                    <h2 class="mt-4 text-lg font-extrabold text-ink">Artikel tidak ditemukan</h2>
                    <p class="mx-auto mt-2 max-w-sm text-sm text-ink-soft">Coba kata kunci lain atau jelajahi kategori yang tersedia.</p>
                    <a href="{{ route('articles.index') }}" class="btn-primary mt-6">Semua Artikel</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Terpopuler --}}
    @if ($popular->count())
        <section class="bg-cream-50 py-12 sm:py-16" aria-labelledby="populer-heading">
            <div class="container-app">
                <h2 id="populer-heading" class="flex items-center gap-2.5 text-xl font-extrabold tracking-tight text-ink">
                    <x-app-icon name="trending-up" class="h-5 w-5 text-gold-600"/>
                    Artikel Terpopuler
                </h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($popular as $article)
                        <a href="{{ route('articles.show', $article->slug) }}" class="card-shadow group flex items-center gap-4 rounded-2xl bg-white p-4 transition hover:-translate-y-0.5">
                            <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="h-16 w-20 shrink-0 rounded-xl object-cover" loading="lazy">
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-gold-700">{{ $article->category }}</p>
                                <h3 class="mt-0.5 line-clamp-2 text-sm font-extrabold leading-snug text-ink group-hover:text-forest-800">{{ $article->title }}</h3>
                                <p class="mt-1 text-[11px] text-ink-soft">{{ $article->read_minutes }} min baca · {{ number_format($article->views) }} dibaca</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ================= NEWSLETTER ================= --}}
    <section class="bg-white py-12 sm:py-16">
        <div class="container-app">
            <div class="card-shadow-lg grid items-center gap-8 overflow-hidden rounded-3xl border border-forest-100 bg-cream-50 p-8 sm:p-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-gold-100 px-3.5 py-1.5 text-xs font-bold text-gold-800">
                        <x-icon name="newspaper" class="h-3.5 w-3.5"/>
                        Newsletter
                    </span>
                    <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-ink">Dapatkan Artikel Pilihan Setiap Minggu</h2>
                    <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                        Tips memilih sekolah, informasi beasiswa, dan panduan pendaftaran langsung ke email Anda.
                    </p>
                </div>
                <form action="{{ route('newsletter.store') }}" method="POST" class="flex flex-col gap-3 sm:flex-row">
                    @csrf
                    <label class="relative flex-1">
                        <x-icon name="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-soft/60"/>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Anda..." required aria-label="Alamat email"
                               class="input-field !py-4 pl-12">
                    </label>
                    <button type="submit" class="btn-primary !py-4">
                        <x-icon name="send" class="h-4 w-4"/>
                        Langganan
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
