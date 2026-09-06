@extends('layouts.app')
@section('title', $article->title.' — Pesantrends')

@section('content')
    <article>
        <header class="relative overflow-hidden bg-forest-950">
            <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="absolute inset-0 h-full w-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-t from-forest-950 via-forest-950/75 to-forest-950/45" aria-hidden="true"></div>
            <div class="container-app relative py-14 sm:py-20">
                <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                    <span class="mx-2" aria-hidden="true">/</span>
                    <a href="{{ route('articles.index') }}" class="transition hover:text-white">Artikel</a>
                </nav>
                <div class="mt-5 max-w-3xl">
                    <span class="rounded-full bg-gold-500 px-3.5 py-1.5 text-[11px] font-extrabold text-forest-950">{{ $article->category }}</span>
                    <h1 class="mt-4 text-2xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl">{{ $article->title }}</h1>
                    <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-semibold text-white/75">
                        <span class="flex items-center gap-1.5"><x-app-icon name="calendar" class="h-4 w-4 text-gold-400"/>{{ $article->published_label }}</span>
                        <span class="flex items-center gap-1.5"><x-app-icon name="clock" class="h-4 w-4 text-gold-400"/>{{ $article->read_minutes }} min baca</span>
                        <span class="flex items-center gap-1.5"><x-app-icon name="eye" class="h-4 w-4 text-gold-400"/>{{ number_format($article->views) }} dibaca</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="bg-white py-12 sm:py-16">
            <div class="container-app grid gap-12 lg:grid-cols-[1fr_300px]">
                <div>
                    @include('components.flash')

                    <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="card-shadow aspect-[16/8] w-full rounded-3xl object-cover">
                    <p class="mt-6 border-l-4 border-gold-500 pl-5 text-base font-semibold leading-relaxed text-ink-soft">{{ $article->excerpt }}</p>

                    <div class="article-body mt-8">
                        {!! $article->formatted_content !!}
                    </div>

                    <div class="mt-10 rounded-2xl border border-forest-100 bg-cream-50 p-6">
                        <p class="text-sm font-extrabold text-ink">Tertarik mencari sekolah untuk buah hati?</p>
                        <p class="mt-1.5 text-sm text-ink-soft">Jelajahi 1.240+ sekolah Islam terverifikasi di Pesantrends.</p>
                        <a href="{{ route('schools.index') }}" class="btn-primary mt-4">
                            <x-app-icon name="search" class="h-4 w-4" :stroke="2.5"/>
                            Cari Sekolah Sekarang
                        </a>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="card-shadow sticky top-24 rounded-2xl bg-white p-6">
                        <h2 class="text-sm font-extrabold uppercase tracking-wider text-ink">Artikel Terbaru</h2>
                        <div class="mt-4 space-y-4">
                            @foreach ($latest as $item)
                                <a href="{{ route('articles.show', $item->slug) }}" class="group flex gap-3">
                                    <img src="{{ asset($item->image) }}" alt="" class="h-14 w-16 shrink-0 rounded-lg object-cover" loading="lazy">
                                    <div class="min-w-0">
                                        <h3 class="line-clamp-2 text-xs font-bold leading-snug text-ink group-hover:text-forest-700">{{ $item->title }}</h3>
                                        <p class="mt-1 text-[11px] text-ink-soft">{{ $item->read_minutes }} min baca</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-shadow rounded-2xl bg-forest-950 p-6">
                        <h2 class="text-sm font-extrabold text-white">Dapatkan Artikel Pilihan Setiap Minggu</h2>
                        <p class="mt-2 text-xs leading-relaxed text-white/70">Tips memilih sekolah &amp; info beasiswa langsung ke email Anda.</p>
                        <form action="{{ route('newsletter.store') }}" method="POST" class="mt-4 space-y-2">
                            @csrf
                            <input type="email" name="email" placeholder="Email Anda..." required aria-label="Alamat email"
                                   class="w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/50 focus:bg-white/20 focus:outline-none focus:ring-2 focus:ring-gold-400/50">
                            <button type="submit" class="btn-gold w-full !py-3 text-xs">Langganan</button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    @if ($related->count())
        <section class="bg-cream-50 py-14" aria-labelledby="artikel-terkait">
            <div class="container-app">
                <h2 id="artikel-terkait" class="text-xl font-extrabold tracking-tight text-ink">Artikel Terkait</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-article-card :article="$item"/>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
